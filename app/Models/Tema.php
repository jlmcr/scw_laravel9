<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    //!Relacion uno a muchos tema 1: N:usuarios
    //!tema propaga a usuarios
    public function usuarios(){
        return $this->hasMany(User::class,'tema_id','id');
        //hasMany -> un tema tiene muchos usuarios
    }
}
