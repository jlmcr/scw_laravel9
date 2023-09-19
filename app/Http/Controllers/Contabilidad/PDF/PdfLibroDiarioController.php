<?php

namespace App\Http\Controllers\Contabilidad\PDF;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\Ejercicio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibroDiarioExport;



class PdfLibroDiarioController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function libroDiario_pdf(Request $request)
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

        //return $cuentasDetalle;

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.libro_diario_pdf',compact('datosEmpresaActiva','fechaInicio_buscado','fechaFin_buscado','comprobantesEncontrados','detalleComprobante','cuentasDetalle'));
        $pdf->setPaper('A4','portrait'); //landscape horizontal

        //return $pdf->download();
        return $pdf->stream('libro_diario.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }

    public function libroDiario_excel(Request $request)
    {
        try {
                // https://aprendible.com/series/laravel-excel/lecciones/como-exportar-vistas-a-excel
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

                //return $cuentasDetalle;
                //! datosEmpresaActiva','fechaInicio_buscado','fechaFin_buscado','comprobantesEncontrados','detalleComprobante','cuentasDetalle'

                //descarga la configuracion que tengo en esa clase o modelo
                return Excel::download(new LibroDiarioExport($datosEmpresaActiva, $fechaInicio_buscado, $fechaFin_buscado, $comprobantesEncontrados, $detalleComprobante, $cuentasDetalle), 'libro_diario.xlsx');

        } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            return redirect(route('libro-diario'));
        }
    }
}
