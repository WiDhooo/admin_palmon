<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pengguna = Pengguna::all();
        return response()->json($pengguna);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email|unique:users,email', // Sesuaikan nama tabel jika perlu
            'no_telp' => 'required|max:15', // Tambahkan validasi jika kolom ini ada
            'alamat' => 'required|max:255', // Tambahkan validasi jika kolom ini ada
        ]);

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp, // Tambahkan jika kolom ini ada
            'alamat' => $request->alamat,   // Tambahkan jika kolom ini ada
        ]);

        return response()->json(['message' => 'Pengguna created successfully', 'data' => $pengguna], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna not found'], 404);
        }
        return response()->json($pengguna);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        //
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email|unique:user,email,'. $pengguna->id,// Sesuaikan nama tabel jika perlu
            'no_telp' => 'required|max:15', // Tambahkan validasi jika kolom ini ada
            'alamat' => 'required|max:255', // Tambahkan validasi jika kolom ini ada
        ]);

        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna not found'], 404);
        }

        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->no_telp = $request->no_telp; // Tambahkan jika kolom ini ada
        $pengguna->alamat = $request->alamat;   // Tambahkan jika kolom ini ada
        $pengguna->save();

        //update data artikel
        Pengguna::where('id',$id)->update([
            'nama'=>$request->nama,
            'email'=>$request->email,
            'no_telp'=>$request->no_telp,
            'alamat'=>$request->alamat,
        ]);

        return response()->json(['message' => 'Pengguna updated successfully', 'data' => $pengguna]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna not found'], 404);
        }
        $pengguna->delete();
        return response()->json(['message' => 'Pengguna deleted successfully']);
 
    }
}
