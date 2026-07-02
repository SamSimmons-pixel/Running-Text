<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontakController extends Controller
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
     * Show the Kontak management page.
     */
    public function index()
    {
        $this->requireAdmin();

        $kontakList = Kontak::orderBy('nama', 'asc')->get();

        return view('kontak', compact('kontakList'));
    }

    /**
     * Store a new Kontak.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kontak,nama'],
        ]);

        Kontak::create($data);

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak berhasil ditambahkan.');
    }

    /**
     * Delete a Kontak.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak berhasil dihapus.');
    }
}
