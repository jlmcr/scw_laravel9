<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionSistema;
use App\Models\Ejercicio;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EjercicioController extends Controller
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
        //! Busqueda de Ejercicios contables
        $criterio = $request->get('id_denominacionSocial');

        if(auth()->user()->mostrarBajas == 0)
        {
            //mostramos solo altas - no bajas
            //predeterminado
            //1 activo
            //0 baja

            $ejerciciosEncontrados= DB::table('ejercicios')
                ->where('estado','=',1)
                ->where('empresa_id','=',$criterio)
                ->orderBy('estado','DESC')
                ->orderBy('ejercicioFiscal','DESC')
                ->paginate(5);
            $ejerciciosEncontrados->withQueryString();//!Metodo para agregar a los link de paginacion los parametros enviados en la solicitud o URL actual


            /*$ejerciciosEncontrados= DB::table('ejercicios')
            ->where('estado','=',1)
            ->where('empresa_id','=',$criterio)
            ->orderBy('fechaCierre','DESC')
            ->get(); */

        }
        elseif(auth()->user()->mostrarBajas == 1)
        {
            //mostramos todos - altas y bajas
            //mostramos bajas deacuerdo a la configuracion en la tabla usuario
            //no filtramos por estado

           /*  $ejerciciosEncontrados= Ejercicio::paginate(2); */

            $ejerciciosEncontrados= DB::table('ejercicios')
            ->where('empresa_id','=',$criterio)
            ->orderBy('estado','DESC')
            ->orderBy('fechaCierre','DESC')
            ->paginate(5);
            $ejerciciosEncontrados->withQueryString();//!Metodo para agregar a los link de paginacion los parametros enviados en la solicitud o URL actual

            /* $ejerciciosEncontrados= DB::table('ejercicios')
            ->where('empresa_id','=',$criterio)
            ->orderBy('fechaCierre','DESC')
            ->get(); */
        }

        //! Busqueda de empresa Buscada
        $empresaBuscada=Empresa::all()->find($criterio); //? esta linea está solo apra que exista la variable y no de error

        if($request->get('id_denominacionSocial') != "")
        {
            $empresaBuscada=Empresa::all()->find($criterio);
        }

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! Año minimo
        $confSistema = ConfiguracionSistema::select('anioMinimo')->first();
        $anioMinimo = $confSistema->anioMinimo;
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        return view('modulos.empresas.ejercicios.index')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejerciciosEncontrados',$ejerciciosEncontrados)
        ->with('empresaBuscada',$empresaBuscada)
        ->with('anioMinimo',$anioMinimo);

        //return $empresaBuscada;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            //! manipulamos fechas
            // Como ven, estamos utilizando el método createFromFormat de la clase Carbon para modificar el formato de la fecha de vencimiento que recibimos por el formulario.
            //https://www.laraveltip.com/trabajando-con-formatos-de-fechas-en-laravel-y-jquery-ui-datepicker/#:~:text=Como%20ven%2C%20estamos%20utilizando%20el%20m%C3%A9todo%20createFromFormat%20de%20la%20clase%20Carbon%20para%20modificar%20el%20formato%20de%20la%20fecha%20de%20vencimiento%20que%20recibimos%20por%20el%20formulario.
            //https://www.laraveltip.com/trabajando-con-formatos-de-fechas-en-laravel-y-jquery-ui-datepicker/

            $fecha1 = Carbon::createFromFormat('d/m/Y', $request->get('fechaInicio'));
            $fecha2 = Carbon::createFromFormat('d/m/Y', $request->get('fechaCierre'));
            //https://styde.net/componente-carbon-fechas-laravel-5/
            //valida si una fecaha existe - mes, el día y el año

            $f1 = explode('/',$request->get('fechaInicio'));  //separamos la fecha en partes - lo guarda en un arreglo
            $f2 = explode('/',$request->get('fechaCierre'));

            if(count($f1) == 3 && checkdate($f1[1], $f1[0], $f1[2])==true && count($f2) == 3 && checkdate($f2[1], $f2[0], $f2[2])==true)
            {
                // En caso que no lo sepas, Laravel incluye Carbon, un wrapper de DateTime muy potente, y puedes crear una instancia de fecha fácilmente, utilizando el método createFromFormat:
                // Carbon::createFromFormat('d/m/Y', '31/01/2019');
                // El resultado es una fecha que se puede almacenar directamente en una base de datos típica.
                //---
                // Con PHP usa strtotime() de la siguiente forma
                // Le pasas el string
                //  $fecha = strtotime('31/01/2019');
                // Le das el formato que necesitas a la fecha
                // $newformat = date('Y-m-d',$fecha );
                // echo $newformat;
                //  2019-01-31

                $ejercicio = new Ejercicio(); //* creamos un objeto del tipo modelo o clase Empresa
                $ejercicio->fechaInicio = $fecha1;
                $ejercicio->fechaCierre = $fecha2;
                $ejercicio->ejercicioFiscal = $request->get('ejercicioFiscal');
                $ejercicio->empresa_id = $request->get('id_denominacionSocial');
                $ejercicio->estado=1; //alta
                $ejercicio->save();

                session()->flash('crear','ok');
                return redirect('/ejercicios?id_denominacionSocial='. $request->get('id_denominacionSocial'));
            }
            else
            {
                session()->flash('errorFecha','error');
                return redirect('/ejercicios?id_denominacionSocial='. $request->get('id_denominacionSocial'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('ejercicios.index'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
            if($request->get('validador') == 'DarDeAlta')
            {
                $ejercicio = Ejercicio::find($id);
                $ejercicio->estado = 1; //damos de alta a un ejercicio que se haya dado de baja
                $ejercicio->save();

                session()->flash('actualizar','ok');
                return redirect('/ejercicios?id_denominacionSocial='.$ejercicio->empresa_id);
            }
            else
            {
                //* manipulamos fechas
                $fecha1 = Carbon::createFromFormat('d/m/Y', $request->get('fechaInicio'));
                $fecha2 = Carbon::createFromFormat('d/m/Y', $request->get('fechaCierre'));

                $f1 = explode('/',$request->get('fechaInicio'));  //separamos la fecha en partes - lo guarda en un arreglo
                $f2 = explode('/',$request->get('fechaCierre'));

                if(count($f1) == 3 && checkdate($f1[1], $f1[0], $f1[2])==true && count($f2) == 3 && checkdate($f2[1], $f2[0], $f2[2])==true)
                {
                    $ejercicio = Ejercicio::find($id);
                    $ejercicio->fechaInicio = $fecha1;
                    $ejercicio->fechaCierre = $fecha2;
                    $ejercicio->ejercicioFiscal = $request->get('ejercicioFiscal');

                    $ejercicio->save();

                    session()->flash('actualizar','ok');
                    return redirect('/ejercicios?id_denominacionSocial='.$ejercicio->empresa_id);
                    //return $ejercicio;
                }
                else
                {
                    $ejercicio = Ejercicio::find($id);
                    session()->flash('errorFecha','error');
                    return redirect('/ejercicios?id_denominacionSocial='.$ejercicio->empresa_id);
                }
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('ejercicios.index'));
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
            $ejercicio = Ejercicio::find($id); //* hacemos una consulta
            $idEjerc = Ejercicio::find($id); //* hacemos una consulta para usar el id de la empresa
            /* damos de baja */
            $ejercicio->estado = 0;
            $ejercicio->save();

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('/ejercicios?id_denominacionSocial='.$idEjerc->empresa_id );
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('ejercicios.index'));
        }
    }
}
