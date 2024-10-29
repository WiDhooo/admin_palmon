<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smartguide extends Model
{
    use HasFactory;
    protected $table = 'smartguide';
    protected $fillable = [
        'judul',
        'isi',
        'nama',
        'foto',
    ];
}
