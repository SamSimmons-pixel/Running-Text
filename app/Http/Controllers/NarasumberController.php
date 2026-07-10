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
    private function requireOperator(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin_operator', 'operator'])) {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Show the Narasumber management page.
     */
    public function index()
    {
        $this->requireOperator();

        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();

        return view('narasumber', compact('narasumberList'));
    }

    /**
     * Store a new Narasumber.
     */
    public function store(Request $request)
    {
        $this->requireOperator();

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
        $this->requireOperator();

        $narasumber = Narasumber::findOrFail($id);
        $narasumber->delete();

        return redirect()->route('admin.narasumber')
            ->with('success', 'Narasumber berhasil dihapus.');
    }
}
