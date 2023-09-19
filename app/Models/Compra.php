<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable= [
        'nitProveedor',
        'razonSocialProveedor',
        'codigoAutorizacion',
        'numeroFactura',
        'dim',
        'fecha',
        'importeTotal',
        'ice',
        'iehd',
        'ipj',
        'tasas',
        'otrosNoSujetosaCF',
        'exentos',
        'tasaCero',
        'descuentos',
        'gifCard',
        'tipoCompra',
        'codigoControl',
        'combustible',
        'ultimoCodigoAutorizacion',
        'sucursal_id',
];
}
