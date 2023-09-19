<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable =[
        'fecha',
        'numeroFactura'	,
        'codigoAutorizacion',	
        'ciNitCliente',	
        'complemento',	
        'razonSocialCliente',	
        'importeTotal',	
        'ice',	
        'iehd',	
        'ipj',	
        'tasas',	
        'otrosNoSujetosaIva',	
        'exportacionesyExentos',
        'tasaCero',	
        'descuentos',	
        'gifCard',	
        'estado',	
        'codigoControl',	
        'tipoVenta',	
        'sucursal_id',	
    ];
}
