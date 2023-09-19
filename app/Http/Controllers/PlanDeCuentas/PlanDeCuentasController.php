<?php

namespace App\Http\Controllers\PlanDeCuentas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PartidaContable;
use App\Models\Empresa;
use App\Models\Ejercicio;
use Exception;

class PlanDeCuentasController extends Controller
{

    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
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

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();

        return view('modulos.contabilidad.plan_de_Cuentas.index')
                ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                ->with('tipos',$tipos)
                ->with('grupos',$grupos)
                ->with('sub_grupos',$sub_grupos)
                ->with('cuentas',$cuentas)
                ->with('sub_cuentas',$sub_cuentas);
        //return $grupos;
    }



    public function actualizarTipo(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $idTipo = $request->get('idCodigoTipo');
            $nuevaDescripcion = strtoupper($request->get('descripcionTipo'));

            DB::update("UPDATE pc_tipo SET descripcion='".$nuevaDescripcion."' WHERE id='".$idTipo."'");

            //$tipo = DB::select('SELECT * FROM pc_tipo WHERE id='.$idTipo);

            //return $tipo;
            session()->flash('actualizar_tipo','ok');
            return redirect(route('plan-de-cuentas'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }


    public function crearGrupo(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            try {
                    $idTipo = $request->get('idCodigoTipo_mcg');
                    $descripcionGrupo = strtoupper($request->get('descripcionGrupo_mcg'));
                    $nivel = 2;

                    //! consultas y preparacion de datos para la tabla plandecuentas
                    //? correlativo
                    // SELECT max(correlativo) FROM pc_partida_contable where tipo_id=1 and nivel =2;
                    //array
                    $correl = DB::select('SELECT max(correlativo) AS maximo FROM pc_partida_contable where tipo_id=? AND nivel=?',[$idTipo, $nivel]);


                    //? lista de grupo dado un tipo
                    // SELECT * FROM pc_partida_contable where tipo_id=1 and nivel =2;

                    //?codigo
                    $correlativo = $correl[0]->maximo +1;

                    $longitud_correlativo = strlen($correlativo);//longitud de cadena
                    $codigo_correlativo ="";

                    //evaluamos el largo de la cadena
                    switch ($longitud_correlativo) {
                        case 0:
                            $codigo_correlativo = "00".$correlativo; // ejemplo 00
                            break;
                        case 1:
                            $codigo_correlativo = "0".$correlativo; // ejemplo 09
                            break;
                        default:
                            $codigo_correlativo = $correlativo;
                            break;
                    }

                    $codigo = $idTipo.$codigo_correlativo;

                    $grupo_en_el_plan = new PartidaContable();
                    $grupo_en_el_plan->codigo = $codigo;
                    $grupo_en_el_plan->descripcion = $descripcionGrupo;
                    $grupo_en_el_plan->nivel = $nivel;
                    $grupo_en_el_plan->correlativo = $correlativo;
                    $grupo_en_el_plan->estado = 1;
                    $grupo_en_el_plan->tipo_id = $idTipo;
                    $grupo_en_el_plan->save();


                    //! creamos en la tabla grupo

                    DB::insert('INSERT INTO pc_grupo(id, codigo_partida) VALUES (?,?)',[$codigo, $codigo]);
                    // DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);


                    session()->flash('crear_grupo','ok');
                    return redirect(route('plan-de-cuentas'));

                    //$PlanDeCuentas = PlanDeCuentas::all();
                    //return $correlativo;
            } catch(Exception $e){
                session()->flash('error_crear','error');
                return redirect(route('plan-de-cuentas'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function actualizarGrupo(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            // meg modal editar grupo
            $nuevaDescripcion = strtoupper($request->get('descripcionGrupo_meg'));

            $grupo = PartidaContable::find($id);
            $grupo->descripcion = $nuevaDescripcion;
            $grupo->save();

            session()->flash('actualizar_grupo','ok');
            return redirect(route('plan-de-cuentas'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function crearSubGrupo(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            try {
                    $idTipo = $request->get('idCodigoTipo_mcsg');
                    $idGrupo = $request->get('idCodigoGrupo_mcsg');
                    $descripcionSubGrupo = strtoupper($request->get('descripcionSubGrupo_mcsg'));
                    $nivel = 3;

                    //! consultas y preparacion de datos para la tabla plandecuentas
                    //? correlativo

                    //lista de subgrupo:
                    //SELECT pc_sub_grupo.id, pc_sub_grupo.grupo_id, pc_partida_contable.*
                    // FROM pc_sub_grupo
                    // INNER JOIN pc_partida_contable ON pc_sub_grupo.codigo_partida = pc_partida_contable.codigo
                    // WHERE pc_sub_grupo.grupo_id=101 AND pc_partida_contable.nivel =3

                    //array
                    $correl = DB::select('SELECT max(pc_partida_contable.correlativo) AS maximo
                    FROM pc_sub_grupo
                    INNER JOIN pc_partida_contable ON pc_sub_grupo.codigo_partida = pc_partida_contable.codigo
                    WHERE pc_sub_grupo.grupo_id=? AND pc_partida_contable.nivel =?',[$idGrupo, $nivel]);

                    //! CODIGO
                    $correlativo = $correl[0]->maximo +1;
                    $longitud_correlativo = strlen($correlativo);//longitud de cadena
                    $codigo_correlativo ="";

                    //evaluamos el largo de la cadena
                    switch ($longitud_correlativo) {
                        case 0:
                            $codigo_correlativo = "00".$correlativo; // ejemplo 00
                            break;
                        case 1:
                            $codigo_correlativo = "0".$correlativo; // ejemplo 09
                            break;
                        default:
                            $codigo_correlativo = $correlativo;
                            break;
                    }

                    $codigo = $idGrupo.$codigo_correlativo; //! codigo para subgrupo

                    $pruebacodigo = PartidaContable::find($codigo);
                    if($pruebacodigo !="")
                    {
                        //! volvemos a generar el correlativo
                        $correlativo = $correlativo +1;
                        $longitud_correlativo = strlen($correlativo);//longitud de cadena
                        $codigo_correlativo ="";
                        //evaluamos el largo de la cadena
                        switch ($longitud_correlativo) {
                            case 0:
                                $codigo_correlativo = "00".$correlativo; // ejemplo 00
                                break;
                            case 1:
                                $codigo_correlativo = "0".$correlativo; // ejemplo 09
                                break;
                            default:
                                $codigo_correlativo = $correlativo;
                                break;
                        }

                        $codigo = $idGrupo.$codigo_correlativo; //! codigo para subgrupo
                    }

                    //! GUARDADO EN LA TABLA PLANDECUENTAS
                    $SubGrupo = new PartidaContable();
                    $SubGrupo->codigo = $codigo;
                    $SubGrupo->descripcion = $descripcionSubGrupo;
                    $SubGrupo->nivel = $nivel;
                    $SubGrupo->correlativo = $correlativo;
                    $SubGrupo->estado = 1;
                    $SubGrupo->tipo_id = $idTipo; // relacion con plandecuentas con tipo
                    $SubGrupo->save();

                    //! GUARDADO EN LA TABLA SUBGRUPO
                    DB::insert('INSERT INTO pc_sub_grupo(id, codigo_partida, grupo_id) VALUES (?,?,?)',[$codigo, $codigo, $idGrupo]);

                    session()->flash('crear_subgrupo','ok');
                    return redirect(route('plan-de-cuentas'));

            } catch(Exception $e){
                session()->flash('error_crear','error');
                return redirect(route('plan-de-cuentas'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function actualizarSubGrupo(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            // mesg modal editar subgrupo
            $nuevaDescripcion = strtoupper($request->get('descripcionSubGrupo_mesg'));

            $SubGrupo = PartidaContable::find($id);
            $SubGrupo->descripcion = $nuevaDescripcion;
            $SubGrupo->save();

            session()->flash('actualizar_subgrupo','ok');
            return redirect(route('plan-de-cuentas'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function crearCuenta(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            try {
                    $idTipo = $request->get('idCodigoTipo_mcc');
                    $idSubGrupo = $request->get('idCodigoSubGrupo_mcc');
                    $descripcionCuenta = strtoupper($request->get('descripcionCuenta_mcc'));
                    $nivel = 4;

                    //! consultas y preparacion de datos para la tabla plandecuentas
                    //? correlativo

                    //array
                    $correl = DB::select('SELECT max(pc_partida_contable.correlativo) AS maximo
                    FROM pc_cuenta
                    INNER JOIN pc_partida_contable ON pc_cuenta.codigo_partida = pc_partida_contable.codigo
                    WHERE pc_cuenta.subGrupo_id=? AND pc_partida_contable.nivel =?',[$idSubGrupo, $nivel]);

                    //! CODIGO
                    $correlativo = $correl[0]->maximo +1;
                    $longitud_correlativo = strlen($correlativo);//longitud de cadena
                    $codigo_correlativo ="";

                    //evaluamos el largo de la cadena
                    switch ($longitud_correlativo) {
                        case 0:
                            $codigo_correlativo = "00".$correlativo; // ejemplo 00
                            break;
                        case 1:
                            $codigo_correlativo = "0".$correlativo; // ejemplo 09
                            break;
                        default:
                            $codigo_correlativo = $correlativo;
                            break;
                    }

                    $codigo = $idSubGrupo.$codigo_correlativo; //! codigo para cuenta

                    $pruebacodigo = PartidaContable::find($codigo);
                    if($pruebacodigo !="")
                    {
                        //! volvemos a generar el correlativo
                        $correlativo = $correlativo +1;
                        $longitud_correlativo = strlen($correlativo);//longitud de cadena
                        $codigo_correlativo ="";
                        //evaluamos el largo de la cadena
                        switch ($longitud_correlativo) {
                            case 0:
                                $codigo_correlativo = "00".$correlativo; // ejemplo 00
                                break;
                            case 1:
                                $codigo_correlativo = "0".$correlativo; // ejemplo 09
                                break;
                            default:
                                $codigo_correlativo = $correlativo;
                                break;
                        }

                        $codigo = $idSubGrupo.$codigo_correlativo; //! codigo para cuenta
                    }

                    //! GUARDADO EN LA TABLA PLANDECUENTAS
                    $cuenta = new PartidaContable();
                    $cuenta->codigo = $codigo;
                    $cuenta->descripcion = $descripcionCuenta;
                    $cuenta->nivel = $nivel;
                    $cuenta->correlativo = $correlativo;
                    $cuenta->estado = 1;
                    $cuenta->tipo_id = $idTipo; // relacion con plandecuentas con tipo
                    $cuenta->save();

                    //! GUARDADO EN LA TABLA CUENTA
                    DB::insert('INSERT INTO pc_cuenta(id, codigo_partida, subGrupo_id) VALUES (?,?,?)',[$codigo, $codigo, $idSubGrupo]);

                    session()->flash('crear_cuenta','ok');
                    return redirect(route('plan-de-cuentas'));

            } catch(Exception $e){
                session()->flash('error_crear','error');
                return redirect(route('plan-de-cuentas'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function actualizarCuenta(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $nuevaDescripcion = strtoupper($request->get('descripcionCuenta_mec'));

            $cuenta = PartidaContable::find($id);
            $cuenta->descripcion = $nuevaDescripcion;
            $cuenta->save();

            session()->flash('actualizar_cuenta','ok');
            return redirect(route('plan-de-cuentas'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function crearSubCuenta(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            try {
                    $idTipo = $request->get('idCodigoTipo_mcsc');
                    $idCuenta = $request->get('idCodigoCuenta_mcsc');
                    $descripcionSubCuenta = $request->get('descripcionSubCuenta_mcsc');// no convertimos en mayusculas
                    $nivel = 5;

                    //! consultas y preparacion de datos para la tabla plandecuentas
                    //? correlativo

                    //array
                    $correl = DB::select('SELECT max(pc_partida_contable.correlativo) AS maximo
                    FROM pc_sub_cuenta
                    INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
                    WHERE pc_sub_cuenta.cuenta_id=? AND pc_partida_contable.nivel =?',[$idCuenta, $nivel]);

                    //! CODIGO
                    $correlativo = $correl[0]->maximo +1;
                    $longitud_correlativo = strlen($correlativo);//longitud de cadena
                    $codigo_correlativo ="";

                    //evaluamos el largo de la cadena
                    switch ($longitud_correlativo) {
                        case 0:
                            $codigo_correlativo = "000".$correlativo; // ejemplo 000
                            break;
                        case 1:
                            $codigo_correlativo = "00".$correlativo; // ejemplo 009
                            break;
                        case 2:
                            $codigo_correlativo = "0".$correlativo; // ejemplo 099
                            break;
                        default:
                            $codigo_correlativo = $correlativo;
                            break;
                    }

                    $codigo = $idCuenta.$codigo_correlativo; //! codigo para subcuenta

                    $pruebacodigo = PartidaContable::find($codigo);
                    if($pruebacodigo !="")
                    {
                        //! volvemos a generar el correlativo
                        $correlativo = $correlativo +1;
                        $longitud_correlativo = strlen($correlativo);//longitud de cadena
                        $codigo_correlativo ="";
                        //evaluamos el largo de la cadena
                        switch ($longitud_correlativo) {
                            case 0:
                                $codigo_correlativo = "000".$correlativo; // ejemplo 000
                                break;
                            case 1:
                                $codigo_correlativo = "00".$correlativo; // ejemplo 009
                                break;
                            case 2:
                                $codigo_correlativo = "0".$correlativo; // ejemplo 099
                                break;
                            default:
                                $codigo_correlativo = $correlativo;
                                break;
                        }
                        $codigo = $idCuenta.$codigo_correlativo; //! codigo para cuenta
                    }

                    //! GUARDADO EN LA TABLA PLANDECUENTAS
                    $subCuenta = new PartidaContable();
                    $subCuenta->codigo = $codigo;
                    $subCuenta->descripcion = $descripcionSubCuenta;
                    $subCuenta->nivel = $nivel;
                    $subCuenta->correlativo = $correlativo;
                    $subCuenta->estado = 1;
                    $subCuenta->tipo_id = $idTipo; // relacion con plandecuentas con tipo
                    $subCuenta->save();

                    //! GUARDADO EN LA TABLA SUBCUENTA
                    DB::insert('INSERT INTO pc_sub_cuenta(id, codigo_partida, cuenta_id) VALUES (?,?,?)',[$codigo, $codigo, $idCuenta]);

                    session()->flash('crear_subcuenta','ok');
                    return redirect(route('plan-de-cuentas'));

            } catch(Exception $e){
                session()->flash('error_crear','error');
                return redirect(route('plan-de-cuentas'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    public function actualizarSubCuenta(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $nuevaDescripcion = $request->get('descripcionSubCuenta_mesc');// no convertimos en mayusculas

            $subCuenta = PartidaContable::find($id);
            $subCuenta->descripcion = $nuevaDescripcion;
            $subCuenta->save();

            session()->flash('actualizar_subcuenta','ok');
            return redirect(route('plan-de-cuentas'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('plan-de-cuentas'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
