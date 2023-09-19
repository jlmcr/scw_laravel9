<?php

namespace App\Http\Controllers\Contabilidad\PDF;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\Ejercicio;
use App\Models\Empresa;
use App\Models\TipoDeComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
//use Luecano\NumeroALetras\NumeroALetras;
//require 'vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;

class PdfComprobanteController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function comprobanteIndividual_pdf(Request $request)
    {
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

        $id = $request->get('id');
        //! DATOS GENERALES  DEL COMPROBANTE
        $datosGeneralesComprobante = Comprobante::find($id);
        //! DATOS DE LA EMPRESA - esto aplica solo a los comprobante individuales, por que no mandamos el id de la empresa por la url
            $datosEjercicio = Ejercicio::find($datosGeneralesComprobante->ejercicio_id);
            $datosEmpresa = Empresa::find($datosEjercicio->empresa_id);


        //! DATOS DEL DETALLE DEL COMPROBANTE
        // select `detalle_comprobante`.*, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion` from `detalle_comprobante` inner join `pc_sub_cuenta` on `detalle_comprobante`.`subcuenta_id` = `pc_sub_cuenta`.`id` inner join `pc_partida_contable` on `pc_sub_cuenta`.`codigo_partida` = `pc_partida_contable`.`codigo` where `detalle_comprobante`.`comprobante_id` = 1;
        $detalleComprobante = DB::table('detalle_comprobante')
                    ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                    ->join('pc_partida_contable','pc_sub_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                    ->select('detalle_comprobante.*','pc_partida_contable.codigo','pc_partida_contable.descripcion','pc_sub_cuenta.cuenta_id')//!modifica
                    ->where('detalle_comprobante.comprobante_id','=',$id)
                    ->orderBy('detalle_comprobante.orden','ASC')
                    ->get();

        $cuentasDetalle = DB::table('detalle_comprobante')
                    ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                    ->join('pc_cuenta','pc_sub_cuenta.cuenta_id','=','pc_cuenta.id') //!aumenta
                    ->join('pc_partida_contable','pc_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                    ->select('pc_cuenta.id','pc_partida_contable.codigo','pc_partida_contable.descripcion')//!modifica
                    ->where('detalle_comprobante.comprobante_id','=',$id)
                    ->orderBy('detalle_comprobante.orden','ASC')
                    ->get();
        //return  $cuentasDetalle;

        //! PARA LA PARTE DE LAS CUENTAS
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');
        // return $subcuentas_y_cuentas;


        //! letras de totales
        //$literal_total = new NumeroALetras();
        //return $literal_total->toWords(1100);
        try {
            $totales_de_Comprobantes = DB::select("SELECT comprobantes.id, SUM(detalle_comprobante.debe) AS total_literal
            FROM comprobantes
            INNER JOIN detalle_comprobante ON comprobantes.id = detalle_comprobante.comprobante_id
            WHERE comprobantes.id = ?
            GROUP BY comprobantes.id", [$id]);
            // return $totales_de_Comprobantes[0]->total_literal;

            //! ************************
            $formatter = new NumeroALetras();
            //echo $formatter->toMoney(2500.90, 2, 'DÓLARES', 'CENTAVOS');
            $total_literal = $formatter->toMoney($totales_de_Comprobantes[0]->total_literal, 2, 'BOLIVIANOS', 'CENTAVOS');

            //return $total_literal;
        } catch (\Throwable $th) {
            $total_literal ="";
        }

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.comprobante_individual_pdf',compact('datosGeneralesComprobante','detalleComprobante','cuentasDetalle','datosEjercicio','datosEmpresa','total_literal'));
        $pdf->setPaper('A4','portrait'); //landscape horizontal

        //return $pdf->download();
        return $pdf->stream('comprobante.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }

    public function comprobantesVarios_pdf(Request $request)
    {
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

            //! BUSQUEDA DE TODO PREDETERMINADA DE TODO EL EJERCICIO
            $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);

            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $fechaInicio_buscado = $request->get('fechaInicio_buscado');
            $fechaFin_buscado = $request->get('fechaFin_buscado');

            // no usar Comprobante::all()->where
            //?SQL  select * from `comprobantes` where `fecha` between '2022-01-01' and '2022-12-31' and `ejercicio_id` = 3
            $comprobantesEncontrados = Comprobante::where('ejercicio_id','=',$idEjercicioActivo)
                        ->where('estado','=',1)
                        ->whereBetween('fecha',[$fechaInicio_buscado,$fechaFin_buscado])
                        ->orderBy('fecha')
                        ->orderBy('tipoComprobante_id')
                        ->get();

            $detalleComprobante = DB::table('detalle_comprobante')
                        ->join('comprobantes','detalle_comprobante.comprobante_id','=','comprobantes.id')//!aumentado
                        ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                        ->join('pc_partida_contable','pc_sub_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                        ->select('comprobantes.fecha','detalle_comprobante.*','pc_partida_contable.codigo','pc_partida_contable.descripcion','pc_sub_cuenta.cuenta_id')//!modifica
                        ->where('comprobantes.ejercicio_id','=',$idEjercicioActivo)//!aumentado
                        ->whereBetween('comprobantes.fecha',[$fechaInicio_buscado,$fechaFin_buscado])//!modificado
                        ->orderBy('detalle_comprobante.orden','ASC')
                        ->get();
            //select `comprobantes`.`fecha`, `detalle_comprobante`.*, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion`, `pc_sub_cuenta`.`cuenta_id` from `detalle_comprobante` inner join `comprobantes` on `detalle_comprobante`.`comprobante_id` = `comprobantes`.`id` inner join `pc_sub_cuenta` on `detalle_comprobante`.`subcuenta_id` = `pc_sub_cuenta`.`id` inner join `pc_partida_contable` on `pc_sub_cuenta`.`codigo_partida` = `pc_partida_contable`.`codigo` where `comprobantes`.`ejercicio_id` = ? and `comprobantes`.`fecha` between ? and ? order by `detalle_comprobante`.`orden` asc


                        //agrupamos para traer unicos
            $cuentasDetalle = DB::table('detalle_comprobante')
                        ->join('comprobantes','detalle_comprobante.comprobante_id','=','comprobantes.id')//!aumentado
                        ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                        ->join('pc_cuenta','pc_sub_cuenta.cuenta_id','=','pc_cuenta.id') //!aumenta
                        ->join('pc_partida_contable','pc_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                        ->select('pc_cuenta.id','pc_partida_contable.codigo','pc_partida_contable.descripcion')//!modifica
                        ->where('comprobantes.ejercicio_id','=',$idEjercicioActivo)//!aumentado
                        ->whereBetween('comprobantes.fecha',[$fechaInicio_buscado,$fechaFin_buscado])//!modificado
                        ->groupBy('pc_cuenta.id') //!aumentado
                        ->groupBy('pc_partida_contable.codigo')
                        ->groupBy('pc_partida_contable.descripcion')
                        ->orderBy('detalle_comprobante.orden','ASC')
                        ->get();

            //select `pc_cuenta`.`id`, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion` from `detalle_comprobante` inner join `comprobantes` on `detalle_comprobante`.`comprobante_id` = `comprobantes`.`id` inner join `pc_sub_cuenta` on `detalle_comprobante`.`subcuenta_id` = `pc_sub_cuenta`.`id` inner join `pc_cuenta` on `pc_sub_cuenta`.`cuenta_id` = `pc_cuenta`.`id` inner join `pc_partida_contable` on `pc_cuenta`.`codigo_partida` = `pc_partida_contable`.`codigo` where `comprobantes`.`ejercicio_id` = ? and `comprobantes`.`fecha` between ? and ? group by `pc_cuenta`.`id`, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion` order by `detalle_comprobante`.`orden` asc

        //return $cuentasDetalle;



        //! letras de totales
        //$literal_total = new NumeroALetras();
        //return $literal_total->toWords(1100);

        $totales_de_Comprobantes = DB::select("SELECT comprobantes.id, SUM(detalle_comprobante.debe) AS total_literal
        FROM comprobantes
        INNER JOIN detalle_comprobante ON comprobantes.id = detalle_comprobante.comprobante_id
        WHERE comprobantes.estado = 1 AND comprobantes.ejercicio_id = ?
        GROUP BY comprobantes.id", [$idEjercicioActivo]);
        // return $totales_de_Comprobantes;

        try {
            $literal_por_comprobante = array();

            foreach ($totales_de_Comprobantes as $total) {
                //return $total;
                $formatter = new NumeroALetras();

                $numero = round($total->total_literal,2) ;
                $literal = $formatter->toMoney($numero, 2, 'BOLIVIANOS', 'CENTAVOS');

                $literal_por_comprobante[$total->id]=[
                    'literal'=>$literal
                ];
            }
        } catch (\Throwable $th) {
            $literal_por_comprobante = array();
        }

        //$literal_por_comprobante = json_encode($literal_por_comprobante);
        //return $literal_por_comprobante[64];
        //   foreach ($literal_por_comprobante as $key => $value) {
        //       return $value;
        //   }

        //return explode($literal_por_comprobante,",");

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.comprobantes_varios_pdf',compact('datosEmpresaActiva','fechaInicio_buscado','fechaFin_buscado','comprobantesEncontrados','detalleComprobante','cuentasDetalle','literal_por_comprobante'));
        $pdf->setPaper('A4','portrait'); //landscape horizontal

        //return $pdf->download();
        return $pdf->stream('comprobantes.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }
}
