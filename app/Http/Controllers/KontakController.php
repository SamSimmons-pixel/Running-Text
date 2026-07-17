<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontakController extends Controller
{
    private function requireOperator(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin_operator', 'operator'])) {
            abort(403, 'Unauthorized!');
        }
    }

    public function index()
    {
        $this->requireOperator();

        $kontakList = Kontak::withCount(['kajian'])
            ->with(['kajian:id,kontak_id,Judul'])
            ->orderBy('nama', 'asc')
            ->get();

        return view('kontak', compact('kontakList'));
    }

    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'max:255', 'unique:kontak,nomor_kontak'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        Kontak::create($data);

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->route('admin.kontak')
            ->with('success', 'Kontak "' . $kontak->nama . '" berhasil dihapus. Data terkait di Kajian telah diset ke kosong (—).');
    }
}
