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

