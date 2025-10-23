<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;

class MakananController extends Controller
{
    public function index()
    {
        $makanan = Makanan::all();
        return view('makanan.index', compact('makanan'));
    }

    public function show($id)
    {
        $makanan = Makanan::findOrFail($id);
        return response()->json($makanan);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('makanan', 'public');
        }

        Makanan::create($data);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $makanan = Makanan::findOrFail($id);
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('makanan', 'public');
        }

        $makanan->update($data);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $makanan = Makanan::findOrFail($id);
        $makanan->delete();
        return response()->json(['success' => true]);
    }
}
