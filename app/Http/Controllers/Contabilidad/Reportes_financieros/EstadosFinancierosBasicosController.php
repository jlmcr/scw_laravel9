<?php

namespace App\Http\Controllers\Contabilidad\Reportes_financieros;

use App\Http\Controllers\Controller;
use App\Models\Ejercicio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BalanceGeneralExport;
use App\Exports\EstadoResultadoExport;

class EstadosFinancierosBasicosController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function eeff_basicos(Request $request){

        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');
        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();


        if($request->get('process') == 'search')
        {
            //! BUSQUEDA PERSONALIZADA
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            //? *********** 1 estado de resultados **************
            $fechaInicio_buscado_er = $request->get('fechaInicio_er');
            $fechaFin_buscado_er = $request->get('fechaFin_er');
            //? *********** 2 balance general **************
            $fechaInicio_buscado_bg = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado_bg = $request->get('fechaFin_bg');

        }
        else{
            //! BUSQUEDA PREDETERMINADA DE TODO EL EJERCICIO
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            //? *********** 1 estado de resultados **************
            $fechaInicio_buscado_er = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado_er = $datosEjercicioActivo->fechaCierre;
            //? *********** 2 balance general **************
            $fechaInicio_buscado_bg = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado_bg = $datosEjercicioActivo->fechaCierre;

        }

        //? *************************************************
        //? *********** 1 estado de resultados **************
        //? *************************************************

        //! ****************************/
        $acumulado_tipos_er = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_grupos_er = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_subgrupos_er = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_cuentas_er = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_subcuentas_er = DB::select(
            "SELECT
                detalle_comprobante.subcuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                detalle_comprobante.subcuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);


        //return $acumulado_cuentas_er;

        //? *************************************************
        //? *********** 2 balance general **************
        //? *************************************************

        //! ****************************/
        $acumulado_tipos_bg = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_grupos_bg = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_subgrupos_bg = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_cuentas_bg = DB::select(
            "SELECT
            vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

        //! ****************************/
        $acumulado_subcuentas_bg = DB::select(
            "SELECT
                detalle_comprobante.subcuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                detalle_comprobante
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY
                detalle_comprobante.subcuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
        ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);


        //? *************************************************
        //? *********** 3 datos utilizados por ambos *****
        //? *************************************************
        //! todos los tipos
        $tipos_todos = DB::table('pc_tipo')
                ->where('estado','=',1)
                ->orderBy('id','ASC')
                ->get();
        //! todos los grupos
        $grupos_todos = DB::select(
            "SELECT DISTINCT
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
            vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
            FROM
                vista_detalle_plan_de_cuentas_por_subcuenta
            WHERE
            vista_detalle_plan_de_cuentas_por_subcuenta.grupo_estado = 1");

        //! validacion de fachas
        if($fechaInicio_buscado_er < $datosEjercicioActivo->fechaInicio || $fechaFin_buscado_er > $datosEjercicioActivo->fechaCierre ||
        $fechaInicio_buscado_bg < $datosEjercicioActivo->fechaInicio || $fechaFin_buscado_bg > $datosEjercicioActivo->fechaCierre)
        {
            session()->flash('error','fechas_de_busqueda');
        }

        return view('modulos.contabilidad.reportes_financieros.estados_financieros')
                    ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                    ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                    ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                    ->with('datosEjercicioActivo',$datosEjercicioActivo)
                    ->with('fechaInicio_buscado_er',$fechaInicio_buscado_er) //! datos estado de resultados
                    ->with('fechaFin_buscado_er',$fechaFin_buscado_er) //! datos estado de resultados
                    ->with('acumulado_subcuentas_er',$acumulado_subcuentas_er) //! datos estado de resultados
                    ->with('acumulado_cuentas_er',$acumulado_cuentas_er) //! datos estado de resultados
                    ->with('acumulado_subgrupos_er',$acumulado_subgrupos_er) //! datos estado de resultados
                    ->with('acumulado_grupos_er',$acumulado_grupos_er) //! datos estado de resultados
                    ->with('acumulado_tipos_er',$acumulado_tipos_er) //! datos estado de resultados
                    ->with('fechaInicio_buscado_bg',$fechaInicio_buscado_bg) //* datos balance general
                    ->with('fechaFin_buscado_bg',$fechaFin_buscado_bg) //* datos balance general
                    ->with('acumulado_subcuentas_bg',$acumulado_subcuentas_bg) //* datos balance general
                    ->with('acumulado_cuentas_bg',$acumulado_cuentas_bg) //* datos balance general
                    ->with('acumulado_subgrupos_bg',$acumulado_subgrupos_bg) //* datos balance general
                    ->with('acumulado_grupos_bg',$acumulado_grupos_bg) //* datos balance general
                    ->with('acumulado_tipos_bg',$acumulado_tipos_bg) //* datos balance general
                    ->with('tipos_todos',$tipos_todos) //! datos estado de resultados
                    ->with('grupos_todos',$grupos_todos); //! datos estado de resultados
    }

    //EXCEL
    public function bbgg_excel(Request $request){
        try {
            //! BUSQUEDA PERSONALIZADA
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            //? *********** 1 estado de resultados **************
            $fechaInicio_buscado_er = $request->get('fechaInicio_er');
            $fechaFin_buscado_er = $request->get('fechaFin_er');
            //? *********** 2 balance general **************
            $fechaInicio_buscado_bg = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado_bg = $request->get('fechaFin_bg');

            //? *************************************************
            //? *********** 1 estado de resultados **************
            //? *************************************************

            //! ****************************/
            $acumulado_tipos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_grupos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subgrupos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_cuentas_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subcuentas_er = DB::select(
                "SELECT
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    SUM(detalle_comprobante.debe) AS suma_debe,
                    SUM(detalle_comprobante.haber) AS suma_haber,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);


            //return $acumulado_cuentas_er;

            //? *************************************************
            //? *********** 2 balance general **************
            //? *************************************************

            //! ****************************/
            $acumulado_tipos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_grupos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subgrupos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_cuentas_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subcuentas_bg = DB::select(
                "SELECT
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    SUM(detalle_comprobante.debe) AS suma_debe,
                    SUM(detalle_comprobante.haber) AS suma_haber,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);


            //? *************************************************
            //? *********** 3 datos utilizados por ambos *****
            //? *************************************************
            //! todos los tipos
            $tipos_todos = DB::table('pc_tipo')
                    ->where('estado','=',1)
                    ->orderBy('id','ASC')
                    ->get();
            //! todos los grupos
            $grupos_todos = DB::select(
                "SELECT DISTINCT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    vista_detalle_plan_de_cuentas_por_subcuenta
                WHERE
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_estado = 1");



            // https://stackoverflow.com/questions/tagged/dompdf

            $datosEmpresa = Empresa::find(auth()->user()->idEmpresaActiva);

            //? $pdf = PDF::loadView('modulos.reportes_pdf.eeff.bbgg_PDF', compact('datosEmpresa','datosEjercicioActivo', 'fechaInicio_buscado_er', 'fechaFin_buscado_er', 'acumulado_subcuentas_er', 'acumulado_cuentas_er', 'acumulado_subgrupos_er', 'acumulado_grupos_er', 'acumulado_tipos_er', 'fechaInicio_buscado_bg', 'fechaFin_buscado_bg', 'acumulado_subcuentas_bg', 'acumulado_cuentas_bg', 'acumulado_subgrupos_bg', 'acumulado_grupos_bg', 'acumulado_tipos_bg', 'tipos_todos', 'grupos_todos'));


            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new BalanceGeneralExport($datosEmpresa, $datosEjercicioActivo, $fechaInicio_buscado_er, $fechaFin_buscado_er, $acumulado_subcuentas_er, $acumulado_cuentas_er, $acumulado_subgrupos_er, $acumulado_grupos_er, $acumulado_tipos_er, $fechaInicio_buscado_bg, $fechaFin_buscado_bg, $acumulado_subcuentas_bg, $acumulado_cuentas_bg, $acumulado_subgrupos_bg, $acumulado_grupos_bg, $acumulado_tipos_bg, $tipos_todos, $grupos_todos), 'balance_general.xlsx');

        } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            return redirect(route('estados-financieros'));
        }
    }

    public function eerr_excel(Request $request){
        try {
            //! BUSQUEDA PERSONALIZADA
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            //? *********** 1 estado de resultados **************
            $fechaInicio_buscado_er = $request->get('fechaInicio_er');
            $fechaFin_buscado_er = $request->get('fechaFin_er');
            //? *********** 2 balance general **************
            $fechaInicio_buscado_bg = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado_bg = $request->get('fechaFin_bg');

            //? *************************************************
            //? *********** 1 estado de resultados **************
            //? *************************************************

            //! ****************************/
            $acumulado_tipos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_grupos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subgrupos_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_cuentas_er = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subcuentas_er = DB::select(
                "SELECT
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    SUM(detalle_comprobante.debe) AS suma_debe,
                    SUM(detalle_comprobante.haber) AS suma_haber,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_er,$fechaFin_buscado_er,$idEjercicioActivo]);


            //return $acumulado_cuentas_er;

            //? *************************************************
            //? *********** 2 balance general **************
            //? *************************************************

            //! ****************************/
            $acumulado_tipos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_descripcion
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_grupos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subgrupos_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.grupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_cuentas_bg = DB::select(
                "SELECT
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id

                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subGrupo_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);

            //! ****************************/
            $acumulado_subcuentas_bg = DB::select(
                "SELECT
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    SUM(detalle_comprobante.debe) AS suma_debe,
                    SUM(detalle_comprobante.haber) AS suma_haber,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    detalle_comprobante
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                INNER JOIN vista_detalle_plan_de_cuentas_por_subcuenta ON vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo =  detalle_comprobante.subcuenta_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY
                    detalle_comprobante.subcuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_codigo,
                    vista_detalle_plan_de_cuentas_por_subcuenta.subCuenta_descripcion,
                    vista_detalle_plan_de_cuentas_por_subcuenta.cuenta_id,
                    vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                ORDER BY detalle_comprobante.subcuenta_id ASC, vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo ASC"
            ,[$fechaInicio_buscado_bg,$fechaFin_buscado_bg,$idEjercicioActivo]);


            //? *************************************************
            //? *********** 3 datos utilizados por ambos *****
            //? *************************************************
            //! todos los tipos
            $tipos_todos = DB::table('pc_tipo')
                    ->where('estado','=',1)
                    ->orderBy('id','ASC')
                    ->get();
            //! todos los grupos
            $grupos_todos = DB::select(
                "SELECT DISTINCT
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_codigo,
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_descripcion,
                vista_detalle_plan_de_cuentas_por_subcuenta.tipo_codigo
                FROM
                    vista_detalle_plan_de_cuentas_por_subcuenta
                WHERE
                vista_detalle_plan_de_cuentas_por_subcuenta.grupo_estado = 1");



            // https://stackoverflow.com/questions/tagged/dompdf

            $datosEmpresa = Empresa::find(auth()->user()->idEmpresaActiva);

            //? $pdf = PDF::loadView('modulos.reportes_pdf.eeff.bbgg_PDF', compact('datosEmpresa','datosEjercicioActivo', 'fechaInicio_buscado_er', 'fechaFin_buscado_er', 'acumulado_subcuentas_er', 'acumulado_cuentas_er', 'acumulado_subgrupos_er', 'acumulado_grupos_er', 'acumulado_tipos_er', 'fechaInicio_buscado_bg', 'fechaFin_buscado_bg', 'acumulado_subcuentas_bg', 'acumulado_cuentas_bg', 'acumulado_subgrupos_bg', 'acumulado_grupos_bg', 'acumulado_tipos_bg', 'tipos_todos', 'grupos_todos'));


            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new EstadoResultadoExport($datosEmpresa, $datosEjercicioActivo, $fechaInicio_buscado_er, $fechaFin_buscado_er, $acumulado_subcuentas_er, $acumulado_cuentas_er, $acumulado_subgrupos_er, $acumulado_grupos_er, $acumulado_tipos_er, $fechaInicio_buscado_bg, $fechaFin_buscado_bg, $acumulado_subcuentas_bg, $acumulado_cuentas_bg, $acumulado_subgrupos_bg, $acumulado_grupos_bg, $acumulado_tipos_bg, $tipos_todos, $grupos_todos), 'estado_de_resultado.xlsx');

        } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            return redirect(route('estados-financieros'));
        }
    }

}
