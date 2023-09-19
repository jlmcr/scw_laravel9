<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ejercicio;
use App\Models\Comprobante;
use App\Models\DetalleComprobante;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class LibroDiarioController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');
        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();

        if($request->get('process') == 'search' )
        {
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            $fechaInicio_buscado = $request->get('fechaInicio');
            $fechaFin_buscado = $request->get('fechaFin');

            //! POR SI LA FECHA ESTA FUERA DEL EJERCICIO
            if($fechaInicio_buscado < $datosEjercicioActivo->fechaInicio || $fechaFin_buscado > $datosEjercicioActivo->fechaCierre )
            {
                session()->flash('error','fechas_de_busqueda');

                return view('modulos.contabilidad.libro_diario.libro_diario')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                ->with('datosEjercicioActivo',$datosEjercicioActivo)
                ->with('fechaInicio_buscado',$fechaInicio_buscado)
                ->with('fechaFin_buscado',$fechaFin_buscado);
            }
            else
            {
                //! BUSQUEDA PESONALIZADA
                // no usar Comprobante::all()->where
                $comprobantesEncontrados = Comprobante::where('ejercicio_id','=',$idEjercicioActivo)
                            ->where('estado','=',1)
                            ->whereBetween('fecha',[$fechaInicio_buscado,$fechaFin_buscado])
                            ->orderBy('fecha')
                            ->orderBy('tipoComprobante_id')
                            ->get();

                //?SQL  select * from `comprobantes` where `fecha` between '2022-01-01' and '2022-12-31' and `ejercicio_id` = 3

                return view('modulos.contabilidad.libro_diario.libro_diario')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                ->with('comprobantesEncontrados',$comprobantesEncontrados)
                ->with('datosEjercicioActivo',$datosEjercicioActivo)
                ->with('fechaInicio_buscado',$fechaInicio_buscado)
                ->with('fechaFin_buscado',$fechaFin_buscado);
            }

        }
        else
        {
            //! BUSQUEDA DE TODO PREDETERMINADA DE TODO EL EJERCICIO
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            $fechaInicio_buscado = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado = $datosEjercicioActivo->fechaCierre;

            // no usar Comprobante::all()->where
            //?SQL  select * from `comprobantes` where `fecha` between '2022-01-01' and '2022-12-31' and `ejercicio_id` = 3
            $comprobantesEncontrados = Comprobante::where('ejercicio_id','=',$idEjercicioActivo)
                        ->where('estado','=',1)
                        ->whereBetween('fecha',[$fechaInicio_buscado,$fechaFin_buscado])
                        ->orderBy('fecha')
                        ->orderBy('tipoComprobante_id')
                        ->get();

            return view('modulos.contabilidad.libro_diario.libro_diario')
                       ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                        ->with('comprobantesEncontrados',$comprobantesEncontrados)
                        ->with('datosEjercicioActivo',$datosEjercicioActivo)
                        ->with('fechaInicio_buscado',$fechaInicio_buscado)
                        ->with('fechaFin_buscado',$fechaFin_buscado);
        }
        //return $comprobantesEncontrados;
    }

    public function comprobanteDetalle(Request $request)
    {
        $idComprobante=$request->get('idComprobante');

        // select `detalle_comprobante`.*, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion` from `detalle_comprobante` inner join `pc_sub_cuenta` on `detalle_comprobante`.`subcuenta_id` = `pc_sub_cuenta`.`id` inner join `pc_partida_contable` on `pc_sub_cuenta`.`codigo_partida` = `pc_partida_contable`.`codigo` where `detalle_comprobante`.`comprobante_id` = 1;

        $detalleComprobante = DB::table('detalle_comprobante')
                    ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                    ->join('pc_partida_contable','pc_sub_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                    ->select('detalle_comprobante.*','pc_partida_contable.codigo','pc_partida_contable.descripcion')
                    ->where('detalle_comprobante.comprobante_id','=',$idComprobante)
                    ->orderBy('detalle_comprobante.orden','ASC')
                    ->get();

        //$detalleComprobante = DetalleComprobante::all();

        return  $detalleComprobante;
    }

}



