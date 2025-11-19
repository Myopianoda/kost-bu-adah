<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Fungsi ini akan menampilkan halaman "Hub Laporan"
    public function index()
    {
        return view('laporan.index');
    }
}
