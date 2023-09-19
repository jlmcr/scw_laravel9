<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\Tema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreferenciasUsuarioController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }
    //!

    public function index()
    {
        //MODIFICAMOS USUARIO
        $idUsuarioActivo = auth()->user()->id;

        $DatosUsuarioActivo = User::find($idUsuarioActivo);

        //! Busqueda de todas las empresas
        $empresas = Empresa::all(); //! filtramos solo  los ejercicios dados de alta
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all(); //! filtramos solo  los ejercicios dados de alta
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        $temas = Tema::all();

        return view('modulos.preferencias.index')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('DatosUsuarioActivo',$DatosUsuarioActivo)
        ->with('temas',$temas);

    }


    public function update(Request $request, $id)
    {
        //! se requiere tener permiso de edicion
        if($request->get('validador') == 'preferenciasUsuario')
        {
            if(auth()->user()->editar == 1)
            {
                $usuario = User::find($id);
                $usuario->mostrarBajas = $request->get('mostrarBajas');
                $usuario->save();
    
                session()->flash('actualizacion_preferencias_usuario','ok');
                return redirect()->route('preferencias-usuario.index');
            }
            else
            {
                session()->flash('acceso','denegado');
                return redirect(route('preferencias-usuario.index'));
            }
        }

        if($request->get('validador') == 'hora_fecha_en_pdf')
        {
            if(auth()->user()->editar == 1)
            {
                $usuario = User::find($id);
                $usuario->hora_fecha_en_reportes_pdf = $request->get('hora_fecha');
                $usuario->save();
    
                session()->flash('actualizacion_preferencias_usuario','ok');
                return redirect()->route('preferencias-usuario.index');
            }
            else
            {
                session()->flash('acceso','denegado');
                return redirect(route('preferencias-usuario.index'));
            }
        }

        //! puede modificar un usuario sin necesidad de tener permiso de edicion

        if($request->get('validador') == 'resaltar_inputs')
        {
            $usuario = User::find($id);
            $usuario->resaltar_inputs_rcv = $request->get('resaltarInputs');
            $usuario->save();

            session()->flash('actualizacion_preferencias_usuario','ok');
            return redirect()->route('preferencias-usuario.index');
        }

        if($request->get('validador') == 'tema')
        {
            $usuario = User::find($id);
            $usuario->tema_id = $request->get('tema');
            $usuario->save();

            session()->flash('actualizacion_preferencias_usuario','ok');
            return redirect()->route('preferencias-usuario.index');
        }
        //return "yo estube aqui";
    }

    public function aside(Request $request){
        $aux = $request->get('cambiar');

        if((Auth()->user()->colapsar_aside) == 0) {
            $user = User::find(Auth()->user()->id);
            $user->colapsar_aside = 1;
            $user->save();
        }
        else{
            $user = User::find(Auth()->user()->id);
            $user->colapsar_aside = 0;
            $user->save();
        }

        return response()->json([
            'cambiado' =>'cambiado'
        ]);
    }

}
