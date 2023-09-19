<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionSistema;
use App\Models\Sucursal;
use App\Models\Empresa;
use App\Models\Ejercicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
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
        if(auth()->user()->mostrarBajas == 0)
        {
            //! Busqueda de sucursales
            //1 activo
            //0 baja

            $criterio = $request->get('id_denominacionSocial');
            $sucursalesEncontradas= DB::table('sucursals')
                ->where('empresa_id','=',$criterio)
                ->where('estado','=',1) //solo activos
                ->orderBy('estado','DESC')
                ->orderBy('id','ASC')
                ->paginate(5);
                $sucursalesEncontradas->withQueryString();//!Metodo para agregar a los link de paginacion los parametros enviados en la solicitud o URL actual


            //? con eloquent no funciona orwhere
            /* $variable= Sucursal::all()->where('empresa_id','=',$criterio)*/

        }
        elseif(auth()->user()->mostrarBajas == 1)
        {
            //! Busqueda de sucursales
            //no filtramos por estado

            $criterio = $request->get('id_denominacionSocial');
            $sucursalesEncontradas= DB::table('sucursals')
                ->where('empresa_id','=',$criterio)
                ->orderBy('estado','DESC')
                ->orderBy('id','ASC')
                ->paginate(5);
                $sucursalesEncontradas->withQueryString();//!Metodo para agregar a los link de paginacion los parametros enviados en la solicitud o URL actual

        }

        //! Busqueda de empresa Buscada
        $empresaBuscada=Empresa::all()->find($criterio); //? esta linea está solo para que exista la variable y no de error

        if($request->get('id_denominacionSocial') != "")
        {
            $empresaBuscada=Empresa::all()->find($criterio);
        }

        //! Busqueda de todas las empresas
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        return view('modulos.empresas.sucursales.index')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresas',$empresas) //  este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
        ->with('sucursalesEncontradas',$sucursalesEncontradas)
        ->with('empresaBuscada',$empresaBuscada);
        //return $empresaBuscada;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSucursalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            $sucursal = new Sucursal(); //* creamos un objeto del tipo modelo o clase Empresa
            $sucursal->empresa_id = $request->get('id_denominacionSocial');
            $sucursal->descripcion = $request->get('nombre');
            $sucursal->direccion = $request->get('direccion');
            $sucursal->estado = 1;//alta

            $sucursal->save();

            // mesaje de alerta
            session()->flash('crear','ok'); // se envia automaticamente

            //return redirect()->route('sucursales.index',$request->get('id_denominacionSocial'));
            return redirect('/sucursales?id_denominacionSocial='.$request->get('id_denominacionSocial'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('sucursales.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(Sucursal $sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSucursalRequest  $request
     * @param  \App\Models\Sucursal  $sucursal
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            if($request->get('validador') == 'DarDeAlta')
            {
                $sucursal = Sucursal::find($id);
                $sucursal->estado = 1; //damos de alta
                $sucursal->save();

                session()->flash('actualizar','ok');
                return redirect('/sucursales?id_denominacionSocial='.$sucursal->empresa_id);
            }
            else
            {
                /* actualizamos sucursal */
                $sucursal = Sucursal::find($id);
                $sucursal->descripcion = $request->get('nombre');
                $sucursal->direccion=$request->get('direccion');

                $sucursal->save();

                // mesaje de alerta
                session()->flash('actualizar','ok'); // se envia automaticamente

                return redirect('/sucursales?id_denominacionSocial='.$sucursal->empresa_id);
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('sucursales.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sucursal  $sucursal
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->eliminar == 1)
        {
            $sucursal = Sucursal::find($id); //* hacemos una consulta
            $idSuc = Sucursal::find($id); //* hacemos una consulta para usar su id
            $sucursal->estado = 0;
            $sucursal->save();

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('/sucursales?id_denominacionSocial='.$idSuc->empresa_id );
            //return redirect(request()->fullUrl());
            //return $suc;
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('sucursales.index'));
        }
    }

}
