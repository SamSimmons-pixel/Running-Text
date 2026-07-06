<?php

namespace App\Http\Controllers;

use App\Models\InformasiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformasiUmumController extends Controller
{
    /**
     * Guard: only admin role may access any method in this controller.
     */
    private function requireAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Show the Informasi Umum list and add form.
     */
    public function index()
    {
        $this->requireAdmin();

        $informasiList = InformasiUmum::orderBy('created_at', 'desc')->get();

        return view('informasi_umum', compact('informasiList'));
    }

    /**
     * Store a new Informasi Umum.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tampilkan' => ['nullable', 'boolean'],
        ]);

        $data['tampilkan'] = $request->has('tampilkan');

        InformasiUmum::create($data);

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil ditambahkan.');
    }

    /**
     * Update an existing Informasi Umum.
     */
    public function update(Request $request, $id)
    {
        $this->requireAdmin();

        $info = InformasiUmum::findOrFail($id);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tampilkan' => ['nullable', 'boolean'],
        ]);

        $data['tampilkan'] = $request->has('tampilkan');

        $info->update($data);

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil diperbarui.');
    }

    /**
     * Toggle the tampilkan flag.
     */
    public function toggle($id)
    {
        $this->requireAdmin();

        $info = InformasiUmum::findOrFail($id);
        $info->update(['tampilkan' => !$info->tampilkan]);

        return redirect()->route('admin.informasi')
            ->with('success', 'Status tampil informasi diperbarui.');
    }

    /**
     * Delete an Informasi Umum record.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $info = InformasiUmum::findOrFail($id);
        $info->delete();

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil dihapus.');
    }
}
