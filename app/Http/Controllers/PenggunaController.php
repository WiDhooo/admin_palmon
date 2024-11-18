<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use GuzzleHttp\Client;

class PenggunaController extends Controller
{
    //
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8001/api/']);
    }
    public function user() {
        $response = $this->client->get('user_umum');
        $pengguna = json_decode($response->getBody()->getContents(), true);

        return view('dashboard.user', compact('pengguna'));
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
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'required|max:15',
            'alamat' => 'required|max:500',
        ]);

        $response = $this->client->post('user_umum', [
            'json' => $request->all()
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return redirect()->route('user')->with('success', 'Pengguna created successfully');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit_user(string $id)
    {
        //
        //echo "ini method edit";
        //echo $id;
        $response = $this->client->get("user_umum/{$id}");
        $hasil_query = json_decode($response->getBody()->getContents(), true);

        return view('dashboard.user_edit', compact('hasil_query'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_user(Request $request, string $id){
        // validasi data
        $request->validate([
            'nama' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'required|max:15',
            'alamat' => 'required|max:500',
        ]);

        $response = $this->client->put("user_umum/{$id}", [
            'json' => $request->all()
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return redirect()->route('user')->with('success', 'Pengguna updated successfully');
 

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_user(string $id)
    {
        //
        $response = $this->client->delete("user_umum/{$id}");

        if ($response->getStatusCode() == 200) {
            return redirect()->route('user')->with('success', 'Pengguna deleted successfully');
        } else {
            return redirect()->route('user')->with('error', 'Failed to delete pengguna');
        }
    }
}
