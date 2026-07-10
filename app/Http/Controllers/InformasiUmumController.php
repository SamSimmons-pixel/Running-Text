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

        InformasiUmum::create($data);

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

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tampilkan' => ['nullable', 'boolean'],
        ]);

        $data['tampilkan'] = $request->has('tampilkan');
        $data['last_modified_by'] = Auth::user()->name;

        $info->update($data);

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
        $info->delete();

        return redirect()->route('admin.informasi')
            ->with('success', 'Informasi Umum berhasil dihapus.');
    }
}
