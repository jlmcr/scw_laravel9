<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;

/*     //! Relacion muchos a uno  (Relacion uno a muchos INVERSA) ejercicio N : empresa 1
    //!empresa propaga a ejercicio
    public function empresa(){
        return $this->belongsTo('app\Models\Empresa'); //* pasamos el moodelo del lado opuesto de la relacion
    } */
}
