<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\SmartguideController;
use App\Models\Artikel;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contohcontroller;
use App\Http\Controllers\SignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/',[SignController::class, 'index']);
Route::get('/register',[RegisterController::class, 'regis']);
Route::post('/save', [RegisterController::class,'save'])->name('simpan_reg');
Route::post('/Signin',[SignController::class, 'in']);
Route::get('/dashboard',[DashboardController::class, 'index']);
Route::get('/Signout',[SignController::class, 'out']);
Route::get('/artikel',[ArtikelController::class, 'artikel'])->name('artikel');
Route::get('/form_artikel', [ArtikelController::class,'create_artikel'])->name('create_ark');
Route::post('/store_artikel', [ArtikelController::class,'store_artikel'])->name('simpan_artikel');
Route::get('/edit_artikel/{id}', [ArtikelController::class,'edit_artikel'])->name('edit_artikel');
Route::put('/update_artikel/{id}', [ArtikelController::class,'update_artikel'])->name('simpaneditan_artikel');
Route::get('/delete_artikel/{id}', [ArtikelController::class,'destroy_artikel'])->name('delartikel');
Route::get('/user',[PenggunaController::class, 'user'])->name('user');
Route::get('/form_user', [PenggunaController::class,'create_user'])->name('create_user');
Route::post('/store_user', [PenggunaController::class,'store_user'])->name('simpan_user');
Route::get('/edit_user/{id}', [PenggunaController::class,'edit_user'])->name('edit_user');
Route::put('/update_user/{id}', [PenggunaController::class,'update_user'])->name('simpaneditan_user');
Route::get('/delete_user/{id}', [PenggunaController::class,'destroy_user'])->name('deluser');
Route::get('/smartguide',[SmartguideController::class, 'smartguide'])->name('smartguide');
Route::get('/form_smartguide', [SmartguideController::class,'create_smartguide'])->name('create_smartguide');
Route::post('/store_smartguide', [SmartguideController::class,'store_smartguide'])->name('simpan_smartguide');
Route::get('/edit_smartguide/{id}', [SmartguideController::class,'edit_smartguide'])->name('edit_smartguide');
Route::put('/update_smartguide/{id}', [SmartguideController::class,'update_smartguide'])->name('simpaneditan_smartguide');
Route::get('/delete_smartguide/{id}', [SmartguideController::class,'destroy_smartguide'])->name('delsmartguide');
Route::get('/show_artikel/{id}', [ArtikelController::class,'show_artikel'])->name('lihat_artikel');
Route::get('/show_smartguide/{id}', [SmartguideController::class,'show_smartguide'])->name('lihat_smartguide');

Route::get('/logout', [SignController::class, 'out'])->name('logout');

Route::post('/import-users', [DashboardController::class, 'importUsers'])->name('import_users');
Route::get('/export-users', [DashboardController::class, 'exportUsers'])->name('export_users');