<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Makanan;
use App\Models\MakananKriteria;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    /**
     * 📝 Menampilkan semua hasil rekomendasi
     */
    public function index()
    {
        $rekomendasi = Rekomendasi::with(['makanan', 'user'])
            ->orderByDesc('skor')
            ->get();

        return view('rekomendasi.index', compact('rekomendasi'));
    }

    /**
     * 🧮 Proses perhitungan SAW
     */
    public function hitung()
    {
        $makanans = Makanan::all();
        $kriterias = Kriteria::all();

        // Ambil MIN & MAX sekali saja
        $stats = MakananKriteria::selectRaw('kriterias_id, MIN(nilai) as minv, MAX(nilai) as maxv')
            ->groupBy('kriterias_id')
            ->get()
            ->keyBy('kriterias_id');

        foreach ($makanans as $makanan) {

            $skor = 0;

            foreach ($kriterias as $kriteria) {

                $nilai = MakananKriteria::where('makanans_id', $makanan->id)
                    ->where('kriterias_id', $kriteria->id)
                    ->value('nilai') ?? 0;

                $min = $stats[$kriteria->id]->minv;
                $max = $stats[$kriteria->id]->maxv;

                // Normalisasi SAW yang benar
                if ($kriteria->tipe === 'benefit') {
                    $normalisasi = $max > 0 ? $nilai / $max : 0;
                } else {
                    // cost
                    $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                }

                // Gunakan bobot asli
                $skor += $normalisasi * $kriteria->bobot;
            }

            // Simpan hasil
            Rekomendasi::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'makanans_id' => $makanan->id,
                ],
                [
                    'skor' => round($skor, 6)
                ]
            );
        }

        return redirect()->route('rekomendasi.index')->with('success', 'Perhitungan SAW selesai!');
    }
}