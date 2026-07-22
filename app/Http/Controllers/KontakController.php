<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontakController extends Controller
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

        $kontakList = Kontak::withCount(['kajian'])
            ->with(['kajian:id,kontak_id,Judul'])
            ->orderBy('nama', 'asc')
            ->get();

        return view('kontak', compact('kontakList'));
    }

    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'max:255', 'unique:kontak,nomor_kontak'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $kontak = Kontak::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " menambahkan kontak baru \"{$kontak->nama}\" ({$kontak->nomor_kontak})"))->toOthers();

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $kontak = Kontak::findOrFail($id);
        $nama = $kontak->nama;
        $kontak->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus kontak \"{$nama}\""))->toOthers();

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak "' . $nama . '" berhasil dihapus. Data terkait di Kajian telah diset ke kosong (—).');
    }

    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $kontak = Kontak::findOrFail($id);
        $namaBefore   = $kontak->nama;
        $nomorBefore  = $kontak->nomor_kontak;

        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'max:255', 'unique:kontak,nomor_kontak,' . $id],
        ]);

        $data['last_modified_by'] = Auth::user()->name;

        $kontak->update($data);

        $changes = [];
        if ($namaBefore !== $kontak->nama) {
            $changes[] = "nama dari \"{$namaBefore}\" menjadi \"{$kontak->nama}\"";
        }
        if ($nomorBefore !== $kontak->nomor_kontak) {
            $changes[] = "nomor kontak dari \"{$nomorBefore}\" menjadi \"{$kontak->nomor_kontak}\"";
        }

        if (count($changes) > 0) {
            $msg = Auth::user()->name . " mengubah kontak \"{$namaBefore}\": " . implode('; ', $changes);
        } else {
            $msg = Auth::user()->name . " memperbarui kontak \"{$kontak->nama}\"";
        }
        broadcast(new NotificationChange($msg))->toOthers();

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak berhasil diperbarui.');
    }
}
