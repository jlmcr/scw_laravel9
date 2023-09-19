<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        //! Busqueda de todas las empresas
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');


        //! Busqueda de todas las empresas
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        //return $idEmpresaActiva;

        //! TARJETAS

        $empresasLista = Empresa::all()
        ->where('estado','=',1);

        $cantRegistrosDelEjercicioAcivo = DB::table('comprobantes')->where('estado','=',1)->where('ejercicio_id', '=', auth()->user()->idEjercicioActivo)->count();
        $cantSucursalesDeLaEmpresaActiva = DB::table('sucursals')->where('estado','=',1)->where('empresa_id', '=', auth()->user()->idEmpresaActiva)->count();
        $cantEjerciciosDeLaEmpresaActiva = DB::table('ejercicios')->where('estado','=',1)->where('empresa_id', '=', auth()->user()->idEmpresaActiva)->count();


        //! graficos
        //activo fijo
            //! rubros con la cantidad de activos deoendientes en la empresa activa
            $rubros = DB::select(
                "SELECT rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
                COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
                FROM rubros
                LEFT JOIN(
                    SELECT * FROM activos_fijos WHERE activos_fijos.empresa_id = ?
                ) AS activos_fijos
                ON rubros.id = activos_fijos.rubro_id
                GROUP BY
                rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum,
                rubros.sujetoAdepreciacion", [$idEmpresaActiva]);

            $datos_rubros_array=[];

            //return $rubros;

            foreach($rubros as $rubro)
            {
                $datos_rubros_array['label'][]= $rubro->rubro;
                $datos_rubros_array['data'][]= $rubro->cantidad_activos_registrados;
            }
            $data_rubros = json_encode($datos_rubros_array['data']);
            $label_rubros = json_encode($datos_rubros_array['label']);
        //activo fijo

        //! tarjeta compras
        $datos_compras = DB::select(
            "SELECT
                YEAR(FECHA) AS anio,
                MONTH(FECHA)AS mes,
                SUM(compras.importeTotal) AS suma
            FROM
                compras
            INNER JOIN sucursals ON compras.sucursal_id = sucursals.id
            INNER JOIN empresas ON sucursals.empresa_id = empresas.id

            WHERE
                empresas.id = ?
            GROUP BY
                MONTH(FECHA),
                YEAR(FECHA)
            ORDER BY anio DESC, mes DESC ", [$idEmpresaActiva]);

        //! tarjeta ventas
        $datos_ventas = DB::select(
            "SELECT
                YEAR(FECHA) AS anio,
                MONTH(FECHA)AS mes,
                SUM(ventas.importeTotal) AS suma
            FROM
                ventas
            INNER JOIN sucursals ON ventas.sucursal_id = sucursals.id
            INNER JOIN empresas ON sucursals.empresa_id = empresas.id

            WHERE
                empresas.id = ? AND
                ventas.estado = 'V'
            GROUP BY
                MONTH(FECHA),
                YEAR(FECHA)
            ORDER BY anio DESC, mes DESC ", [$idEmpresaActiva]);
        

        //return $datos_compras;

        //! tarjeta sucursales2
        $sucursales_de_la_empresa_dash = Sucursal::where('empresa_id','=',$idEmpresaActiva)
                            ->where('estado','=',1)
                            ->get();

        //! tarjeta ejer2
        $ejercicios_de_la_empresa_dash = Ejercicio::where('empresa_id','=',$idEmpresaActiva)
                            ->where('estado','=',1)
                            ->get();


        $dia = date('d');
        // return $dia; $dia =='01'


        if ($dia =='01')
        {
            session()->flash('respaldo','recomendar');
        }

        return view('dashboard')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
        ->with('empresasLista',$empresasLista) //? este es usado por la vista index
        ->with('cantRegistrosDelEjercicioAcivo',$cantRegistrosDelEjercicioAcivo)
        ->with('cantSucursalesDeLaEmpresaActiva',$cantSucursalesDeLaEmpresaActiva)
        ->with('cantEjerciciosDeLaEmpresaActiva',$cantEjerciciosDeLaEmpresaActiva)
        ->with('label_rubros',$label_rubros)
        ->with('data_rubros',$data_rubros)
        ->with('datos_compras',$datos_compras)
        ->with('datos_ventas',$datos_ventas)
        ->with('sucursales_de_la_empresa_dash',$sucursales_de_la_empresa_dash)
        ->with('ejercicios_de_la_empresa_dash',$ejercicios_de_la_empresa_dash);


    }
}
