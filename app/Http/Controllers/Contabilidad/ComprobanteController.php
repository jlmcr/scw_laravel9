<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\TipoDeComprobante;
use App\Models\Comprobante;
use App\Models\DetalleComprobante;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ComprobanteController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect(route('comprobante.create'));
    }

    public function create()
    {
        $tiposComprobantes = TipoDeComprobante::where('estado','=',1)->get();

        //PARA LA PARTE DE LAS CUENTAS
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');

        // $subcuentas_y_cuentas = DB::select('SELECT pc_sub_cuenta.id AS id_subcuenta, pc_cuenta.id AS id_cuenta, pc_partida_contable.descripcion AS descripcion_cuenta
        // FROM pc_cuenta
        // INNER JOIN pc_partida_contable ON pc_cuenta.codigo_partida = pc_partida_contable.codigo
        // INNER JOIN pc_sub_cuenta ON pc_sub_cuenta.cuenta_id = pc_cuenta.id');

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! para mostrar fechas del ejercicio activo
        $ejercicioActivo = Ejercicio::find(auth()->user()->idEjercicioActivo);

        return view('modulos.contabilidad.comprobante.create')
            ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
            ->with('tiposComprobantes',$tiposComprobantes) //! utilizado para la tabla derecha de tipos de comprobante
            ->with('sub_cuentas',$sub_cuentas)
            ->with('ejercicioActivo',$ejercicioActivo);//! utilizado por la tabla derecha, en los periodos habilitados

            // return $subcuentas_y_cuentas;
    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            //!validacion de que el comprobante esté dentro del periodo
                //en la vista y aqui
            //!fecha
            //validamos existencia de fecha

            $fa=explode('-',$request->get('fecha')); //? $request->get('fecha') tiene el siguiente formato -> aaaa/mm/dd
            $fechaAuxiliar =$fa[2]."/".$fa[1]."/".$fa[0]; //d/m/Y //! si modifico el input, esto debo modificar

            $fechaComprobante = Carbon::createFromFormat('d/m/Y', $fechaAuxiliar); //! la fecha con input date se genera: aaaa/mm/dd

            $f1 = explode('/',$fechaAuxiliar);
            //!valida si una fecaha existe - mes, el día y el año
            if(count($f1) == 3)
            {
                if(checkdate($f1[1], $f1[0], $f1[2])==true) //checkdate(int $month, int $day, int $year): bool
                {
                    $ejercicioActivo = Ejercicio::find(auth()->user()->idEjercicioActivo);

                    //!validacion de que el comprobante esté dentro del periodo
                    $fechaInicio = Carbon::createFromFormat('Y-m-d',$ejercicioActivo->fechaInicio);
                    $fechaCierre = Carbon::createFromFormat('Y-m-d',$ejercicioActivo->fechaCierre);
                    if($fechaComprobante >= $fechaInicio && $fechaComprobante <= $fechaCierre)
                    {

                        //! VERIFICAMOS QUE NO HAYA DUPLICIDAD DE NUMERO EN EL MISMO
                        //! MES - AÑO -TIPO - EJERCICIO
                        $numeroComprobante= $request->get('nroComprobante'); //enviados de la vista
                        $correlativo= $request->get('correlativo'); //enviados de la vista

                        $anio = date("Y", strtotime($fechaComprobante));
                        $mes = date("m", strtotime($fechaComprobante));
                        $idTipoComprobante = $request->get('tipoComprobante');
                        $idEjercicio = auth()->user()->idEjercicioActivo;

                        $prueba = DB::select('SELECT * FROM comprobantes
                        WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=? AND nroComprobante=?',
                        [$anio, $mes, $idTipoComprobante, $idEjercicio, $numeroComprobante]);

                        //*empty() determina si una variable está vacía
                        if($prueba != null) // funciona con arreglos, el "" no me da un resultado buscado en arrays
                        {
                            //! existe duplicidad, entonces volvemos a generarlo

                            $correl = DB::select('SELECT max(correlativo) AS maximo FROM comprobantes
                            WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=?',
                            [$anio, $mes, $idTipoComprobante, $idEjercicio]);

                            //? correlativo
                            $correlativo = $correl[0]->maximo +1; //?actualizamos el correlativo

                            $longitud_correlativo = strlen($correlativo);//longitud de cadena
                            $codigo_correlativo ="";

                            //evaluamos el largo de la cadena
                            switch ($longitud_correlativo) {
                                case 0:
                                    $codigo_correlativo = "0000".$correlativo; // ejemplo 0000
                                    break;
                                case 1:
                                    $codigo_correlativo = "000".$correlativo; // ejemplo 0009
                                    break;
                                case 2:
                                    $codigo_correlativo = "00".$correlativo; // ejemplo 0099
                                    break;
                                case 3:
                                    $codigo_correlativo = "0".$correlativo; // ejemplo 0999
                                    break;
                                default:
                                    $codigo_correlativo = $correlativo;
                                    break;
                            }
                            //! CODIGO NUMERO
                            $numeroComprobante = $idTipoComprobante."-".$mes.$codigo_correlativo;

                            //aviso para el usuario
                            session()->flash('numeroComprobante','numero actualizado');

                        }

                        $comprobante = new Comprobante();
                        $comprobante->estado=1;
                        $comprobante->nroComprobante = $numeroComprobante;
                        $comprobante->fecha = $fechaComprobante;
                        $comprobante->correlativo = $correlativo;
                        $comprobante->concepto = strtoupper($request->get('concepto'));
                        $comprobante->notas = strtoupper($request->get('notas'));
                        $comprobante->observaciones = $request->get('observaciones');
                        $comprobante->documento = strtoupper($request->get('documento'));
                        $comprobante->numeroDocumento = strtoupper($request->get('numeroDocumento'));
                        $comprobante->ejercicio_id = $idEjercicio;
                        $comprobante->tipoComprobante_id = $idTipoComprobante;
                        $comprobante->save();


                        //! DETALLE DEL COMPROBANTE
                        $codigos = $request->get('codigo');
                        $importesDebe = $request->get('debe');
                        $importesHaber = $request->get('haber');
                        $orden = 1;

                        //? datos del comprobante general ya guardado
                        $comprobante_guardado = DB::select('SELECT * FROM comprobantes
                        WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=? AND nroComprobante=?',
                        [$anio, $mes, $idTipoComprobante, $idEjercicio, $numeroComprobante]);

                        $idComprobante = $comprobante_guardado[0]->id;

                        for ($i=0; $i < count($codigos) ; $i++) {

                            //? diferencia entre nulo y vacio
                            /* La respuesta exacta depende un tanto del lenguaje, pero en general:
                            "Nulo" significa "no asignado"
                            "Vacío" significa "asignado, pero sin contenido" */

                            if($codigos[$i] != "")
                            {
                                //! 1ro valores debe y haber
                                    //debe
                                    if( $importesDebe[$i] == null){
                                        $valorDebe = 0;
                                    }
                                    else{
                                        $valorDebe = str_replace(",","",$importesDebe[$i]);
                                    }
                                    //haber
                                    if( $importesHaber[$i] == null){
                                        $valorHaber = 0;
                                    }
                                    else{
                                        $valorHaber = str_replace(",","",$importesHaber[$i]);
                                    }
                                //1ro valores debe y haber

                                //! 2do valores guardamos
                                $detalle = new DetalleComprobante();
                                $detalle->debe = $valorDebe;
                                $detalle->haber = $valorHaber;
                                $detalle->orden = $orden;
                                $detalle->comprobante_id = $idComprobante;
                                $detalle->subcuenta_id = $codigos[$i];
                                $detalle->save();

                                $orden ++;
                                //$orden += 1;
                            }

                        }

                        //return $prueba != null;
                        session()->flash('comprobante','creado');
                        return redirect(route('comprobante.create'));
                    }
                    else
                    {
                        session()->flash('fecha','fuera de periodo');
                        return redirect(route('comprobante.create'));
                    }
                }
                else
                {
                    session()->flash('fecha','fecha inexistente');
                    return redirect(route('comprobante.create'));
                }
            }
            else
            {
                session()->flash('fecha','fecha inexistente');
                return redirect(route('comprobante.create'));
            }
            //return $fechaComprobante;
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('comprobante.create'));
        }
    }

    public function generarNroComprobante(Request $request)
    {
        //!fecha
        //validamos existencia de fecha

        $fa=explode('-',$request->get('fecha')); //? $request->get('fecha') tiene el siguiente formato -> aaaa/mm/dd
        $fechaAuxiliar =$fa[2]."/".$fa[1]."/".$fa[0]; //d/m/Y //! si modifico el input, esto debo modificar

        $fechaComprobante = Carbon::createFromFormat('d/m/Y', $fechaAuxiliar); //! la fecha con input date se genera: aaaa/mm/dd

        $f1 = explode('/',$fechaAuxiliar);
        //!valida si una fecaha existe - mes, el día y el año
            if(count($f1) == 3)
            {
                if(checkdate($f1[1], $f1[0], $f1[2])==true) //checkdate(int $month, int $day, int $year): bool
                {
                    $ejercicioActivo = Ejercicio::find(auth()->user()->idEjercicioActivo);

                    //!validacion de que el comprobante esté dentro del periodo
                    $fechaInicio = Carbon::createFromFormat('Y-m-d',$ejercicioActivo->fechaInicio);
                    $fechaCierre = Carbon::createFromFormat('Y-m-d',$ejercicioActivo->fechaCierre);

                    if($fechaComprobante >= $fechaInicio && $fechaComprobante <= $fechaCierre)
                    {
                        //! CORRELATIVO
                        $anio = date("Y", strtotime($fechaComprobante));
                        $mes = date("m", strtotime($fechaComprobante));
                        $idTipoComprobante = $request->get('tipoComprobante');
                        $idEjercicio = auth()->user()->idEjercicioActivo;

                        $correl = DB::select('SELECT max(correlativo) AS maximo FROM comprobantes
                        WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=?',
                        [$anio, $mes, $idTipoComprobante, $idEjercicio]);

                        //? correlativo
                        $correlativo = $correl[0]->maximo +1;

                        $longitud_correlativo = strlen($correlativo);//longitud de cadena
                        $codigo_correlativo ="";

                        //evaluamos el largo de la cadena
                        switch ($longitud_correlativo) {
                            case 0:
                                $codigo_correlativo = "0000".$correlativo; // ejemplo 0000
                                break;
                            case 1:
                                $codigo_correlativo = "000".$correlativo; // ejemplo 0009
                                break;
                            case 2:
                                $codigo_correlativo = "00".$correlativo; // ejemplo 0099
                                break;
                            case 3:
                                $codigo_correlativo = "0".$correlativo; // ejemplo 0999
                                break;
                            default:
                                $codigo_correlativo = $correlativo;
                                break;
                        }
                        //! CODIGO NUMERO
                        $codigo_numero_comprobante = $idTipoComprobante."-".$mes.$codigo_correlativo;

                        $respuesta[]=[
                            'numero_comprobante'=> $codigo_numero_comprobante,
                            'correlativo' => $correlativo,
                            'fecha' => "correcta"
                        ];
                        return $respuesta;
                    }
                    else
                    {
                        $respuesta[]=[
                            'numero_comprobante'=> "",
                            'correlativo' => "",
                            'fecha' => "fuera de periodo"
                        ];
                        return $respuesta;
                    }

                }
                else
                {
                    $respuesta[]=[
                        'numero_comprobante'=> "",
                        'correlativo' => "",
                        'fecha' => "erronea"
                    ];
                    return $respuesta;
                }
            }
            else
            {
                $respuesta[]=[
                    'numero_comprobante'=> "",
                    'correlativo' => "",
                    'fecha' => "erronea"
                ];
                return $respuesta;
            }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //! DATOS GENERALES  DEL COMPROBANTE
        $datosGeneralesComprobante = Comprobante::find($id);

        //! DATOS DEL DETALLE DEL COMPROBANTE
        // select `detalle_comprobante`.*, `pc_partida_contable`.`codigo`, `pc_partida_contable`.`descripcion` from `detalle_comprobante` inner join `pc_sub_cuenta` on `detalle_comprobante`.`subcuenta_id` = `pc_sub_cuenta`.`id` inner join `pc_partida_contable` on `pc_sub_cuenta`.`codigo_partida` = `pc_partida_contable`.`codigo` where `detalle_comprobante`.`comprobante_id` = 1;
        $detalleComprobante = DB::table('detalle_comprobante')
                    ->join('pc_sub_cuenta','detalle_comprobante.subcuenta_id','=','pc_sub_cuenta.id')
                    ->join('pc_partida_contable','pc_sub_cuenta.codigo_partida','=','pc_partida_contable.codigo')
                    ->select('detalle_comprobante.*','pc_partida_contable.codigo','pc_partida_contable.descripcion')
                    ->where('detalle_comprobante.comprobante_id','=',$id)
                    ->orderBy('detalle_comprobante.orden','ASC')
                    ->get();

        //return  $datosGeneralesComprobante;

        //! DATOS PARA LA INTERFAZ DEL COMPROBANTE
        $tiposComprobantes = TipoDeComprobante::where('estado','=',1)->get();

        //! PARA LA PARTE DE LAS CUENTAS
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! para mostrar fechas del ejercicio activo
        $ejercicioActivo = Ejercicio::find(auth()->user()->idEjercicioActivo);

        return view('modulos.contabilidad.comprobante.edit')
                    ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                    ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                    ->with('tiposComprobantes',$tiposComprobantes) //! utilizado para la tabla derecha de tipos de comprobante
                    ->with('sub_cuentas',$sub_cuentas)
                    ->with('ejercicioActivo',$ejercicioActivo)//! utilizado por la tabla derecha, en los periodos habilitados
                    ->with('datosGeneralesComprobante',$datosGeneralesComprobante) //? datos del comprobante
                    ->with('detalleComprobante',$detalleComprobante); //? datos del comprobante

        // return $subcuentas_y_cuentas;
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $comprobante = Comprobante::find($id);
            $comprobante->concepto = strtoupper($request->get('concepto'));
            $comprobante->notas = strtoupper($request->get('notas'));
            $comprobante->observaciones = $request->get('observaciones');
            $comprobante->documento = strtoupper($request->get('documento'));
            $comprobante->numeroDocumento = strtoupper($request->get('numeroDocumento'));
            $comprobante->save();

            //! DETALLE DEL COMPROBANTE
            $codigos = $request->get('codigo');
            $importesDebe = $request->get('debe');
            $importesHaber = $request->get('haber');
            $orden = 1;

            //! datos del comprobante general ya guardado
            $idComprobanteGeneral = $id;

            //! borramos detalles anteriores
            /* DB::delete('delete detalle_comprobante where comprobante_id = ?', [$idComprobanteGeneral]);
            DB::delete('delete users where name = ?', ['John']) */
            DB::table('detalle_comprobante')->where('comprobante_id','=',$idComprobanteGeneral)->delete();

            for ($i=0; $i < count($codigos) ; $i++) {

                //? diferencia entre nulo y vacio
                /* La respuesta exacta depende un tanto del lenguaje, pero en general:
                "Nulo" significa "no asignado"
                "Vacío" significa "asignado, pero sin contenido" */

                if($codigos[$i] != "")
                {
                    //! 1ro valores debe y haber
                        //debe
                        if( $importesDebe[$i] == null){
                            $valorDebe = 0;
                        }
                        else{
                            $valorDebe = str_replace(",","",$importesDebe[$i]);
                        }
                        //haber
                        if( $importesHaber[$i] == null){
                            $valorHaber = 0;
                        }
                        else{
                            $valorHaber = str_replace(",","",$importesHaber[$i]);
                        }

                    //! 2do valores guardamos
                    $detalle = new DetalleComprobante();
                    $detalle->debe = $valorDebe;
                    $detalle->haber = $valorHaber;
                    $detalle->orden = $orden;
                    $detalle->comprobante_id = $idComprobanteGeneral;
                    $detalle->subcuenta_id = $codigos[$i];
                    $detalle->save();

                    $orden ++;
                    //$orden += 1;
                }

            }

            //return $prueba != null;
            session()->flash('comprobante','actualizado');
            return redirect(route('comprobante.edit',$id));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('comprobante.edit',$id));
        }
    }

    public function destroy($id, Request $request)
    {
        if(auth()->user()->eliminar == 1)
        {
            $fechaInicio_buscado = $request->get('fechaInicio_eliminar');
            $fechaFin_buscado = $request->get('fechaFin_eliminar');

            $comprobante = Comprobante::find($id); //* hacemos una consulta
            $comprobante->estado = 0;
            $comprobante->save();

            try {
                $venta = Venta::where('registroContable','=',$comprobante->nroComprobante.'*'.$comprobante->id)->get();
                $venta[0]->registroContable = "";
                $venta[0]->save();

            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $compra = Compra::where('registroContable','=',$comprobante->nroComprobante.'*'.$comprobante->id)->get();
                $compra[0]->registroContable = "";
                $compra[0]->save();
                //return $compra[0];

            } catch (\Throwable $th) {
                //throw $th;
            }

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('contabilidad/libro-diario?process=search&fechaInicio='.$fechaInicio_buscado.'&fechaFin='.$fechaFin_buscado);
            //http://sistemacontable.test/contabilidad/libro-diario?process=search&fechaInicio=2022-01-01&fechaFin=2022-02-28
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('libro-diario'));
        }
    }
}
