<?php

namespace App\Http\Controllers\PlanDeCuentas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;

class ReportesPlanDeCuentasController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function planDeCuentas_Pdf(Request $request)
    {
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?


        $tipos = DB::select('SELECT * FROM pc_tipo WHERE estado = 1 ORDER BY id ASC');

        $grupos = DB::select('SELECT pc_grupo.id, pc_partida_contable.*
        FROM pc_grupo
        INNER JOIN pc_partida_contable ON pc_grupo.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        $sub_grupos = DB::select('SELECT pc_sub_grupo.id, pc_sub_grupo.grupo_id, pc_partida_contable.*
        FROM pc_sub_grupo
        INNER JOIN pc_partida_contable ON pc_sub_grupo.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        $cuentas = DB::select('SELECT pc_cuenta.id, pc_cuenta.subGrupo_id, pc_partida_contable.*
        FROM pc_cuenta
        INNER JOIN pc_partida_contable ON pc_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        /* return view('modulos.contabilidad.plan_de_Cuentas.index')
                ->with('tipos',$tipos)
                ->with('grupos',$grupos)
                ->with('sub_grupos',$sub_grupos)
                ->with('cuentas',$cuentas)
                ->with('sub_cuentas',$sub_cuentas); */


        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.plan_de_cuentas_pdf',compact('tipos','grupos','sub_grupos','cuentas','sub_cuentas'));
        $pdf->setPaper('A4','portrait'); //landscape horizontal

        //return $pdf->download();
        return $pdf->stream('Plan_de_cuentas.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }
}
