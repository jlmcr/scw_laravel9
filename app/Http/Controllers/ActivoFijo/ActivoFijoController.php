<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivoFijo;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\Rubro;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivoFijoController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $rubroSeleccionado = $request->get('id_rubro_buscado');

        if($rubroSeleccionado == '-1')
        {
            $rubro_buscado = "todos";

            //! para el select del buscador
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

            /* $activosFijosEncontrados = DB::table('activos_fijos')
                            ->where('empresa_id','=',$idEmpresaActiva)
                            ->where('rubro_id','<>',null)
                            ->orderBy('id','ASC')
                            ->get(); */
                            //->toSql();
            //!con eloquente (con los modelos) probamos las relaciones desde los modelos
            $activosFijosEncontrados = ActivoFijo::where('empresa_id','=',$idEmpresaActiva)
                            ->where('rubro_id','<>',null)
                            ->orderBy('id','ASC')
                            ->get();
        }
        else
        {
            //?$rubroBuscado = Rubro::where('id','=','$rubroSeleccionado')->get();
            $rubro_buscado = Rubro::find($rubroSeleccionado);

            //! para el select del buscador
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

            $activosFijosEncontrados = DB::table('activos_fijos')
                            ->where('empresa_id','=',$idEmpresaActiva)
                            ->where('rubro_id','=',$rubroSeleccionado)
                            ->orderBy('id','ASC')
                            ->get();
                            //->toSql();
        }

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        //return $rubros;
        return view('modulos.activoFijo.activoFijoListado')
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('rubros',$rubros) //! utilizado por el select del buscador
        ->with('rubroSeleccionado',$rubroSeleccionado) //! utilizado por el select del buscador
        ->with('rubro_buscado',$rubro_buscado) //! datos del rubro buscado o seleccionado
        ->with('activosFijosEncontrados',$activosFijosEncontrados); //! Tabla

        //return $activosFijosEncontrados;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            $rubro_id = $request->get('id_rubro_nuevo_activo');
            $empresa_id = $request->get('id_empresa_activa'); //? esta vez no usamos el auth user desde el controlador, el dato de la empresa activa lo traemos desde la vista

            if($request->get('id_empresa_activa') != "") // solo si tenemos una empresa activa
            {
                //! CODIGO
                //!1 ultimo correlativo de activo fijo  del rubro en la empresa
                $ultimoCorrelativo = DB::table('activos_fijos')
                                    ->where('empresa_id','=',$empresa_id)
                                    ->where('rubro_id','=',$rubro_id)
                                    ->max('correlativo');

                //!2 analizamos parte del codigo correspondiente al rubro
                $aux_rubro_longitud = strlen($rubro_id);//longitud de cadena
                $correlativo = $ultimoCorrelativo + 1;
                $aux_corel_longitud = strlen($correlativo);//longitud de cadena

                //? evaluamos el largo de la cadena
                switch ($aux_rubro_longitud) {
                    case 0:
                        $auxCodRubro = "00".$rubro_id; // ejemplo 00
                        break;
                    case 1:
                        $auxCodRubro = "0".$rubro_id; // ejemplo 09
                        break;
                    /* case 2:
                        $auxCodRubro = "0".$rubro_id; // para 2 digitos en el codigo: vacio ceros "" //?va rebajando los ceros
                        break; */
                    default:
                        $auxCodRubro = $rubro_id;
                        break;
                }

                //!3 analizamos parte del codigo correspondiente al CORRELATIVO del activo
                $ceros = "0";
                $auxCodigoCorrelativo = $correlativo;
                while($aux_corel_longitud <4)
                {
                    $auxCodigoCorrelativo = $ceros.$correlativo;//cadena 00+1

                    $ceros = $ceros."0";//aumentamos cero
                    $aux_corel_longitud = strlen($auxCodigoCorrelativo);//longitud de cadena
                    // ultimo resultado 0001
                }

                $codigoGenerado = $auxCodRubro."-".$empresa_id.$auxCodigoCorrelativo;

                //! 4 verificamos que no exista otro id igual
                $pruebaDuplicidad_Activo = ActivoFijo::find($codigoGenerado);

                //*empty() determina si una variable está vacía
                // null funciona con arreglos, el "" no me da un resultado buscado en arrays
                if($pruebaDuplicidad_Activo != null)
                {
                    $codigoGenerado = $auxCodRubro."-".$empresa_id.$ceros.($correlativo+1);
                }

                //GUARDAMOS
                $activo = new ActivoFijo();
                $activo->id = $codigoGenerado;
                $activo->activoFijo = strtoupper($request->get('nombre'));
                $activo->cantidad = $request->get('cantidad');
                $activo->medida = strtoupper($request->get('medida'));
                $activo->valorInicial = str_replace(",","",$request->get('valorInicial')); //number_format no funciona
                $activo->depAcumInicial = str_replace(",","",$request->get('depAcumInicial'));
                $activo->situacion = $request->get('situacion');
                $activo->estadoAF = $request->get('estadoAF');
                //fecha
                $auxFecha = explode('/',$request->get('fechaRegistro'));
                if(count($auxFecha) == 3 && checkdate($auxFecha[1], $auxFecha[0], $auxFecha[2]) == true) //m d a
                {
                    $fecha = Carbon::createFromFormat('d/m/Y', $request->get('fechaRegistro'));
                    $activo->fechaCompraRegistro = $fecha;
                }
                //
                $activo->documento = $request->get('documento');
                $activo->numeroDocumento = $request->get('numeroDoc');
                $activo->correlativo = $correlativo;
                $activo->empresa_id = $empresa_id;
                $activo->rubro_id = $rubro_id;
                $activo->save();

                session()->flash('crear','ok');
                return redirect('/activoFijo/?id_rubro_buscado='.$rubro_id);

                //$p=strlen("123");
                //return $codigoGenerado;
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('activoFijo.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            //ACTUALIZAMOS
            $activo = ActivoFijo::find($id);
            $activo->activoFijo = strtoupper($request->get('nombre'));
            $activo->cantidad = $request->get('cantidad');
            $activo->medida = strtoupper($request->get('medida'));
            $activo->valorInicial = str_replace(",","",$request->get('valorInicial')); //number_format no funciona
            $activo->depAcumInicial = str_replace(",","",$request->get('depAcumInicial'));
            $activo->situacion = $request->get('situacion');
            $activo->estadoAF = $request->get('estadoAF');
            //fecha
            $auxFecha = explode('/',$request->get('fechaRegistro'));
            if(count($auxFecha) == 3 && checkdate($auxFecha[1], $auxFecha[0], $auxFecha[2]) == true) //m d a
            {
                $fecha = Carbon::createFromFormat('d/m/Y', $request->get('fechaRegistro'));
                $activo->fechaCompraRegistro = $fecha;
            }
            else
            {
                $activo->fechaCompraRegistro = null;
            }
            //
            $activo->documento = $request->get('documento');
            $activo->numeroDocumento = $request->get('numeroDoc');
            $activo->save();

            session()->flash('actualizar','ok');
            return redirect('/activoFijo/?id_rubro_buscado='.$activo->rubro_id);
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('activoFijo.index'));
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
        if(auth()->user()->eliminar == 1)
        {
            //https://cybmeta.com/como-obtener-la-url-actual-en-php#:~:text=%24_SERVER%5B'REQUEST_URI'%5D,bar%3Dfoo%20.
            //$uri = $_SERVER["REQUEST_URI"];
            //?es recomendable usarlo en la vista o para recuperar query string (valores enviados por url)


            $auxiliar = ActivoFijo::find($id);

            $activofijo = ActivoFijo::find($id);
            $activofijo->delete();

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('/activoFijo?id_rubro_buscado='.$auxiliar->rubro_id);
            //return redirect($uri);// en este caso no funciona por que toma la url de la ruta destroy y no index (la antes de enviar)
            //?es recomendable usarlo en la vista o para recuperar query string (valores enviados por url)
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('activoFijo.index'));
        }
    }
}
