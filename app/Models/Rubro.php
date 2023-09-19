<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    //!Relacion uno a muchos rubro 1: activofijo N
    //!rubro propaga a activofijo

    public function rubros_activosFijos(){
        return $this->hasMany(ActivoFijo::class,'rubro_id','id');
        //hasMany -> un rubro tiene muchos activos
    }
}
