<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smartguide;

class SmartguideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $smartguides = Smartguide::all();
        return response()->json($smartguides);
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

        $smartguide = new Smartguide();
        $smartguide->judul = $request->judul;
        $smartguide->isi = $request->isi;
        $smartguide->nama = $request->nama;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $smartguide->foto = $filename;
        }
        $smartguide->save();

        return response()->json(['message' => 'Smartguide created successfully', 'data' => $smartguide], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $smartguide = Smartguide::find($id);
        if (!$smartguide) {
            return response()->json(['message' => 'Smartguide not found'], 404);
        }
        return response()->json($smartguide);
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

        $smartguide = Smartguide::find($id);
        if (!$smartguide) {
            return response()->json(['message' => 'Smartguide not found'], 404);
        }

        $smartguide->judul = $request->judul;
        $smartguide->isi = $request->isi;
        $smartguide->nama = $request->nama;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $smartguide->foto = $filename;
        }
        $smartguide->save();

        return response()->json(['message' => 'Smartguide updated successfully', 'data' => $smartguide]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $smartguide = Smartguide::find($id);
        if (!$smartguide) {
            return response()->json(['message' => 'Smartguide not found'], 404);
        }
        $smartguide->delete();
        return response()->json(['message' => 'Smartguide deleted successfully']);
    }
}
