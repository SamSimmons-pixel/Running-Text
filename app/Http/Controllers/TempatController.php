<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempatController extends Controller
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

        $tempatList = Tempat::withCount(['kajian', 'acara'])
            ->with(['kajian:id,tempat_id,Judul', 'acara:id,tempat_id,judul'])
            ->orderBy('nama', 'asc')
            ->get();

        return view('tempat', compact('tempatList'));
    }

    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:255', 'unique:tempat,nama'],
            'deskripsi_alamat' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $tempat = Tempat::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " menambahkan tempat baru \"{$tempat->nama}\""));

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $tempat = Tempat::findOrFail($id);
        $namaBefore = $tempat->nama;
        $deskripsiBefore = $tempat->deskripsi_alamat ?? '—';

        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:255', 'unique:tempat,nama,' . $id],
            'deskripsi_alamat' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['last_modified_by'] = Auth::user()->name;

        $tempat->update($data);

        $changes = [];
        if ($namaBefore !== $tempat->nama) {
            $changes[] = "nama dari \"{$namaBefore}\" menjadi \"{$tempat->nama}\"";
        }
        $deskripsiAfter = $tempat->deskripsi_alamat ?? '—';
        if ($deskripsiBefore !== $deskripsiAfter) {
            $changes[] = "deskripsi alamat dari \"{$deskripsiBefore}\" menjadi \"{$deskripsiAfter}\"";
        }

        if (count($changes) > 0) {
            $msg = Auth::user()->name . " mengubah tempat \"{$namaBefore}\": " . implode('; ', $changes);
        } else {
            $msg = Auth::user()->name . " memperbarui tempat \"{$tempat->nama}\"";
        }
        broadcast(new NotificationChange($msg));

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $tempat = Tempat::findOrFail($id);
        $nama = $tempat->nama;
        $tempat->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus tempat \"{$nama}\""));

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat "' . $nama . '" berhasil dihapus. Data terkait di Kajian dan Acara telah diset ke kosong (—).');
    }
}
