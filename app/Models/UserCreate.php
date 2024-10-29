<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCreate extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable =[
        'email', 'username','password'
    ];
}
