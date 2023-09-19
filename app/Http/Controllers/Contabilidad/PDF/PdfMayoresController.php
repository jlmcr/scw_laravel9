<?php

namespace App\Http\Controllers\Contabilidad\PDF;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class PdfMayoresController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function mayorAnalitico_pdf(Request $request){
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

            //! DATO PARA LA BUSQUEDA
            $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $fechaInicio_buscado = $request->get('fechaInicio_buscado');
            $fechaFin_buscado = $request->get('fechaFin_buscado');
            $idSubcuenta_buscada = $request->get('id');

            //! datos de la sub cuenta

            //! podemos usar vistas en el plan de cuentas = cuentas subcuetas, tipos, grupos etc
            $datos_Subcuenta = DB::table('pc_sub_cuenta')
                                ->join('pc_partida_contable','pc_sub_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                                ->select('pc_sub_cuenta.*','pc_partida_contable.codigo','pc_partida_contable.descripcion')
                                ->where('pc_sub_cuenta.id','=',$idSubcuenta_buscada)
                                ->get();

            $registrosEncontrados = DB::table('vista_registros_para_mayores')
                                ->where('estado_comprobante','=',1)
                                ->where('ejercicio_id','=',$idEjercicioActivo)
                                ->where('subcuenta_id','=',$idSubcuenta_buscada)
                                ->whereBetween('fecha',[$fechaInicio_buscado,$fechaFin_buscado])
                                ->get(); //se ordena en la vista
                                //->toSql();

            //? select * from `vista_registros_para_mayores` where `estado_comprobante` = ? and `ejercicio_id` = ? and `subcuenta_id` = ? and `fecha` between ? and ?

            //return $registrosEncontrador;

        //return $cuentasDetalle;

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.mayor_individual_pdf',compact('datosEmpresaActiva','fechaInicio_buscado','fechaFin_buscado','datos_Subcuenta','registrosEncontrados'));
        $pdf->setPaper('A4','landscape'); // portrait vert

        //return $pdf->download();
        return $pdf->stream('mayor_analílitico.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
        //mayor_individual_pdf
    }
}
