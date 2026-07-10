<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Narasumber;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcaraController extends Controller
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
     * Show the Kelola Acara management page.
     */
    public function index()
    {
        $this->requireOperator();

        $acara          = Acara::orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();

        return view('kelola_acara', compact('acara', 'narasumberList', 'tempatList'));
    }

    /**
     * Store a new Acara record.
     */
    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'hari'        => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber'  => ['required', 'string', 'exists:narasumber,nama'],
            'tempat'      => ['required', 'string', 'exists:tempat,nama'],
            'status'      => ['nullable', 'string', 'max:100'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        Acara::create($data);

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil ditambahkan.');
    }

    /**
     * Update an existing Acara record.
     */
    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);

        $data = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'hari'        => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'narasumber'  => ['required', 'string', 'exists:narasumber,nama'],
            'tempat'      => ['required', 'string', 'exists:tempat,nama'],
            'status'      => ['nullable', 'string', 'max:100'],
        ]);

        $data['last_modified_by'] = Auth::user()->name;

        $acara->update($data);

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    /**
     * Delete an Acara record.
     */
    public function destroy($id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $acara->delete();

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil dihapus.');
    }

    /**
     * Toggle the tampilkan flag for an Acara.
     */
    public function toggle($id)
    {
        $this->requireOperator();

        $acara = Acara::findOrFail($id);
        $acara->tampilkan = !$acara->tampilkan;
        $acara->last_modified_by = Auth::user()->name;
        $acara->save();

        return redirect()->route('admin.acara')
            ->with('success', 'Status visibilitas acara berhasil diperbarui.');
    }
}
