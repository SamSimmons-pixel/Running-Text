<?php

namespace App\Http\Controllers;

use App\Models\Narasumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NarasumberController extends Controller
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
     * Show the Narasumber management page.
     */
    public function index()
    {
        $this->requireAdmin();

        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();

        return view('narasumber', compact('narasumberList'));
    }

    /**
     * Store a new Narasumber.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:narasumber,nama'],
        ]);

        Narasumber::create($data);

        return redirect()->route('admin.narasumber')
            ->with('success', 'Narasumber berhasil ditambahkan.');
    }

    /**
     * Delete a Narasumber.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $narasumber = Narasumber::findOrFail($id);
        $narasumber->delete();

        return redirect()->route('admin.narasumber')
            ->with('success', 'Narasumber berhasil dihapus.');
    }
}
