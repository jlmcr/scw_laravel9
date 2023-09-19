<?php

namespace App\Http\Controllers\CompraVenta\ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class BuscadorVentaController extends Controller
{
    public function ciNitCliente(request $request)
    {
        $termino=$request->get('termino');

        $querys = DB::select("SELECT DISTINCT ciNitCliente, complemento, razonSocialCliente FROM ventas WHERE ciNitCliente LIKE '".$termino."%' ORDER BY ciNitCliente DESC");


        /* para que funcione el autocomplete dene haber un campo llamado LABEL */
        $datos=[];
        foreach($querys as $query)
        {
            $datos[]=[
                'label'=>$query->ciNitCliente,
                'complemento'=>$query->complemento,
                'razonSocialCliente'=>$query->razonSocialCliente
            ];
        }
        return $datos;
    }

    public function razonSocialCliente(request $request)
    {
        $termino=$request->get('termino');

        /*$querys = Compra::where('razonSocialProveedor','LIKE','%' .$termino. '%')->get(); */

        $querys = DB::select("SELECT DISTINCT razonSocialCliente FROM ventas WHERE razonSocialCliente LIKE '%".$termino."%' ORDER BY razonSocialCliente ASC");


        /* para que funcione el autocomplete dene haber un campo llamado LABEL */
        $datos=[];
        foreach($querys as $query)
        {
            $datos[]=[
                'label'=>$query->razonSocialCliente,
            ];
        }
        return $datos;
    }

    public function autorizacionVenta(request $request)
    {
        $termino=$request->get('termino');

        $querys = DB::select("SELECT DISTINCT codigoAutorizacion FROM ventas WHERE codigoAutorizacion LIKE '".$termino."%' ORDER BY codigoAutorizacion ASC");


        /* para que funcione el autocomplete dene haber un campo llamado LABEL */
        $datos=[];
        foreach($querys as $query)
        {
            $datos[]=[
                'label'=>$query->codigoAutorizacion,
            ];
        }
        return $datos;
    }
}
