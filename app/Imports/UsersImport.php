<?php

namespace App\Imports;

use App\Models\Pengguna;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pengguna([
            'id' => $row[0],
            'nama' => $row[1],
            'email' => $row[2],
            'no_telp' => $row[3],
            'alamat' => $row[4],
        ]);
    }
}
