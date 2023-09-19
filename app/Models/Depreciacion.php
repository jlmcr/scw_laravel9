<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciacion extends Model
{
    use HasFactory;

    protected $table ="depreciaciones";

/*     // activo fijo 1 : N depreciacion
    //* (Relacion uno a muchos INVERSA)
    //* belongsTo -> um activo tien a un rubro
    public function depreciacion_activoFijo(){
        return $this->belongsTo(ActivoFijo::class,'activoFijo_id','id');
        //*clase - claveforanea - clave propietario
    } */
}
