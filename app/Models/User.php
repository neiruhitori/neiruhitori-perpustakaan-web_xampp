<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table ='users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'perpustakaan_id',
        'nip',
        'name',
        'email',
        'password',
        'photoProfile'
    ];
    protected $guarded = ['id'];
}
