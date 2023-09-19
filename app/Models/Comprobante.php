<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    //! (Relacion uno a muchos INVERSA) Relacion muchos a uno
    //! comprobante N : tipo 1
    //? tipo propaga a comprobante
    public function tipo(){
        return $this->belongsTo(TipoDeComprobante::class,'tipoComprobante_id','id'); 
        //* pasamos el moodelo del lado opuesto de la relacion
    }

}
