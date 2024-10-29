<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class SignController extends Controller
{
    //
    public function index() {
        return view("Sign.index");
    }

    public function in(Request $request) {
        // Cari pengguna berdasarkan email
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Jika password valid, set session dan redirect ke dashboard
            session([
                'email' => $user->email,
                'name' => $user->name
            ]);
            return redirect('/dashboard');
        } else {
            // Jika login gagal, kembali ke halaman login dengan pesan error
            return back()->with('error', 'Email atau password salah.')->withInput();
        }
    }

    public function out(Request $request) {
        $request->session()->flush();
        return redirect('/');
    }
}