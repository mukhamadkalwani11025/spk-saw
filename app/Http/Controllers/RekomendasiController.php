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

        $totalBobot = $kriterias->sum('bobot');
        $bobotNormalisasi = [];

        foreach ($kriterias as $kriteria) {
            $bobotNormalisasi[$kriteria->id] = $kriteria->bobot / $totalBobot;
        }

        foreach ($makanans as $makanan) {
            $skor = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = MakananKriteria::where('makanans_id', $makanan->id)
                    ->where('kriterias_id', $kriteria->id)
                    ->value('nilai') ?? 0;

                $max = MakananKriteria::where('kriterias_id', $kriteria->id)->max('nilai');
                $min = MakananKriteria::where('kriterias_id', $kriteria->id)->min('nilai');

                if ($kriteria->tipe === 'benefit') {
                    $normalisasi = $max > 0 ? $nilai / $max : 0;
                } else {
                    $normalisasi = $nilai > 0 ? $min / $nilai : 0;
                }

                $skor += $normalisasi * $bobotNormalisasi[$kriteria->id];
            }

            Rekomendasi::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'makanans_id' => $makanan->id,
                ],
                [
                    'skor' => round($skor, 4),
                ]
            );
        }

        return redirect()->route('rekomendasi.index')->with('success', 'Perhitungan rekomendasi selesai!');
    }

    /**
     * ❌ Menghapus data rekomendasi
     */
    public function destroy($id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);
        $rekomendasi->delete();

        return redirect()->route('rekomendasi.index')->with('success', 'Data rekomendasi berhasil dihapus.');
    }
}
