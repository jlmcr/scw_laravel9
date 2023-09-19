<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\TipoDeComprobante;
use Illuminate\Support\Facades\DB;

class TipoDeComprobanteController extends Controller
{

    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if(auth()->user()->mostrarBajas == 0)
        {
            //1 activos

            //sql = SELECT tipos_de_comprobante.*, COUNT(comprobantes.tipoComprobante_id) AS cantidad FROM tipos_de_comprobante LEFT JOIN comprobantes ON comprobantes.tipoComprobante_id = tipos_de_comprobante.id GROUP BY tipos_de_comprobante.id

            //nota:
            //al parecer eloQuent exige la agrupacion de todos los campos mostrado en INNER JOIN
            // en busqueda de compras no ecige agrupacion pero es solo una tabla

            $tiposComprobantes = DB::table('tipos_de_comprobante')
            ->select(DB::raw('tipos_de_comprobante.id, tipos_de_comprobante.nombre, tipos_de_comprobante.color,tipos_de_comprobante.estado , count(comprobantes.tipoComprobante_id) as cantidad'))
            ->leftJoin('comprobantes','comprobantes.tipoComprobante_id','=','tipos_de_comprobante.id')
            ->where('tipos_de_comprobante.estado', '=', 1)
            ->groupBy('tipos_de_comprobante.id')
            ->groupBy('tipos_de_comprobante.nombre')
            ->groupBy('tipos_de_comprobante.color')
            ->groupBy('tipos_de_comprobante.estado')
            ->get();
            //SQL
            //select tipos_de_comprobante.id, tipos_de_comprobante.nombre, tipos_de_comprobante.color,tipos_de_comprobante.estado , count(comprobantes.tipoComprobante_id) as cantidad from `tipos_de_comprobante` left join `comprobantes` on `comprobantes`.`tipoComprobante_id` = `tipos_de_comprobante`.`id` where `tipos_de_comprobante`.`estado` = ? group by `tipos_de_comprobante`.`id`, `tipos_de_comprobante`.`nombre`, `tipos_de_comprobante`.`color`, `tipos_de_comprobante`.`estado`
        }
        elseif(auth()->user()->mostrarBajas == 1)
        {
            //no filtramos por estado

            $tiposComprobantes = DB::table('tipos_de_comprobante')
            ->select(DB::raw('tipos_de_comprobante.id, tipos_de_comprobante.nombre, tipos_de_comprobante.color,tipos_de_comprobante.estado , count(comprobantes.tipoComprobante_id) as cantidad'))
            ->leftJoin('comprobantes','comprobantes.tipoComprobante_id','=','tipos_de_comprobante.id')
            ->groupBy('tipos_de_comprobante.id')
            ->groupBy('tipos_de_comprobante.nombre')
            ->groupBy('tipos_de_comprobante.color')
            ->groupBy('tipos_de_comprobante.estado')
            ->get();
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

        return view('modulos.contabilidad.comprobante.tipo-comprobante')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('tiposComprobantes',$tiposComprobantes);

        // return $tiposComprobantes;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            $tipoCompr = new TipoDeComprobante();
            $tipoCompr->nombre = strtoupper($request->get('nombre'));
            $tipoCompr->color = $request->get('color');
            $tipoCompr->estado = 1;
            $tipoCompr->save();
            session()->flash('crear','ok');
            return redirect(route('tipo-comprobante.index'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('tipo-comprobante.index'));
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

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {

            if($request->get('validador') == 'DarDeAlta')
            {
                $tipoCompr = TipoDeComprobante::find($id);
                $tipoCompr->estado=1;
                $tipoCompr->save();

                session()->flash('actualizar','ok');
                return redirect(route('tipo-comprobante.index'));
            }
            else
            {
                //actualizar
                $tipoCompr = TipoDeComprobante::find($id);
                $tipoCompr->nombre = strtoupper($request->get('nombre'));
                $tipoCompr->color = $request->get('color');
                $tipoCompr->save();

                session()->flash('actualizar','ok');
                return redirect(route('tipo-comprobante.index'));
            }

        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('tipo-comprobante.index'));
        }
    }

    public function destroy($id)
    {
        if(auth()->user()->eliminar == 1)
        {
            $tipoCompr = TipoDeComprobante::find($id);
            $tipoCompr->estado=0;
            $tipoCompr->save();

            session()->flash('eliminar','ok');
            return redirect(route('tipo-comprobante.index'));

        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('sucursales.index'));
        }
    }
}
