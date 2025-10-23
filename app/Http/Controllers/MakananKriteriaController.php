<?php

namespace App\Http\Controllers;

use App\Models\MakananKriteria;
use App\Models\Makanan;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class MakananKriteriaController extends Controller
{
    // tampil halaman index dan kirim variabel yang dibutuhkan
    public function index()
    {
        // ambil semua record makanan_kriteria beserta relasi makanan & kriteria
        $makanan_kriteria = MakananKriteria::with(['makanan', 'kriteria'])->orderBy('id')->get();

        // untuk dropdown modal tambah/edit
        $makanan = Makanan::orderBy('nama')->get();
        $kriteria = Kriteria::orderBy('nama')->get();

        return view('makanan_kriteria.index', compact('makanan_kriteria', 'makanan', 'kriteria'));
    }

    // simpan data baru (dipanggil via AJAX POST)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'makanans_id' => 'required|exists:makanan,id',
            'kriterias_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric'
        ]);

        // jika ingin memastikan kombinasi unik, bisa gunakan updateOrCreate
        MakananKriteria::create($validated);

        return response()->json(['success' => true]);
    }

    // ambil 1 record -> dipakai AJAX untuk isi modal edit
    public function show($id)
    {
        $mk = MakananKriteria::findOrFail($id);
        return response()->json($mk);
    }

    // update record (dipanggil via AJAX, method spoofing _method=PUT)
    public function update(Request $request, $id)
    {
        $mk = MakananKriteria::findOrFail($id);

        $validated = $request->validate([
            'makanans_id' => 'required|exists:makanan,id',
            'kriterias_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric'
        ]);

        $mk->update($validated);

        return response()->json(['success' => true]);
    }

    // hapus record (dipanggil via AJAX)
    public function destroy($id)
    {
        $mk = MakananKriteria::findOrFail($id);
        $mk->delete();

        return response()->json(['success' => true]);
    }
}
