<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Peserta extends Model
{
      use HasFactory, HasApiTokens;

    protected $table = 'peserta';
    protected $fillable = [
       'user_name','user_email','no_hp',
       'nik','tgl_lahir','user_password','user_level','kampus','id_skema','status','tempat','date'
    ];
}
