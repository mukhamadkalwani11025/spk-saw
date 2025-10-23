<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::orderBy('id')->get();
        return view('kriteria.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric',
            'tipe' => 'required|in:benefit,cost'
        ]);

        Kriteria::create($validated);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $k = Kriteria::findOrFail($id);
        return response()->json($k);
    }

    public function update(Request $request, $id)
    {
        $k = Kriteria::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric',
            'tipe' => 'required|in:benefit,cost'
        ]);

        $k->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $k = Kriteria::findOrFail($id);
        $k->delete();
        return response()->json(['success' => true]);
    }
}
