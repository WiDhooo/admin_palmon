<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Smartguide;
use GuzzleHttp\Client;

class SmartguideController extends Controller
{
    //
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8001/api/']);
    }
    public function show_smartguide(string $id)
    {
        $response = $this->client->get("guides/{$id}");
        $smartguide = json_decode($response->getBody()->getContents(), true);

        return view('dashboard.show_smartguide', compact('smartguide'));

    }


    public function smartguide() {
        $response = $this->client->get('guides');
        $data_smartguide = json_decode($response->getBody()->getContents(), true);

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
            'tag' => 'required|max:255',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            // Jika file gambar ada yang terupload
            if (!empty($request->gambar)) {
                // Maka proses berikut yang dijalankan
                $fileName = 'foto-' . uniqid() . '.' . $request->gambar->extension();
                // Setelah tahu fotonya sudah masuk maka tempatkan ke public
                $request->gambar->move(public_path('gambars'), $fileName);
            } else {
                $fileName = 'noimage.jpeg';
            }
            
            // Mengambil nama pembuat dari session
            $nama_pembuat = session('name');
            
            //tambah data pengguna
            $multipart = [
                [
                    'name' => 'judul',
                    'contents' => $request->judul
                ],
                [
                    'name' => 'isi',
                    'contents' => $request->isi
                ],
                [
                    'name' => 'nama_pembuat',
                    'contents' => $nama_pembuat
                ],
                [
                    'name' => 'tag',
                    'contents' => $request->tag
                ],
                [
                    'name' => 'gambar',
                    'contents' => fopen(public_path('gambars') . '/' . $fileName, 'r'),
                    'filename' => $fileName
                ]
            ];
            try {
                $response = $this->client->post('guides', [
                    'multipart' => $multipart
                ]);
    
                $data = json_decode($response->getBody()->getContents(), true);
    
                return redirect()->route('smartguide')->with('success', 'Smartguide created successfully');
            } catch (\Exception $e) {
                \Log::error('Error creating smartguide: ' . $e->getMessage());
                return redirect()->route('create_smartguide')->with('error', 'Failed to create smartguide');
            }
    }

    public function edit_smartguide(string $id)
    {
        //
        //echo "ini method edit";
        //echo $id;
        $response = $this->client->get("guides/{$id}");
        $hasil_query = json_decode($response->getBody()->getContents(), true);

        return view('dashboard.smartguide_edit', compact('hasil_query'));
 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_smartguide(Request $request, string $id){

        // validasi data
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'nama_pembuat' => 'required|max:255',
            'tag' => 'required|max:255',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $multipart = [
            [
                'name' => 'judul',
                'contents' => $request->judul
            ],
            [
                'name' => 'isi',
                'contents' => $request->isi
            ],
            [
                'name' => 'nama_pembuat',
                'contents' => $request->nama_pembuat
            ],
            [
                'name' => 'tag',
                'contents' => $request->tag
            ]
        ];

        if ($request->hasFile('gambar')) {
            $multipart[] = [
                'name' => 'gambar',
                'contents' => fopen($request->file('gambar')->getPathname(), 'r'),
                'filename' => $request->file('gambar')->getClientOriginalName()
            ];
        }

        try {
            $response = $this->client->put("guides/{$id}", [
                'multipart' => $multipart
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return redirect()->route('smartguide')->with('success', 'Smartguide updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating smartguide: ' . $e->getMessage());
            return redirect()->route('edit_smartguide', $id)->with('error', 'Failed to update smartguide');
        }

        //
    }

    public function destroy_smartguide(string $id)
    {
        //
        try {
            $response = $this->client->delete("guides/{$id}");

            if ($response->getStatusCode() == 200) {
                return redirect()->route('smartguide')->with('success', 'Smartguide deleted successfully');
            } else {
                return redirect()->route('smartguide')->with('error', 'Failed to delete smartguide');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting smartguide: ' . $e->getMessage());
            return redirect()->route('smartguide')->with('error', 'Failed to delete smartguide');
        }
    }
}
