<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeComprobante extends Model
{
    use HasFactory;

    protected $table = "tipos_de_comprobante";

    public $timestamps = false;//sin fecha de creacion y actualizacion

    //!Relacion uno a muchos tipo 1: activofijo comprobante
    //!comprobante propaga a tipo
    public function comprobantes(){
        return $this->hasMany(Comprobante::class,'tipoComprobante_id','id');
        //hasMany -> un tipo tiene muchos comprobante
    }
}
