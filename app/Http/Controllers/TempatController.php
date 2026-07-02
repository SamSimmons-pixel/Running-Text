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
    private function requireAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Show the Tempat management page.
     */
    public function index()
    {
        $this->requireAdmin();

        $tempatList = Tempat::orderBy('nama', 'asc')->get();

        return view('tempat', compact('tempatList'));
    }

    /**
     * Store a new Tempat.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:tempat,nama'],
        ]);

        Tempat::create($data);

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil ditambahkan.');
    }

    /**
     * Delete a Tempat.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $tempat = Tempat::findOrFail($id);
        $tempat->delete();

        return redirect()->route('admin.tempat')
            ->with('success', 'Tempat berhasil dihapus.');
    }
}
