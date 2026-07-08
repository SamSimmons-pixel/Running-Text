<?php

namespace App\Http\Controllers;

use App\Models\Kajian;
use App\Models\Narasumber;
use App\Models\Tempat;
use App\Models\Kontak;
use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class admin_dashboard_Controller extends Controller
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



    // ─────────────────────────────────────────────────────────────

    /**
     * Show the admin dashboard with all kajian records.
     */
    public function index()
    {
        $this->requireAdmin();

        $kajian = Kajian::orderBy('Tanggal', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();
        $kontakList     = Kontak::orderBy('nama', 'asc')->get();
        $acaraList      = Acara::orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();

        return view('admin_dashboard', compact('kajian', 'narasumberList', 'tempatList', 'kontakList', 'acaraList'));
    }

    

    public function informasi()
    {
        $this->requireAdmin();
        return view('informasi_umum');
    }

    public function acara()
    {
        $this->requireAdmin();
        return view('kelola_acara');
    }
}
