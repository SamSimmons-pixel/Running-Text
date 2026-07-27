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
     * Resolve the exact end datetime for a kajian based on explicit time string or dynamic prayer schedule.
     */
    public static function resolveEndTime($tanggal, $waktuSelesai): \Carbon\Carbon
    {
        $start = \Carbon\Carbon::parse($tanggal, 'Asia/Jakarta');
        if ($waktuSelesai) {
            if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $waktuSelesai)) {
                $parts = explode(':', $waktuSelesai);
                return $start->copy()->setTime((int)$parts[0], (int)$parts[1], (int)($parts[2] ?? 0));
            }

            try {
                $hijriService = app(\App\Services\HijriService::class);
                $schedule = $hijriService->getPrayerScheduleForDate($start);

                $textMap = [
                    'Menjelang Subuh'   => $schedule['subuh'] ?? '04:30',
                    'Menjelang Dzuhur'  => $schedule['dzuhur'] ?? '12:00',
                    'Menjelang Ashar'   => $schedule['ashar'] ?? '15:15',
                    'Menjelang Maghrib' => $schedule['maghrib'] ?? '18:00',
                    'Menjelang Isya'    => $schedule['isya'] ?? '19:15',
                ];

                if (isset($textMap[$waktuSelesai])) {
                    $parts = explode(':', $textMap[$waktuSelesai]);
                    return $start->copy()->setTime((int)$parts[0], (int)$parts[1]);
                }
            } catch (\Exception $e) {
                $fallbackMap = [
                    'Menjelang Subuh'   => '04:30',
                    'Menjelang Dzuhur'  => '12:00',
                    'Menjelang Ashar'   => '15:15',
                    'Menjelang Maghrib' => '18:00',
                    'Menjelang Isya'    => '19:15',
                ];
                if (isset($fallbackMap[$waktuSelesai])) {
                    $parts = explode(':', $fallbackMap[$waktuSelesai]);
                    return $start->copy()->setTime((int)$parts[0], (int)$parts[1]);
                }
            }
        }
        return $start->copy()->addHour();
    }

    /**
     * Automatically update expired kajian records to not display in JSON feed.
     */
    public static function autoUpdateExpiredKajian(): void
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $kajianList = Kajian::where('Tampilkan', true)->get();

        foreach ($kajianList as $item) {
            $end = self::resolveEndTime($item->Tanggal, $item->WaktuSelesai);
            if ($now->greaterThan($end)) {
                $item->update(['Tampilkan' => false]);
            }
        }
    }

    public static function isKajianOnAir($tanggal, $waktuSelesai): bool
    {
        $start = \Carbon\Carbon::parse($tanggal, 'Asia/Jakarta');
        $end = self::resolveEndTime($tanggal, $waktuSelesai);

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
        $kajian->load(['narasumber', 'tempat', 'kontak']);

        $narasumberName = $kajian->narasumber->nama ?? '—';
        $tempatName     = $kajian->tempat->nama ?? '—';
        broadcast(new NotificationChange(Auth::user()->name . " menambahkan kajian baru \"{$kajian->Judul}\" (Narasumber: {$narasumberName}, Tempat: {$tempatName})"))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil ditambahkan.');
    }

    /**
     * Update an existing kajian record.
     */
    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $kajian = Kajian::with(['narasumber', 'tempat', 'kontak'])->findOrFail($id);

        $oldData = [
            'Judul'        => $kajian->Judul,
            'Tanggal'      => $kajian->Tanggal,
            'WaktuSelesai' => $kajian->WaktuSelesai,
            'narasumber'   => $kajian->narasumber->nama ?? '—',
            'tempat'       => $kajian->tempat->nama ?? '—',
            'kontak'       => $kajian->kontak->nama ?? '—',
            'Informasi'    => $kajian->Informasi,
        ];

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
        $kajian->load(['narasumber', 'tempat', 'kontak']);

        $changes = [];
        if (trim($oldData['Judul']) !== trim($kajian->Judul)) {
            $changes[] = "judul dari \"{$oldData['Judul']}\" menjadi \"{$kajian->Judul}\"";
        }
        $oldTglNorm = \Carbon\Carbon::parse($oldData['Tanggal'])->format('Y-m-d H:i');
        $newTglNorm = \Carbon\Carbon::parse($kajian->Tanggal)->format('Y-m-d H:i');
        if ($oldTglNorm !== $newTglNorm) {
            $oldTglDisplay = \Carbon\Carbon::parse($oldData['Tanggal'])->format('d-m-Y H:i');
            $newTglDisplay = \Carbon\Carbon::parse($kajian->Tanggal)->format('d-m-Y H:i');
            $changes[] = "waktu mulai dari \"{$oldTglDisplay}\" menjadi \"{$newTglDisplay}\"";
        }
        $oldSelesaiNorm = $oldData['WaktuSelesai'] ? preg_replace('/^(\d{2}:\d{2}):00$/', '$1', trim($oldData['WaktuSelesai'])) : '';
        $newSelesaiNorm = $kajian->WaktuSelesai ? preg_replace('/^(\d{2}:\d{2}):00$/', '$1', trim($kajian->WaktuSelesai)) : '';
        if ($oldSelesaiNorm !== $newSelesaiNorm) {
            $oldSelesai = $oldData['WaktuSelesai'] ?: '—';
            $newSelesai = $kajian->WaktuSelesai ?: '—';
            $changes[] = "waktu selesai dari \"{$oldSelesai}\" menjadi \"{$newSelesai}\"";
        }
        $newNarasumber = $kajian->narasumber->nama ?? '—';
        if ($oldData['narasumber'] !== $newNarasumber) {
            $changes[] = "narasumber dari \"{$oldData['narasumber']}\" menjadi \"{$newNarasumber}\"";
        }
        $newTempat = $kajian->tempat->nama ?? '—';
        if ($oldData['tempat'] !== $newTempat) {
            $changes[] = "tempat dari \"{$oldData['tempat']}\" menjadi \"{$newTempat}\"";
        }
        $newKontak = $kajian->kontak->nama ?? '—';
        if ($oldData['kontak'] !== $newKontak) {
            $changes[] = "kontak dari \"{$oldData['kontak']}\" menjadi \"{$newKontak}\"";
        }
        if ($oldData['Informasi'] !== $kajian->Informasi) {
            $changes[] = "informasi tambahan diperbarui";
        }

        $judulRef = $oldData['Judul'];
        if (count($changes) > 0) {
            $msg = Auth::user()->name . " mengubah kajian \"{$judulRef}\": " . implode('; ', $changes);
        } else {
            $msg = Auth::user()->name . " memperbarui kajian \"{$judulRef}\"";
        }
        broadcast(new NotificationChange($msg))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Check if kajian time has expired.
     */
    public static function isKajianExpired($tanggal, $waktuSelesai): bool
    {
        $end = self::resolveEndTime($tanggal, $waktuSelesai);

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
        broadcast(new NotificationChange(Auth::user()->name . " mengubah status tampil kajian '{$kajian->Judul}' menjadi {$statusText}"))->toOthers();

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

        broadcast(new NotificationChange(Auth::user()->name . " menghapus kajian \"{$judul}\""))->toOthers();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil dihapus.');
    }
}
