<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

/*     //! relacion uno a muchos con sucursal - empresa 1: suscursal N
    public function sucursalesEmpresa(){ // sucursales-> es su nombre por que recuperara varias sucursales
        return $this->hasMany('App\Models\Sucursal');
        //return $this->hasMany(Sucursal::class,'id');
        // * return $this-> hace referencia a la clase donde nos encontramos
        // * una empresa tiene muchas sucursales
        //* 'id'  es la clave principal
    }

    //! relacion uno a muchos con Ejercicio - empresa 1: ejercicio N
    public function ejercicios (){ // sucursales-> es su nombre por que recuperara varias sucursales
        return $this->hasMany('App\Models\Ejercicio');
    } */
}
