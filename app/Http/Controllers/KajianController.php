<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\Kajian;
use App\Models\Narasumber;
use App\Models\Tempat;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KajianController extends Controller
{
    /**
     * Guard: only admin role may access any method in this controller.
     */
    private function requireOperator(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin_operator', 'operator'])) {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Show the dedicated Kelola Kajian page (full CRUD).
     */
    public function kajian()
    {
        $this->requireOperator();

        self::autoUpdateExpiredKajian();

        $kajian         = Kajian::with(['narasumber', 'tempat', 'kontak'])->orderBy('Tanggal', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();
        $kontakList     = Kontak::orderBy('nama', 'asc')->get();

        return view('kelola_kajian', compact('kajian', 'narasumberList', 'tempatList', 'kontakList'));
    }

    /**
     * Automatically update expired kajian records to not display in JSON feed.
     */
    public static function autoUpdateExpiredKajian(): void
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $kajianList = Kajian::where('Tampilkan', true)->get();

        foreach ($kajianList as $item) {
            $start = \Carbon\Carbon::parse($item->Tanggal, 'Asia/Jakarta');
            $end = null;

            if ($item->WaktuSelesai) {
                if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $item->WaktuSelesai)) {
                    $parts = explode(':', $item->WaktuSelesai);
                    $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1], (int)($parts[2] ?? 0));
                } else {
                    $textMap = [
                        'Menjelang Dzuhur'  => '12:00',
                        'Menjelang Ashar'   => '15:30',
                        'Menjelang Maghrib' => '18:00',
                        'Menjelang Isya'    => '19:30',
                    ];
                    if (isset($textMap[$item->WaktuSelesai])) {
                        $parts = explode(':', $textMap[$item->WaktuSelesai]);
                        $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1]);
                    } else {
                        $end = $start->copy()->addHour();
                    }
                }
            } else {
                $end = $start->copy()->addHour();
            }

            if ($now->greaterThan($end)) {
                $item->update(['Tampilkan' => false]);
            }
        }
    }

    public static function isKajianOnAir($tanggal, $waktuSelesai): bool
    {
        $start = \Carbon\Carbon::parse($tanggal, 'Asia/Jakarta');
        $end = null;

        if ($waktuSelesai) {
            if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $waktuSelesai)) {
                $parts = explode(':', $waktuSelesai);
                $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1], (int)($parts[2] ?? 0));
            } else {
                $textMap = [
                    'Menjelang Dzuhur'  => '12:00',
                    'Menjelang Ashar'   => '15:30',
                    'Menjelang Maghrib' => '18:00',
                    'Menjelang Isya'    => '19:30',
                ];
                if (isset($textMap[$waktuSelesai])) {
                    $parts = explode(':', $textMap[$waktuSelesai]);
                    $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1]);
                } else {
                    $end = $start->copy()->addHour();
                }
            }
        } else {
            $end = $start->copy()->addHour();
        }

        return \Carbon\Carbon::now('Asia/Jakarta')->between($start, $end);
    }

    /**
     * Store a new kajian record.
     */
    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'Tanggal'      => ['required', 'date'],
            'WaktuSelesai' => ['nullable', 'string', 'max:255'],
            'Judul'        => ['required', 'string', 'max:255'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'kontak_id'    => ['nullable', 'integer', 'exists:kontak,id'],
            'Informasi'    => ['nullable', 'string', 'max:1000'],
            'Tampilkan'    => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');
        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $kajian = Kajian::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " telah menambahkan kajian baru: " . $kajian->Judul))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil ditambahkan.');
    }

    /**
     * Update an existing kajian record.
     */
    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $kajian = Kajian::findOrFail($id);

        $data = $request->validate([
            'Tanggal'      => ['required', 'date'],
            'WaktuSelesai' => ['nullable', 'string', 'max:255'],
            'Judul'        => ['required', 'string', 'max:255'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'kontak_id'    => ['nullable', 'integer', 'exists:kontak,id'],
            'Informasi'    => ['nullable', 'string', 'max:10000'],
            'Tampilkan'    => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');
        $data['last_modified_by'] = Auth::user()->name;

        $kajian->update($data);

        broadcast(new NotificationChange(Auth::user()->name . " telah mengubah kajian " . $kajian->Judul))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Check if kajian time has expired.
     */
    public static function isKajianExpired($tanggal, $waktuSelesai): bool
    {
        $start = \Carbon\Carbon::parse($tanggal, 'Asia/Jakarta');
        $end = null;
        if ($waktuSelesai) {
            if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $waktuSelesai)) {
                $parts = explode(':', $waktuSelesai);
                $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1], (int)($parts[2] ?? 0));
            } else {
                $textMap = [
                    'Menjelang Dzuhur'  => '12:00',
                    'Menjelang Ashar'   => '15:30',
                    'Menjelang Maghrib' => '18:00',
                    'Menjelang Isya'    => '19:30',
                ];
                if (isset($textMap[$waktuSelesai])) {
                    $parts = explode(':', $textMap[$waktuSelesai]);
                    $end = $start->copy()->setTime((int)$parts[0], (int)$parts[1]);
                } else {
                    $end = $start->copy()->addHour();
                }
            }
        } else {
            $end = $start->copy()->addHour();
        }
        return \Carbon\Carbon::now('Asia/Jakarta')->greaterThan($end);
    }

    /**
     * Toggle the Tampilkan flag.
     */
    public function toggle($id)
    {
        $this->requireOperator();

        $kajian = Kajian::findOrFail($id);
        if (self::isKajianExpired($kajian->Tanggal, $kajian->WaktuSelesai)) {
            return redirect()->route('admin.kajian')
                ->with('error', 'Status tampil tidak dapat diubah karena waktu kajian sudah terlewati.');
        }

        $kajian->update([
            'Tampilkan' => !$kajian->Tampilkan,
            'last_modified_by' => Auth::user()->name,
        ]);

        $statusText = $kajian->Tampilkan ? "ditampilkan" : "disembunyikan";
        broadcast(new NotificationChange(Auth::user()->name . " telah mengubah status tampil kajian '{$kajian->Judul}' menjadi {$statusText}"))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Status tampil kajian diperbarui.');
    }

    /**
     * Delete a kajian record.
     */
    public function destroy($id)
    {
        $this->requireOperator();

        $kajian = Kajian::findOrFail($id);
        $judul = $kajian->Judul;
        $kajian->delete();

        broadcast(new NotificationChange(Auth::user()->name . " telah menghapus kajian " . $judul))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil dihapus.');
    }
}
