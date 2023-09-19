<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\ConfiguracionSistema;

class ConfiguracionSistemaController extends Controller
{

    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }
    //!

    public function index()
    {
        //solo el ingreso para Administrador y Contador
        if(auth()->user()->rol != "Administrador" && auth()->user()->rol != "Contador")
        {
            return redirect(route('dashboard.index'));
        }

        //MODIFICAMOS USUARIO
        $sistema = ConfiguracionSistema::first();

        //! Busqueda de todas las empresas
        $empresas = Empresa::all(); //! filtramos solo  los ejercicios dados de alta
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all(); //! filtramos solo  los ejercicios dados de alta


        return view('modulos.sistema.index')
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('sistema',$sistema);
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            if($request->get('validador') == 'mensaje')
            {
                $configuracion = ConfiguracionSistema::find($id);
                $configuracion->mensajeWhatsapp = $request->get('mensaje');
                $configuracion->save();

                session()->flash('actualizar','ok');
                return redirect()->route('configuracion-sistema.index');
            }
            if($request->get('validador') == 'anioMinimoPermitido')
            {
                $configuracion = ConfiguracionSistema::find($id);
                $configuracion->anioMinimo = $request->get('gestion');
                $configuracion->save();

                session()->flash('actualizar','ok');
                return redirect()->route('configuracion-sistema.index');
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('configuracion-sistema.index'));
        }
    }
}
