<?php

namespace App\Http\Controllers;

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
    private function requireAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Show the dedicated Kelola Kajian page (full CRUD).
     */
    public function kajian()
    {
        $this->requireAdmin();

        $kajian         = Kajian::orderBy('Tanggal', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();
        $kontakList     = Kontak::orderBy('nama', 'asc')->get();

        return view('kelola_kajian', compact('kajian', 'narasumberList', 'tempatList', 'kontakList'));
    }

    /**
     * Store a new kajian record.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'Tanggal'    => ['required', 'date'],
            'Judul'      => ['required', 'string', 'max:255'],
            'Narasumber' => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'     => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'     => ['nullable', 'string', 'exists:kontak,nama'],
            'Tampilkan'  => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');

        Kajian::create($data);

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil ditambahkan.');
    }

    /**
     * Update an existing kajian record.
     */
    public function update(Request $request, $id)
    {
        $this->requireAdmin();

        $kajian = Kajian::findOrFail($id);

        $data = $request->validate([
            'Tanggal'    => ['required', 'date'],
            'Judul'      => ['required', 'string', 'max:255'],
            'Narasumber' => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'     => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'     => ['nullable', 'string', 'exists:kontak,nama'],
            'Tampilkan'  => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');

        $kajian->update($data);

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Toggle the Tampilkan (show/hide kajian) flag.
     */
    public function toggle($id)
    {
        $this->requireAdmin();

        $kajian = Kajian::findOrFail($id);
        $kajian->update(['Tampilkan' => !$kajian->Tampilkan]);

        return redirect()->route('admin.kajian')
            ->with('success', 'Status tampil kajian diperbarui.');
    }

    /**
     * Delete a kajian record.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $kajian = Kajian::findOrFail($id);
        $kajian->delete();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil dihapus.');
    }

}
