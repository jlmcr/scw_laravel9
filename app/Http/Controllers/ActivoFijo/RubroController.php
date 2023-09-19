<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rubro;
use App\Models\Empresa;
use App\Models\Ejercicio;
use Illuminate\Support\Facades\DB;

class RubroController extends Controller
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
    public function index()
    {
        //$rubros = rubro::all();
        //rubros y su cantidad de activos en todo el sistema
        $rubros = DB::select("SELECT
            rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo,
            rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
            COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
            FROM rubros
            LEFT JOIN activos_fijos ON rubros.id = activos_fijos.rubro_id
            GROUP BY
            rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo,
            rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion");

        //return $rubros;


        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        return view('modulos.activoFijo.rubros')
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('sub_cuentas',$sub_cuentas) //! para modal mayores y para la CREACION
        ->with('rubros',$rubros);
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
            $rubro = new Rubro();
            $rubro->rubro = strtoupper($request->get('rubro'));
            $rubro->aniosVidaUtil = $request->get('aniosVidaUtil');
            $rubro->codCntaActivo = $request->get('codCntaActivo');
            $rubro->codCntaDepreciacion = $request->get('codCntaDepreciacion');
            $rubro->codCntaDepreciacionAcum = $request->get('codCntaDepreciacionAcum');
            if($request->get('sujetoAdepreciacion') == true)
            {
                $rubro->sujetoAdepreciacion = 1;
            }
            else
            {
                $rubro->sujetoAdepreciacion = 0;
            }
            $rubro->save();

            session()->flash('crear','ok');
            return redirect('/rubrosActivoFijo');
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('rubrosActivoFijo.index'));
        }
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
            $rubro = Rubro::find($id);
            $rubro->rubro = strtoupper($request->get('rubro'));
            $rubro->aniosVidaUtil = $request->get('aniosVidaUtil');
            $rubro->codCntaActivo = $request->get('codCntaActivo');
            $rubro->codCntaDepreciacion = $request->get('codCntaDepreciacion');
            $rubro->codCntaDepreciacionAcum = $request->get('codCntaDepreciacionAcum');
            if($request->get('sujetoAdepreciacion') == true)
            {
                $rubro->sujetoAdepreciacion = 1;
            }
            else
            {
                $rubro->sujetoAdepreciacion = 0;
            }
            $rubro->save();

            session()->flash('actualizar','ok');
            return redirect('/rubrosActivoFijo');
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('rubrosActivoFijo.index'));
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
        //! se permite eliminar solo si no tiene más activos
        if(auth()->user()->eliminar == 1)
        {
            $rubro = Rubro::find($id);
            $rubro->delete();

            session()->flash('eliminar','ok'); //* variable de sesion
            return redirect('/rubrosActivoFijo');
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('rubrosActivoFijo.index'));
        }
    }
}
