<?php

namespace App\Http\Controllers;

use App\Models\Kajian;
use App\Models\Narasumber;
use App\Models\Tempat;
use App\Models\Kontak;
use App\Models\Acara;
use App\Models\InformasiUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class admin_dashboard_Controller extends Controller
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



    // ─────────────────────────────────────────────────────────────

    /**
     * Show the admin dashboard with all kajian records.
     */
    public function index()
    {
        $this->requireOperator();

        $kajian = Kajian::orderBy('Tanggal', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();
        $kontakList     = Kontak::orderBy('nama', 'asc')->get();
        $acaraList      = Acara::orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();
        $informasiUmumList = InformasiUmum::orderBy('created_at', 'desc')->get();

        return view('admin_dashboard', compact('kajian', 'narasumberList', 'tempatList', 'kontakList', 'acaraList', 'informasiUmumList'));
    }

    

    public function informasi()
    {
        $this->requireOperator();
        return view('informasi_umum');
    }

    public function acara()
    {
        $this->requireOperator();
        return view('kelola_acara');
    }
}
