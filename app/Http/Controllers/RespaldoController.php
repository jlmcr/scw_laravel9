<?php

namespace App\Http\Controllers;

use App\Models\Ejercicio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RespaldoController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        //! Busqueda de todas las empresas
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        return view('respaldo.index')
            ->with('sub_cuentas',$sub_cuentas) //para modal mayores
            ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios); // este es para el nombre y ejercicio de la empresa activa
    }

    public function respaldar(Request $request)
    {

        //! validando clave actual
        // https://styde.net/cambiar-contrasena-con-laravel-validando-clave-actual/
        //! cifrado de laravel
        // https://laravel.com/docs/9.x/encryption#main-content

        try {
            $clave_ingresada = $request->get('pas');
            $clave_desarrollador = $request->get('des');

            //comparamos contraseñas
            $resultado = Hash::check( $clave_ingresada, auth()->user()->password); //compara: devuelve 1 o vacio 
            //! Auth::user()->password   da un error

            if($resultado == 1 && $clave_desarrollador == '1292519312925193')
            {
                //! https://parzibyte.me/blog/2022/10/05/exportar-base-datos-mysql-laravel/      en local
                //! https://parzibyte.me/blog/2018/10/22/script-respaldar-base-de-datos-mysql-php/     en servidor

                //$ubicacionArchivoTemporal = getcwd() . DIRECTORY_SEPARATOR . "Respaldo_" . uniqid(date("Y-m-d") . "_", true) . ".sql";
                $ubicacionArchivoTemporal = getcwd() . DIRECTORY_SEPARATOR . "back". DIRECTORY_SEPARATOR . "Respaldo_" . uniqid(date("Y-m-d") . "_", true) . ".sql";
            
                
                $salida = "";
                $codigoSalida = 0;
                $comando = sprintf("%s --user=\"%s\" --password=\"%s\" %s > %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"), $ubicacionArchivoTemporal);
                exec($comando, $salida, $codigoSalida);

                if ($codigoSalida !== 0) {

                    //return "Código de salida distinto de 0, se obtuvo código (" . $codigoSalida . "). Revise los ajustes e intente de nuevo";
                    
                    session()->flash('respaldo','error al respaldar');
                    return redirect(route('respaldar.index'));
                }

                return response()->download($ubicacionArchivoTemporal);

            }
            else
            {
                session()->flash('respaldo','credenciales incorrectos');
                return redirect(route('respaldar.index'));
            }

        } catch (\Throwable $th) {
            session()->flash('respaldo','error al respaldar');
            return redirect(route('respaldar.index'));
        }
    }

    public function respaldoAutomatico(Request $request)
    {
        //! https://parzibyte.me/blog/2022/10/05/exportar-base-datos-mysql-laravel/      en local
        //! https://parzibyte.me/blog/2018/10/22/script-respaldar-base-de-datos-mysql-php/     en servidor

        try {
            
            $dia = date('d');
            // return $dia; $dia =='01'


            if ($dia =='07' || $dia =='14' || $dia =='21' || $dia =='28') {
                
                $ubicacionArchivoTemporal = getcwd() . DIRECTORY_SEPARATOR . "back". DIRECTORY_SEPARATOR . "Respaldo_" . uniqid(date("Y-m-d") . "_", true) . ".sql";
        
                $salida = "";
                $codigoSalida = 0;
                $comando = sprintf("%s --user=\"%s\" --password=\"%s\" %s > %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"), $ubicacionArchivoTemporal);
                exec($comando, $salida, $codigoSalida);
        
                if ($codigoSalida !== 0) {
        
                    //return "Código de salida distinto de 0, se obtuvo código (" . $codigoSalida . "). Revise los ajustes e intente de nuevo";
                    
                    session()->flash('respaldo','error al respaldar');
                    return redirect(route('respaldar.index'));
                }
        
                return response()->download($ubicacionArchivoTemporal);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
