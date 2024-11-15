<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Artikel;

use App\Models\Pengguna;

use App\Models\Smartguide;

//Import Export
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use PDF;


class DashboardController extends Controller
{
    //
    public function _construct(Request $request){
        if ($request->session()->get('email'))
            return redirect('/');
    }

    public function index()
    {
    $data_artikel = Artikel::all();
    $data_smartguide = Smartguide::all();
    return view("dashboard.index", compact('data_artikel', 'data_smartguide'));
    }   

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

    public function show_smartguide(string $id)
    {
        $smartguide = Smartguide::find($id);
        return view('dashboard.show_smartguide', compact('smartguide'));
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

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        
        \Log::info('Importing users from file: ' . $request->file('file')->getClientOriginalName());
        
        try {
            Excel::import(new UsersImport, $request->file('file'));
            \Log::info('Import successful');
        } catch (\Exception $e) {
            \Log::error('Import failed: ' . $e->getMessage());
            return redirect()->route('user')->with('error', 'Data import failed.');
        }
        
        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('user')->with('success', 'Data imported successfully.');
    }

    public function exportUsers(Request $request)
    {
        $format = $request->input('format');

        if ($format == 'pdf') {
            $users = Pengguna::select('id', 'nama', 'email', 'no_telp', 'alamat')->get();
            $pdf = PDF::loadView('exports.users', compact('users'));
            return $pdf->download('users.pdf');
        } elseif ($format == 'csv') {
            return Excel::download(new UsersExport, 'users.csv');
        }

        return redirect()->route('user')->with('error', 'Invalid export format selected.');
    }

    
}

