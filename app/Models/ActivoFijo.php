<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    use HasFactory;

    // https://laravel.com/docs/9.x/eloquent#eloquent-model-conventions
    //protected $primaryKey = 'otro_id'; //cuando el id es tiene otro nombre de "id"

    protected $keyType = 'string';//debemoos definirla siempre que mi id no es un entero
    public $incrementing = false;

    protected $table ="activos_fijos";

    //************************************************ */

    // rubro 1 : N activofijo
    //* Relacion muchos a uno  (Relacion uno a muchos INVERSA)
    //* rubro propaga a activofijo
    //* belongsTo -> varios activos pertenecen a un rubro
    public function activosFijos_rubros(){
        return $this->belongsTo(Rubro::class, 'rubro_id','id');
    }

}
