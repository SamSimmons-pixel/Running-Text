<?php

namespace App\Http\Controllers;

use App\Models\Kajian;

class MainpageController extends Controller
{
    /**
     * Display the mainpage with jadwal kajian running text.
     */
    public function index()
    {
        // Only fetch records where Tampilkan = true, ordered by Tanggal ascending
        $kajian = Kajian::where('Tampilkan', true)
            ->orderBy('Tanggal', 'asc')
            ->get();

        return view('mainpage', compact('kajian'));
    }
}
