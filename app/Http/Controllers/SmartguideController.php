<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmartguideController extends Controller
{
    //
    public function show_smartguide(string $id)
    {
        $smartguide = Smartguide::find($id);
        return view('dashboard.show_smartguide', compact('smartguide'));
    }


    public function smartguide() {
        $data_smartguide = Smartguide::all();
        return view("dashboard.smartguide", compact('data_smartguide'));
    }

    public function create_smartguide()
    {
        //
        return view("dashboard.smartguide_create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_smartguide(Request $request)
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
            
            //tambah data pengguna
            Smartguide::create([
                'nama'=>$name,
                'judul'=>$request->judul,
                'isi'=>$request->isi,
                'foto'=>$fileName,
            ]);
            
            return redirect()->route('smartguide');

    }

    public function edit_smartguide(string $id)
    {
        //
        //echo "ini method edit";
        //echo $id;
        $hasil_query = Smartguide::where('id',$id)->first();
        return view('dashboard.smartguide_edit',compact('hasil_query'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_smartguide(Request $request, string $id){

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
            $fotoLama = Smartguide::where('id',$id)->get();
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
            Smartguide::where('id',$id)->update([
                'nama'=>$name,
                'judul'=>$request->judul,
                'isi'=>$request->isi,
                'foto'=>$fileName,
            ]);
                    
            return redirect()->route('smartguide');

        //
    }

    public function destroy_smartguide(string $id)
    {
        //
        Smartguide::where('id',$id)->delete();
        return redirect()->route('smartguide');
    }
}
