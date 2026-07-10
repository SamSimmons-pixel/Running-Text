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
        $this->requireOperator();

        $data = $request->validate([
            'Tanggal'      => ['required', 'date'],
            'WaktuSelesai' => ['nullable', 'string', 'max:255'],
            'Judul'        => ['required', 'string', 'max:255'],
            'Narasumber'   => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'       => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'       => ['nullable', 'string', 'exists:kontak,nama'],
            'Informasi'    => ['nullable', 'string', 'max:1000'],
            'Tampilkan'    => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');
        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        Kajian::create($data);

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
            'Narasumber'   => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'       => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'       => ['nullable', 'string', 'exists:kontak,nama'],
            'Informasi'    => ['nullable', 'string', 'max:10000'],
            'Tampilkan'    => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');
        $data['last_modified_by'] = Auth::user()->name;

        $kajian->update($data);

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Toggle the Tampilkan (show/hide kajian) flag.
     */
    public function toggle($id)
    {
        $this->requireOperator();

        $kajian = Kajian::findOrFail($id);
        $kajian->update([
            'Tampilkan' => !$kajian->Tampilkan,
            'last_modified_by' => Auth::user()->name
        ]);

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
        $kajian->delete();

        return redirect()->route('admin.kajian')
            ->with('success', 'Kajian berhasil dihapus.');
    }

}
