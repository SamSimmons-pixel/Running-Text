<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\Acara;
use App\Models\Narasumber;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcaraController extends Controller
{
    private function requireOperator(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin_operator', 'operator'])) {
            abort(403, 'Unauthorized!');
        }
    }

    public function index()
    {
        $this->requireOperator();

        $acara          = Acara::with(['narasumber', 'tempat'])->orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();

        return view('kelola_acara', compact('acara', 'narasumberList', 'tempatList'));
    }

    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'hari'         => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jum\'at,Sabtu,Ahad'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'status'       => ['nullable', 'string', 'max:100'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $acara = Acara::create($data);
        $acara->load(['narasumber', 'tempat']);

        $narasumberName = $acara->narasumber->nama ?? '—';
        $tempatName     = $acara->tempat->nama ?? '—';
        broadcast(new NotificationChange(Auth::user()->name . " menambahkan acara baru \"{$acara->judul}\" ({$acara->hari}, {$acara->jam_mulai}-{$acara->jam_selesai}, Narasumber: {$narasumberName}, Tempat: {$tempatName})"));

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $acara = Acara::with(['narasumber', 'tempat'])->findOrFail($id);

        $oldData = [
            'judul'        => $acara->judul,
            'hari'         => $acara->hari,
            'jam_mulai'    => $acara->jam_mulai,
            'jam_selesai'  => $acara->jam_selesai,
            'narasumber'   => $acara->narasumber->nama ?? '—',
            'tempat'       => $acara->tempat->nama ?? '—',
            'status'       => $acara->status ?? '—',
        ];

        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'hari'         => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jum\'at,Sabtu,Ahad'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'status'       => ['nullable', 'string', 'max:100'],
        ]);

        $data['last_modified_by'] = Auth::user()->name;

        $acara->update($data);
        $acara->load(['narasumber', 'tempat']);

        $changes = [];
        if (trim($oldData['judul']) !== trim($acara->judul)) {
            $changes[] = "judul dari \"{$oldData['judul']}\" menjadi \"{$acara->judul}\"";
        }
        if (trim($oldData['hari']) !== trim($acara->hari)) {
            $changes[] = "hari dari \"{$oldData['hari']}\" menjadi \"{$acara->hari}\"";
        }
        $oldMulaiNorm = $oldData['jam_mulai'] ? date('H:i', strtotime($oldData['jam_mulai'])) : '';
        $newMulaiNorm = $acara->jam_mulai ? date('H:i', strtotime($acara->jam_mulai)) : '';
        if ($oldMulaiNorm !== $newMulaiNorm) {
            $changes[] = "jam mulai dari \"{$oldMulaiNorm}\" menjadi \"{$newMulaiNorm}\"";
        }
        $oldSelesaiNorm = $oldData['jam_selesai'] ? date('H:i', strtotime($oldData['jam_selesai'])) : '';
        $newSelesaiNorm = $acara->jam_selesai ? date('H:i', strtotime($acara->jam_selesai)) : '';
        if ($oldSelesaiNorm !== $newSelesaiNorm) {
            $changes[] = "jam selesai dari \"{$oldSelesaiNorm}\" menjadi \"{$newSelesaiNorm}\"";
        }
        $newNarasumber = $acara->narasumber->nama ?? '—';
        if ($oldData['narasumber'] !== $newNarasumber) {
            $changes[] = "narasumber dari \"{$oldData['narasumber']}\" menjadi \"{$newNarasumber}\"";
        }
        $newTempat = $acara->tempat->nama ?? '—';
        if ($oldData['tempat'] !== $newTempat) {
            $changes[] = "tempat dari \"{$oldData['tempat']}\" menjadi \"{$newTempat}\"";
        }
        $newStatus = $acara->status ?? '—';
        if ($oldData['status'] !== $newStatus) {
            $changes[] = "status dari \"{$oldData['status']}\" menjadi \"{$newStatus}\"";
        }

        $judulRef = $oldData['judul'];
        if (count($changes) > 0) {
            $msg = Auth::user()->name . " mengubah acara \"{$judulRef}\": " . implode('; ', $changes);
        } else {
            $msg = Auth::user()->name . " memperbarui acara \"{$judulRef}\"";
        }
        broadcast(new NotificationChange($msg));

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $judul = $acara->judul;
        $acara->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus acara \"{$judul}\""));

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil dihapus.');
    }

    public function toggle($id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $acara->tampilkan = !$acara->tampilkan;
        $acara->last_modified_by = Auth::user()->name;
        $acara->save();

        $statusText = $acara->tampilkan ? "ditampilkan" : "disembunyikan";
        broadcast(new NotificationChange(Auth::user()->name . " mengubah status tampil acara '{$acara->judul}' menjadi {$statusText}"));

        return redirect()->route('admin.acara')
            ->with('success', 'Status visibilitas acara berhasil diperbarui.');
    }
}
