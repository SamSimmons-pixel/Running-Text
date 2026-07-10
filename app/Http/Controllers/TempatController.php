<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempatController extends Controller
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
     * Show the Tempat management page.
     */
    public function index()
    {
        $this->requireOperator();

        $tempatList = Tempat::orderBy('nama', 'asc')->get();

        return view('tempat', compact('tempatList'));
    }

    /**
     * Store a new Tempat.
     */
    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:255', 'unique:tempat,nama'],
            'deskripsi_alamat' => ['nullable', 'string', 'max:1000'],
        ]);

        Tempat::create($data);

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil ditambahkan.');
    }

    /**
     * Update an existing Tempat.
     */
    public function update(Request $request, $id)
    {
        $this->requireOperator();

        $tempat = Tempat::findOrFail($id);

        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:255', 'unique:tempat,nama,' . $id],
            'deskripsi_alamat' => ['nullable', 'string', 'max:1000'],
        ]);

        $tempat->update($data);

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil diperbarui.');
    }

    /**
     * Delete a Tempat.
     */
    public function destroy($id)
    {
        $this->requireOperator();

        $tempat = Tempat::findOrFail($id);
        $tempat->delete();

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil dihapus.');
    }
}
