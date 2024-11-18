<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use GuzzleHttp\Client;

class ArtikelController extends Controller
{
    //
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8001/api/']);
    }
    public function artikel() {
        $response = $this->client->get('artikel');
        $data_artikel = json_decode($response->getBody()->getContents(), true);

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
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'nama_pembuat' => 'required|max:255',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
                'name' => 'gambar',
                'contents' => fopen($request->file('gambar')->getPathname(), 'r'),
                'filename' => $request->file('gambar')->getClientOriginalName()
            ]
        ];

        $response = $this->client->post('artikel', [
            'multipart' => $multipart
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return redirect()->route('artikel')->with('success', 'Artikel created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show_artikel(string $id)
    {
        $response = $this->client->get("artikel/{$id}");
        $article = json_decode($response->getBody()->getContents(), true);

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
        $response = $this->client->get("artikel/{$id}");
        $hasil_query = json_decode($response->getBody()->getContents(), true);

        return view('dashboard.artikel_edit', compact('hasil_query'));
 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_artikel(Request $request, string $id){

        // validasi data
        $request->validate([
            'judul' => 'required|max:500',
            'isi' => 'required',
            'nama_pembuat' => 'required|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
                'name' => 'nama',
                'contents' => $request->nama_pembuat
            ]
        ];

        if ($request->hasFile('foto')) {
            $multipart[] = [
                'name' => 'foto',
                'contents' => fopen($request->file('foto')->getPathname(), 'r'),
                'filename' => $request->file('foto')->getClientOriginalName()
            ];
        }

        $response = $this->client->put("artikel/{$id}", [
            'multipart' => $multipart
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return redirect()->route('artikel')->with('success', 'Artikel updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_artikel(string $id)
    {
        //
        $response = $this->client->delete("artikel/{$id}");

        if ($response->getStatusCode() == 200) {
            return redirect()->route('artikel')->with('success', 'Artikel deleted successfully');
        } else {
            return redirect()->route('artikel')->with('error', 'Failed to delete artikel');
        }
    }
}
