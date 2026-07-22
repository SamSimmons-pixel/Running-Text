<?php

namespace App\Http\Controllers;

use App\Events\NotificationChange;
use App\Models\Narasumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NarasumberController extends Controller
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

        // Eager-load kajian and acara counts for usage warnings
        $narasumberList = Narasumber::withCount(['kajian', 'acara'])
            ->with(['kajian:id,narasumber_id,Judul', 'acara:id,narasumber_id,judul'])
            ->orderBy('nama', 'asc')
            ->get();

        return view('narasumber', compact('narasumberList'));
    }

    public function store(Request $request)
    {
        $this->requireOperator();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:narasumber,nama'],
        ]);

        $data['author'] = Auth::user()->name;
        $data['last_modified_by'] = Auth::user()->name;

        $narasumber = Narasumber::create($data);

        broadcast(new NotificationChange(Auth::user()->name . " menambahkan narasumber baru \"{$narasumber->nama}\""))->toOthers();

        return redirect()->route('admin.narasumber')
            ->with('success', 'Narasumber berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->requireOperator();

        $narasumber = Narasumber::findOrFail($id);
        $nama = $narasumber->nama;
        // FK is SET NULL on delete — Eloquent will fire the delete and DB handles nullification
        $narasumber->delete();

        broadcast(new NotificationChange(Auth::user()->name . " menghapus narasumber \"" . $nama . "\""))->toOthers();

        return redirect()->route('admin.narasumber')
            ->with('success', 'Narasumber "' . $nama . '" berhasil dihapus. Data terkait di Kajian dan Acara telah diset ke kosong (—).');
    }

    public function update(Request $request, $id) {
        $this->requireOperator();

        $narasumber = Narasumber::findOrFail($id);
        $namaBefore = $narasumber->nama;

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:narasumber,nama,' . $id],
        ]);

        $narasumber->update($data);

        $namaAfter = $narasumber->fresh()->nama;
        if ($namaBefore !== $namaAfter) {
            $msg = Auth::user()->name . " mengubah nama narasumber dari \"{$namaBefore}\" menjadi \"{$namaAfter}\"";
        } else {
            $msg = Auth::user()->name . " memperbarui narasumber \"{$namaAfter}\"";
        }
        broadcast(new NotificationChange($msg))->toOthers();

        return redirect()->route('admin.narasumber')->with('success', 'Narasumber berhasil diperbarui');
    }
}
