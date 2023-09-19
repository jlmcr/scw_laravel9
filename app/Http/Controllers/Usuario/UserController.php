<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Ejercicio;
use Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        //! Busqueda de todas las empresas
        $empresas = Empresa::all(); //! filtramos solo  los ejercicios dados de alta
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all(); //! filtramos solo  los ejercicios dados de alta
        
            //solo el ingreso para administrador
            if(auth()->user()->rol != "Administrador")
            {
                return redirect(route('dashboard.index'));
            }

        $users = User::where('estado','=',1)->get();

        return view('modulos.usuarios.usuarios')
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('users',$users);
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
            // * verificamos que no haya duplicidad
            $this->validate($request,[
                'email'=>'required|unique:users',
            ]);
            $usuario = new User();
            $usuario->estado = 1;
            $usuario->name = $request->get('nombres');
            /* $usuario->primer_apellido = $request->get('primer_apellido');
            $usuario->segundo_apellido = $request->get('segundo_apellido'); */
            $usuario->email = $request->get('email');

            $value = $request->get('password');
            $usuario->password = Hash::make($value);

            $usuario->acceso = $request->get('acceso');
            $usuario->rol = $request->get('rol');

            $rol = $request->get('rol');
            if($rol  == "Administrador" || $rol  == "Contador" || $rol  == "Auxiliar Contable")
            {
                $usuario->crear = 1;
                $usuario->editar = 1;
                $usuario->eliminar = 1;
            }
            else
            {
                $usuario->crear = 0;
                $usuario->editar = 0;
                $usuario->eliminar = 0;
            }
            $usuario->save();

            session()->flash('crear','ok');
            return redirect(route('usuarios.index'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('usuarios.index'));
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

        if($request->get('validador') == 'EmpresaEjercicio')
        {
            $empresaNueva = $request->get('empresaNueva');
            $ejercicioNuevo = $request->get('ejercicioNuevo');

            $usuario = User::find($id);
            $usuario->idEmpresaActiva = $empresaNueva;
            $usuario->idEjercicioActivo = $ejercicioNuevo;
            $usuario->save();
            session()->flash('actualizacion_empresa_ejercicio','ok');
            //return view('dashboard.index'); no funciona
            return redirect()->route('dashboard.index');
        }

        if(auth()->user()->editar == 1)
        {
            if($request->get('validador') == 'datosUsuario')
            {
                // * no verificamos que duplicidad

                $usuario = User::find($id);
                $usuario->name = $request->get('nombres');
                /* $usuario->primer_apellido = $request->get('primer_apellido');
                $usuario->segundo_apellido = $request->get('segundo_apellido'); */
                $usuario->email = $request->get('email');

                if($request->get('password') != "")
                {
                    $this->validate($request,[
                        'password' => ['string', 'min:8']
                    ]);

                    if($request->get('password') == $request->get('password_conf'))
                    {
                        $value = $request->get('password');
                        $usuario->password = Hash::make($value);
                    }
                    else
                    {
                        session()->flash('igualdad_contr','error');
                        return redirect(route('usuarios.index'));
                        exit;
                    }
                }

                $usuario->acceso = $request->get('acceso');
                $usuario->rol = $request->get('rol');

                if($request->get('crear') == true)
                {
                    $usuario->crear = 1;
                }
                else
                {
                    $usuario->crear = 0;
                }

                if($request->get('editar') == true)
                {
                    $usuario->editar = 1;
                }
                else
                {
                    $usuario->editar = 0;
                }

                if($request->get('eliminar') == true)
                {
                    $usuario->eliminar = 1;
                }
                else
                {
                    $usuario->eliminar = 0;
                }

                $usuario->save();

                session()->flash('actualizar','ok');
                return redirect(route('usuarios.index'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('usuarios.index'));
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
            $usuario = User::find($id); //* hacemos una consulta
            $usuario->estado = 0;
            $usuario->email = "dado_de_baja_".$usuario->email;
            $usuario->save();

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect(route('usuarios.index'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('usuarios.index'));
        }
    }
}
