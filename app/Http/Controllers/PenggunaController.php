<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pengguna;

class PenggunaController extends Controller
{
    //
    public function user() {
        $data_user = Pengguna::all();
        return view("dashboard.user", compact('data_user'));
    }
    public function create_user()
    {
        //
        return view("dashboard.user_create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_user(Request $request)
    {
        //
         // melakukan validasi data
            $request->validate([
                'nama' => 'required|max:50',
                'email' => 'required|email|unique:user,email',
                'no_telp' => 'required|max:15',
                'alamat' => 'required|max:500',
            ],
            [
                'nama.required' => 'Nama wajib diisi',
                'nama.max' => 'Nama maksimal 50 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'no_telp.required' => 'Nomor Telepon wajib diisi',
                'no_telp.max' => 'Nomor Telepon maksimal 15 karakter',
                'alamat.required' => 'Alamat wajib diisi',
                'alamat.max' => 'Alamat maksimal 500 karakter',
            ]);
            
            
            //tambah data pengguna
            Pengguna::create([
                'nama'=>$request->nama,
                'email'=>$request->email,
                'no_telp'=>$request->no_telp,
                'alamat'=>$request->alamat,
            ]);
            
            return redirect()->route('user');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit_user(string $id)
    {
        //
        //echo "ini method edit";
        //echo $id;
        $hasil_query = Pengguna::where('id',$id)->first();
        return view('dashboard.user_edit',compact('hasil_query'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_user(Request $request, string $id){
        $pengguna = Pengguna::findOrFail($id);

        // validasi data
            $request->validate([
                'nama' => 'required|max:50',
                'email' => 'required|email|unique:user,email,'. $pengguna->id,
                'no_telp' => 'required|max:15',
                'alamat' => 'required|max:500',
        
            ],
            [
                'nama.required' => 'Nama wajib diisi',
                'nama.max' => 'Nama maksimal 50 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'no_telp.required' => 'Nomor Telepon wajib diisi',
                'no_telp.max' => 'Nomor Telepon maksimal 15 karakter',
                'alamat.required' => 'Alamat wajib diisi',
                'alamat.max' => 'Alamat maksimal 500 karakter',
            ]);
        
        
        
            //update data artikel
            Pengguna::where('id',$id)->update([
                'nama'=>$request->nama,
                'email'=>$request->email,
                'no_telp'=>$request->no_telp,
                'alamat'=>$request->alamat,
            ]);
                    
            return redirect()->route('user');

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_user(string $id)
    {
        //
        Pengguna::where('id',$id)->delete();
        return redirect()->route('user');
    }
}
