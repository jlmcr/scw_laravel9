<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rubro;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\ActivoFijo;
use App\Models\Depreciacion;
use App\Models\TipoDeCambio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepreciacionController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    //historial-depreciaciones
    public function index(Request $request)
    {
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $rubroSeleccionado = $request->get('id_rubro_buscado');

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


        //! depreciaciones para el HISTORIAL
        $depreciaciones = DB::table('depreciaciones')
                        ->join('activos_fijos','depreciaciones.activoFijo_id','=','activos_fijos.id')//tabla, primera, operador, segundo
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','activos_fijos.rubro_id','activos_fijos.empresa_id','ejercicios.ejercicioFiscal')
                        ->where('activos_fijos.empresa_id','=',$idEmpresaActiva) //activos de la empresa activa
                        ->orderBy('ejercicios.ejercicioFiscal','ASC')
                        ->get();


        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();

        return view('modulos.activoFijo.historialActivoFijo')
            ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
            ->with('rubros',$rubros) //! utilizado por el select del buscador
            ->with('rubroSeleccionado',$rubroSeleccionado) //! utilizado por el select del buscador
            ->with('rubro_buscado',$rubro_buscado) //! datos del rubro buscado o seleccionado
            ->with('activosFijosEncontrados',$activosFijosEncontrados) //! Tabla
            ->with('depreciaciones',$depreciaciones); //! depreciaciones para el HISTORIAL

           // return $depreciaciones;
    }

    public function nuevaDepreciacion_create(Request $request)
    {
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $idRubroSeleccionado = $request->get('id_rubro_buscado');
        $idEjercicioSeleccionado = $request->get('id_ejercicio_buscado');

        //! select
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


        //! select
        $ejercicios_de_la_empresa = Ejercicio::where('empresa_id','=',$idEmpresaActiva)
                        ->where('estado','=',1)
                        ->orderby('ejercicioFiscal','DESC')
                        ->get();

        //! previsualizar
        //! buscamos si hay depreciaciones en ese ejercicio

        //! cantidad de depreciaciones guardadas por empresa, rubro, ejercicio
        //para el encabezado de la tarjeta
        $cantidadDepreciaciones = DB::select('SELECT COUNT(*) AS cantidad FROM depreciaciones
        INNER JOIN activos_fijos ON activos_fijos.id = depreciaciones.activoFijo_id
        WHERE activos_fijos.empresa_id = ?
        AND activos_fijos.rubro_id =?
        AND depreciaciones.ejercicio_id = ? ',
        [$idEmpresaActiva, $idRubroSeleccionado, $idEjercicioSeleccionado]);

            //? $results = DB::select('select * from users where id = :id', ['id' => 1]);
            //? $users = DB::select('select * from users where active = ?', [1]);
            //? https://laravel.com/docs/9.x/database //devuelve un array


        //! datos para el cuadro de depreciacion
        $rubro_buscado_datos = Rubro::find($idRubroSeleccionado);
        $ejercicio_buscado_datos = Ejercicio::find($idEjercicioSeleccionado);

        $activosFijos_encontrados = DB::table('activos_fijos')
                        ->where('empresa_id','=',$idEmpresaActiva)
                        ->where('rubro_id','=',$idRubroSeleccionado)
                        ->orderBy('id','ASC')
                        ->get();
                        //->toSql();

        $depreciaciones_existentes_en_el_ejercicio = DB::table('depreciaciones')
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','ejercicios.ejercicioFiscal')
                        ->where('ejercicio_id','=',$idEjercicioSeleccionado)
                        ->orderBy('ejercicioFiscal','DESC')
                        ->get();
        $todas_las_depreciaciones_de_la_empresa = DB::table('depreciaciones')
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','ejercicios.ejercicioFiscal','ejercicios.empresa_id')
                        ->where('empresa_id','=',$idEmpresaActiva)
                        ->orderBy('ejercicioFiscal','DESC')
                        ->get();
        //! ufv
        $ufvs = TipoDeCambio::all();

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();

        return view('modulos.activoFijo.nuevaDepreciacion')
            ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
            ->with('rubros',$rubros) //! utilizado por el select del buscador
            ->with('idRubroSeleccionado',$idRubroSeleccionado) //! utilizado por el select del buscador
            ->with('rubro_buscado_datos',$rubro_buscado_datos) //! datos del rubro buscado o seleccionado
            ->with('activosFijos_encontrados',$activosFijos_encontrados) //! Para la Tabla de previsualizacion
            ->with('ejercicios_de_la_empresa',$ejercicios_de_la_empresa) //! para el buscador
            ->with('idEjercicioSeleccionado',$idEjercicioSeleccionado) //! para el buscador
            ->with('cantidadDepreciaciones',$cantidadDepreciaciones)
            ->with('ejercicio_buscado_datos',$ejercicio_buscado_datos) //! todos los datos del ejercicio seleccionado
            ->with('depreciaciones_existentes_en_el_ejercicio',$depreciaciones_existentes_en_el_ejercicio) //! usado para ver depreciaciones guardadas
            ->with('todas_las_depreciaciones_de_la_empresa',$todas_las_depreciaciones_de_la_empresa) //! usado para nueva depreciacion
            ->with('ufvs',$ufvs); //! usado para nueva depereciacio - reexpresiones

        //return $cantidadDepreciaciones[0]->cantidad;
        //return $ufvs->toArray();
    }


    public function store(Request $request)
    {
        $base = $request->get('valorInicialBien'); //para control de for do
        $check_reexpresar = $request->get('actualizar'); //boolean
        //return $check_reexpresar;

        //! arreglos
        $fechaInicial = $request->get('fechaInicial');
        $fechaFinal = $request->get('fechaFinal');

        /* valorInicialBien[]
        valorFinalBien[]
        depAcumInicial[]
        depAcFinal[] */
        $valorInicial_depr = $request->get('valorInicialBien');
        $valorFinal_depr = $request->get('valorFinalBien');
        $depAcumInicial_depr = $request->get('depAcumInicial');
        $depAcumFinal_depr = $request->get('depAcFinal');
        $meses = $request->get('meses');

        $aux_accion = $request->get('accion');
        $id_ActivoFijo = $request->get('id_ActivoFijo');
        $id_EjercicioContable = $request->get('id_EjercicioContable');
        $id_RubroSeleccionado = $request->get('id_RubroSeleccionado');
        $urlPagina = $request->get('urlPagina');

        // return $base;
        if($base == "")
        {
            session()->flash('guardadas_depreciaciones','sin datos');
            return redirect(url($urlPagina));
            //return "base vacia";
        }
        else{

            try {
                //code...
                if(count($base) > 0)
                {
                    for ($i=0; $i < count($base) ; $i++) {

                        $accion = explode('-',$aux_accion[$i]); /* separamos el id de la depreciacion para actualizaciones */

                        /*
                        se hace lo anterior por que en la accion se envía el id de la depreciacion existente
                        <input type="hidden" name="accion[]" value="actualizar-{{$depreciacion->id}}">
                        <input type="hidden" name="accion[]" value="crear">
                        */

                        if($accion[0] == "actualizar")
                        {
                            //!fecha inicial
                            $fi1 = explode('-', $fechaInicial[$i] ); //? tiene el siguiente formato -> aaaa/mm/dd
                            $fi2 = $fi1[2]."/".$fi1[1]."/".$fi1[0]; //d/m/Y
                            $finicial3 = Carbon::createFromFormat('d/m/Y', $fi2); //! la fecha con input date se genera: aaaa/mm/dd
                            //!fecha final
                            $ff1 = explode('-', $fechaFinal[$i] ); //? tiene el siguiente formato -> aaaa/mm/dd
                            $ff2 = $ff1[2]."/".$ff1[1]."/".$ff1[0]; //d/m/Y
                            $ffinal3 = Carbon::createFromFormat('d/m/Y', $ff2); //! la fecha con input date se genera: aaaa/mm/dd

                            //! value="actualizar-{{$depreciacion->id}}
                            $depreciacion = Depreciacion::find($accion[1]);
                            $depreciacion->fechaInicial = $finicial3; //fecha
                            $depreciacion->fechaFinal = $ffinal3; //fecha

                            if($check_reexpresar[$i] == 1){
                                $depreciacion->reexpresar = 1;
                            }
                            else{
                                $depreciacion->reexpresar = 0;
                            }

                            $depreciacion->meses = $meses[$i];

                            $depreciacion->valorInicial_depr = str_replace(",","", $valorInicial_depr[$i]);
                            $depreciacion->depAcumInicial_depr = str_replace(",","", $depAcumInicial_depr[$i]);
                            $depreciacion->valorFinal_depr = str_replace(",","", $valorFinal_depr[$i]);
                            $depreciacion->depAcumFinal_depr = str_replace(",","", $depAcumFinal_depr[$i]);

                            $depreciacion->save();
                        }
                        if($accion[0] == "crear")
                        {
                            //!fecha inicial
                            $fi1 = explode('-', $fechaInicial[$i] ); //? tiene el siguiente formato -> aaaa/mm/dd
                            $fi2 = $fi1[2]."/".$fi1[1]."/".$fi1[0]; //d/m/Y
                            $finicial3 = Carbon::createFromFormat('d/m/Y', $fi2); //! la fecha con input date se genera: aaaa/mm/dd
                            //!fecha final
                            $ff1 = explode('-', $fechaFinal[$i] ); //? tiene el siguiente formato -> aaaa/mm/dd
                            $ff2 = $ff1[2]."/".$ff1[1]."/".$ff1[0]; //d/m/Y
                            $ffinal3 = Carbon::createFromFormat('d/m/Y', $ff2); //! la fecha con input date se genera: aaaa/mm/dd

                            $depreciacion = new Depreciacion();
                            $depreciacion->fechaInicial = $finicial3; //fecha
                            $depreciacion->fechaFinal = $ffinal3; //fecha

                            if($check_reexpresar[$i] == 1){
                                $depreciacion->reexpresar = 1;
                            }
                            else{
                                $depreciacion->reexpresar = 0;
                            }

                            $depreciacion->meses = $meses[$i];
                            $depreciacion->valorInicial_depr = str_replace(",","", $valorInicial_depr[$i]);
                            $depreciacion->depAcumInicial_depr = str_replace(",","", $depAcumInicial_depr[$i]);
                            $depreciacion->valorFinal_depr = str_replace(",","", $valorFinal_depr[$i]);
                            $depreciacion->depAcumFinal_depr = str_replace(",","", $depAcumFinal_depr[$i]);
                            //relaciones
                            $depreciacion->activoFijo_id = $id_ActivoFijo[$i];
                            $depreciacion->ejercicio_id = $id_EjercicioContable[$i];
                            $depreciacion->save();
                        }
                    }

                    session()->flash('guardadas_depreciaciones','exitoso');
                    return redirect(url($urlPagina));
                    //return redirect('activo-fijo/historial-depreciaciones?id_rubro_buscado='.$id_RubroSeleccionado[0]);
                }
            } catch (\Throwable $th) {
                session()->flash('guardadas_depreciaciones','error');
                //return redirect(url($urlPagina));
                return redirect('activo-fijo/historial-depreciaciones?id_rubro_buscado='.$id_RubroSeleccionado[0]);
            }
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function consultaUfvAjax (Request $request)
    {
        $fecha = $request->get('fecha');
        $ufvs_consulta = TipoDeCambio::where('fecha','=',$fecha)->get();

        $datos=[];

        if($ufvs_consulta != "")
        {
            foreach($ufvs_consulta as $ufv)
            {
                $datos[]=[
                    'ufv'=>$ufv->ufv
                ];
            }
            return $datos;
        }
        else
        {
            //return "vacio";
            $datos[]=[
                'ufv'=>1
            ];
            return $datos;
        }
    }

    public function destroy($id)
    {
        //
    }
}
