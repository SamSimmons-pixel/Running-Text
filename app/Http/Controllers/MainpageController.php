<?php

namespace App\Http\Controllers;

use App\Models\running_text_data;

class MainpageController extends Controller
{
    /**
     * Display the mainpage with jadwal kajian running text.
     */
    public function index()
    {
        // Only fetch records where Tampilkan = true, ordered by Tanggal ascending
        $kajian = running_text_data::where('Tampilkan', true)
            ->orderBy('Tanggal', 'asc')
            ->get();

        return view('mainpage', compact('kajian'));
    }
}
