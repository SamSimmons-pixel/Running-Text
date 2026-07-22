<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\InformasiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformasiUmumController extends Controller
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
     * Show the Informasi Umum list and add form.
     */
    public function index()
    {
        $this->requireOperator();

        $informasiList = InformasiUmum::orderBy('created_at', 'desc')->get();

        return view('informasi_umum', compact('informasiList'));
    }

    /**
     * Store a new Informasi Umum.
     */
    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tampilkan' => ['nullable', 'boolean'],
        ]);

        $data['tampilkan'] = $request->has('tampilkan');
        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $info = InformasiUmum::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " menambahkan informasi umum baru \"{$info->judul}\""))->toOthers();

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil ditambahkan.');
    }

    /**
     * Update an existing Informasi Umum.
     */
    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $info = InformasiUmum::findOrFail($id);
        $judulBefore     = $info->judul;
        $deskripsiBefore = $info->deskripsi;

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tampilkan' => ['nullable', 'boolean'],
        ]);

        $data['tampilkan'] = $request->has('tampilkan');
        $data['last_modified_by'] = Auth::user()->name;

        $info->update($data);

        $changes = [];
        if ($judulBefore !== $info->judul) {
            $changes[] = "judul dari \"{$judulBefore}\" menjadi \"{$info->judul}\"";
        }
        if ($deskripsiBefore !== $info->deskripsi) {
            $changes[] = "deskripsi informasi diperbarui";
        }

        if (count($changes) > 0) {
            $msg = Auth::user()->name . " mengubah informasi umum \"{$judulBefore}\": " . implode('; ', $changes);
        } else {
            $msg = Auth::user()->name . " memperbarui informasi umum \"{$info->judul}\"";
        }
        broadcast(new NotificationChange($msg))->toOthers();

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil diperbarui.');
    }

    /**
     * Toggle the tampilkan flag.
     */
    public function toggle($id)
    {
        $this->requireOperator();

        $info = InformasiUmum::findOrFail($id);
        $info->update([
            'tampilkan' => !$info->tampilkan,
            'last_modified_by' => Auth::user()->name
        ]);

        $statusText = $info->tampilkan ? "ditampilkan" : "disembunyikan";
        broadcast(new NotificationChange(Auth::user()->name . " mengubah status tampil informasi '{$info->judul}' menjadi {$statusText}"))->toOthers();

        return redirect()->route('admin.informasi')
            ->with('success', 'Status tampil informasi diperbarui.');
    }

    /**
     * Delete an Informasi Umum record.
     */
    public function destroy($id)
    {
        $this->requireOperator();

        $info = InformasiUmum::findOrFail($id);
        $judul = $info->judul;
        $info->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus informasi umum \"{$judul}\""))->toOthers();

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil dihapus.');
    }
}
