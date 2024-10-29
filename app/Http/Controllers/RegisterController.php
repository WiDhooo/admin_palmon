<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function regis() {
        return view("register.index");
    }

    public function save(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|max:45',
            'password' => [
                'required',
                'string',
                'min:8', // minimal 8 karakter
                'regex:/[a-z]/', // harus ada huruf kecil
                'regex:/[A-Z]/', // harus ada huruf besar
                'regex:/[0-9]/', // harus ada angka
                'regex:/[@$!%*?&]/', // harus ada simbol
            ],
            'confirm_password' => 'required|same:password',
        ],
        [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'name.required' => 'Username wajib diisi',
            'name.max' => 'Username maksimal 45 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password harus minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
            'confirm_password.required' => 'Konfirmasi password wajib diisi',
            'confirm_password.same' => 'Konfirmasi password harus sama dengan password',
        ]);
        $validatedData = $request->except('confirm_password');

        User::create([
            'email_verified_at' => now(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
        ]);

        return redirect('/');
    }
}

