<?php

namespace App\Http\Controllers\CompraVenta\compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use Illuminate\Support\Facades\DB;

class BuscadorCompraController extends Controller
{
    public function nitProveedor(request $request)
    {
        $termino=$request->get('termino');

        $querys = DB::select("SELECT DISTINCT nitProveedor, razonSocialProveedor, combustible,ultimoCodigoAutorizacion FROM compras WHERE nitProveedor LIKE '".$termino."%' ORDER BY nitProveedor DESC");


        /* para que funcione el autocomplete dene haber un campo llamado LABEL */
        $datos=[];
        foreach($querys as $query)
        {
            $datos[]=[
                'label'=>$query->nitProveedor,
                'razonSocialProveedor'=>$query->razonSocialProveedor,
                'combustible'=>$query->combustible,
                'ultimoCodigoAutorizacion'=>$query->ultimoCodigoAutorizacion
            ];
        }
        return $datos;
    }

    public function razonSocialProveedor(request $request)
    {
        $termino=$request->get('termino');

        /*$querys = Compra::where('razonSocialProveedor','LIKE','%' .$termino. '%')->get(); */

        $querys = DB::select("SELECT DISTINCT razonSocialProveedor FROM compras WHERE razonSocialProveedor LIKE '%".$termino."%' ORDER BY razonSocialProveedor ASC");


        /* para que funcione el autocomplete dene haber un campo llamado LABEL */
        $datos=[];
        foreach($querys as $query)
        {
            $datos[]=[
                'label'=>$query->razonSocialProveedor,
            ];
        }
        return $datos;
    }

    public function autorizacionCompra(request $request)
    {
        $termino=$request->get('termino');

        //$querys = Compra::where('codigoAutorizacion','LIKE', $termino. '%')->get();
        $querys = DB::select("SELECT DISTINCT codigoAutorizacion FROM compras WHERE codigoAutorizacion LIKE '".$termino."%' ORDER BY codigoAutorizacion ASC");

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
