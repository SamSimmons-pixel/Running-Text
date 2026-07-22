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
            'hari'         => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'status'       => ['nullable', 'string', 'max:100'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $acara = Acara::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " telah menambahkan acara baru: " . $acara->judul))->toOthers();

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $judulBefore = $acara->judul;

        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'hari'         => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber_id'=> ['required', 'integer', 'exists:narasumber,id'],
            'tempat_id'    => ['required', 'integer', 'exists:tempat,id'],
            'status'       => ['nullable', 'string', 'max:100'],
        ]);

        $data['last_modified_by'] = Auth::user()->name;

        $acara->update($data);

        if ($judulBefore !== $acara->judul) {
            $msg = Auth::user()->name . " mengubah judul acara dari \"{$judulBefore}\" menjadi \"{$acara->judul}\"";
        } else {
            $msg = Auth::user()->name . " memperbarui acara \"{$acara->judul}\"";
        }
        broadcast(new NotificationChange($msg))->toOthers();

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $judul = $acara->judul;
        $acara->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus acara \"{$judul}\""))->toOthers();

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
        broadcast(new NotificationChange(Auth::user()->name . " mengubah status tampil acara '{$acara->judul}' menjadi {$statusText}"))->toOthers();

        return redirect()->route('admin.acara')
            ->with('success', 'Status visibilitas acara berhasil diperbarui.');
    }
}
