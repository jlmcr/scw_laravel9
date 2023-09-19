<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

/*     //! Relacion muchos a uno  (Relacion uno a muchos INVERSA) sucursal N : empresa 1
    //!empresa propaga a sucursal
    public function empresa(){
        return $this->belongsTo('app\Models\Empresa'); //* lo pasamos al modelo del lado opuesto de la relacion
    } */
}
