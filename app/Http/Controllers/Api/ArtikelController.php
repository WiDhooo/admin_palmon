<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $artikels = Artikel::all();
        return response()->json($artikels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'nama' => 'required|max:255',
            'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $artikel = new Artikel();
        $artikel->judul = $request->judul;
        $artikel->isi = $request->isi;
        $artikel->nama = $request->nama;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $artikel->foto = $filename;
        }
        $artikel->save();

        return response()->json(['message' => 'Artikel created successfully', 'data' => $artikel], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel not found'], 404);
        }
        return response()->json($artikel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'nama' => 'required|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel not found'], 404);
        }

        $artikel->judul = $request->judul;
        $artikel->isi = $request->isi;
        $artikel->nama = $request->nama;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $artikel->foto = $filename;
        }
        $artikel->save();

        return response()->json(['message' => 'Artikel updated successfully', 'data' => $artikel]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel not found'], 404);
        }
        $artikel->delete();
        return response()->json(['message' => 'Artikel deleted successfully']);
    }
}
