<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    //
    public function artikel() {
        $data_artikel = Artikel::all();
        return view("dashboard.artikel", compact('data_artikel'));
    }

    public function create_artikel()
    {
        //
        return view("dashboard.artikel_create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_artikel(Request $request)
    {
        //
         // melakukan validasi data
         $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ],
        [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 500 karakter',
            'isi.required' => 'Isi wajib diisi',
            'foto.required' => 'Foto wajib diisi',
            'foto.max' => 'Foto maksimal 2 MB',
            'foto.mimes' => 'File ekstensi hanya bisa jpg,png,jpeg,gif, svg',
            'foto.image' => 'File harus berbentuk image'
        ]);

        //jika file foto ada yang terupload
        if(!empty($request->foto)){
            //maka proses berikut yang dijalankan
            $fileName = 'foto-'.uniqid().'.'.$request->foto->extension();
            //setelah tau fotonya sudah masuk maka tempatkan ke public
            $request->foto->move(public_path('gambars'), $fileName);
        } else {
            $fileName = 'noimage.jpeg';
        }
        
        $name = session('name');
            
            //tambah data artikel
            Artikel::create([
                'nama'=>$name,
                'judul'=>$request->judul,
                'isi'=>$request->isi,
                'foto'=>$fileName,
            ]);
            
            return redirect()->route('artikel');
    }

    /**
     * Display the specified resource.
     */
    public function show_artikel(string $id)
    {
        $article = Artikel::find($id);
        return view('dashboard.show_artikel', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_artikel(string $id)
    {
        //
        //echo "ini method edit";
        //echo $id;
        $hasil_query = Artikel::where('id',$id)->first();
        return view('dashboard.artikel_edit',compact('hasil_query'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_artikel(Request $request, string $id){

        // validasi data
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ],
        [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 500 karakter',
            'isi.required' => 'Isi wajib diisi',
            'foto.required' => 'Foto wajib diisi',
            'foto.max' => 'Foto maksimal 2 MB',
            'foto.mimes' => 'File ekstensi hanya bisa jpg,png,jpeg,gif, svg',
            'foto.image' => 'File harus berbentuk image'
        ]);
        
        
            //foto lama
            $fotoLama = Artikel::where('id',$id)->get();
            foreach($fotoLama as $f1){
                $fotoLama = $f1->foto;
            }
        
            //jika foto sudah ada yang terupload
            if(!empty($request->foto)){
                //maka proses selanjutnya
                if(!empty($fotoLama->foto)) unlink(public_path('gambars'.$fotoLama->foto));
                //proses ganti foto
                $fileName = 'foto-'.$request->id.'.'.$request->foto->extension();
                //setelah tau fotonya sudah masuk maka tempatkan ke public
                $request->foto->move(public_path('gambars'), $fileName);
            } else{
                $fileName = $fotoLama;
            }

            $name = session('name');
        
            //update data artikel
            Artikel::where('id',$id)->update([
                'nama'=>$name,
                'judul'=>$request->judul,
                'isi'=>$request->isi,
                'foto'=>$fileName,
            ]);
                    
            return redirect()->route('artikel');

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_artikel(string $id)
    {
        //
        Artikel::where('id',$id)->delete();
        return redirect()->route('artikel');
    }
}