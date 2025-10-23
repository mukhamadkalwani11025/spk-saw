<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\Kriteria;
use App\Models\MakananKriteria;
use App\Models\Rekomendasi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMakanan = Makanan::count();
        $totalKriteria = Kriteria::count();
        $totalMakananKriteria = MakananKriteria::count();
        $totalRekomendasi = Rekomendasi::count();
        $totalUser = User::count();

        return view('dashboard.index', compact(
            'totalMakanan',
            'totalKriteria',
            'totalMakananKriteria',
            'totalRekomendasi',
            'totalUser'
        ));
    }
}
