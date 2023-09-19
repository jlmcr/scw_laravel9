<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa; //* usamos su modelo para traer datos
use App\Models\Sucursal;
use App\Models\Ejercicio;
use App\Models\ConfiguracionSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
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
        //!traemos datos con el modelo, lo almacenamos y lo enviamos a la vista
        //* VariableAlmacenadora =  NombreMetodo::all() ->nos trae todos los registros

        //!para la lista de las empresas en el sistema
        if(auth()->user()->mostrarBajas == 0)
        {
            //no mostrar bajas
            $empresasLista = Empresa::all()
            ->where('estado','=',1);
        }
        elseif(auth()->user()->mostrarBajas == 1)
        {
            //mostrar bajas y altas
            $empresasLista = Empresa::all();
        }

        $cantEmpr = DB::table('empresas')->where('estado','=',1)->count();
        $cantSuc = DB::table('sucursals')->where('estado','=',1)->count();
        $cantEjer = DB::table('ejercicios')->where('estado','=',1)->count();

        //!para el nombre de la empresa activa
        //! Busqueda de todas los ejercicios- solo activos lo filtramos en la vista
        $empresas = Empresa::all();
        $ejercicios = Ejercicio::all();
        /*$empresas = Empresa::all()->where('estado','=',1);
        $ejercicios = Ejercicio::all()->where('estado','=',1); */

        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        //*lo enviamos a la vista
        //* con el metodo with( nombreVariable,variableAlmacenadora)
        return view('modulos.empresas.index')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresasLista',$empresasLista) //? este es usado por la vista index
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('cantEmpr',$cantEmpr)
        ->with('cantSuc',$cantSuc)
        ->with('cantEjer',$cantEjer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->crear == 1)
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

            return view('modulos.empresas.create')
            ->with('sub_cuentas',$sub_cuentas) //para modal mayores
            ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios); // este es para el nombre y ejercicio de la empresa activa
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(url('/empresas'));
        }
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
                'nit'=>'numeric|required|unique:empresas',
            // 'rutaNit'=>'mimes:pdf,jpg,jepg,png,svg|max:1024'
            // 'cedula' =>  'numeric|required|unique:personas|digits_between:6,8',
            ]);

            /* creamos empresa */
            $empresas = new Empresa(); //* creamos un objeto del tipo modelo o clase Empresa
            $empresas->nit = $request->get('nit');
            $empresas->denominacionSocial = $request->get('denominacionSocial');
            $empresas->sociedadTipo = $request->get('sociedadTipo');
            $empresas->actividad = strtoupper($request->get('actividad'));
            $empresas->representanteLegal =	strtoupper($request->get('representanteLegal'));
            $empresas->ci = $request->get('ci');
            $empresas->complemento = $request->get('complemento');
            $empresas->extension = $request->get('extension');
            $empresas->celular = $request->get('celular');
            $empresas->correo = $request->get('correo');
            $empresas->clasificacion = $request->get('clasificacion');

                /*  NIT -- Subimos el documento pdf */
                if($nitpdf = $request->file('rutaNit'))
                {
                    $request->validate(
                        [
                            'rutaNit'=>'mimes:pdf,png,jpg,jpeg'
                        ]
                    );
                    $rutaGuardarNit='storage/empresas/nit/';
                    $docNombreNit = date('YmdHis'). "-nit". $request->get('nit') .".". $nitpdf->getClientOriginalExtension();
                    $nitpdf->move($rutaGuardarNit,$docNombreNit);
                    $empresas->rutaNit= "$docNombreNit";
                }

                /* Certificado de Inscripcion -- Subimos el documento pdf */
                if($inscripcionpdf = $request->file('rutaCertInscripcion'))
                {
                    $request->validate(
                        [
                            'rutaCertInscripcion'=>'mimes:pdf,png,jpg,jpeg'
                        ]
                    );
                    $rutaGuardarCert='storage/empresas/cert/';
                    $docNombreCert = date('YmdHis'). "-cert". $request->get('nit') .".". $inscripcionpdf->getClientOriginalExtension();
                    $inscripcionpdf->move($rutaGuardarCert,$docNombreCert);
                    $empresas->rutaCertInscripcion= "$docNombreCert";
                }

                /* Matricula- Subimos el documento pdf */
                if($matriculapdf = $request->file('rutaMatricula'))
                {
                    $request->validate(
                        [
                            'rutaMatricula'=>'mimes:pdf,png,jpg,jpeg'
                        ]
                    );
                    $rutaGuardarMatr='storage/empresas/matr/';
                    $docNombreMatr = date('YmdHis'). "-matr". $request->get('nit') .".". $matriculapdf->getClientOriginalExtension();
                    $matriculapdf->move($rutaGuardarMatr,$docNombreMatr);
                    $empresas->rutaMatricula= "$docNombreMatr";
                }

                /* Roe- Subimos el documento pdf */
                if($roepdf = $request->file('rutaRoe'))
                {
                    $request->validate(
                        [
                            'rutaRoe'=>'mimes:pdf,png,jpg,jpeg'
                        ]
                    );
                    $rutaGuardarRoe='storage/empresas/roe/';
                    $docNombreRoe = date('YmdHis'). "-roe". $request->get('nit') .".". $roepdf->getClientOriginalExtension();
                    $roepdf->move($rutaGuardarRoe,$docNombreRoe);
                    $empresas->rutaRoe= "$docNombreRoe";
                }

            $empresas->estado = 1; //Alta Empresa
            $empresas->save();

            /* creamos casa matriz*/
            $ultimaEmpresa = DB::table('empresas')->max('id');
            //
            $sucursal = new Sucursal();
            $sucursal->descripcion = "Casa Matriz";
            $sucursal->direccion = $request->get('direccion');
            $sucursal->estado =1;//sucursal alta
            $sucursal->empresa_id = $ultimaEmpresa;
            $sucursal->save();


            // mesaje de alerta
            //con with enviamos algo a las vistas
            // return redirect('/empresas/create')->with('crear','ok');
            session()->flash('crear','ok'); // se envia automaticamente -> la linea anterior no funcionaba
            session()->flash('crearMatriz','okMatriz');

            return redirect()->route('empresas.index');
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(url('/empresas'));
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
        //!solo mostramos las altas
        $sucursalesEncontradas = DB::table('sucursals')
                ->where('empresa_id', '=', $id)
                ->where('estado',"=",1)
                ->orderBy('id','ASC')
                ->get();
        $ejerciciosEncontrados = DB::table('ejercicios')
                ->where('empresa_id', '=', $id)
                ->where('estado',"=",1)
                ->orderBy('ejercicioFiscal','DESC')
                ->get();

        //! Busqueda de todas las empresas, las altas se filtran en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las altas se filtran en la vista
        $ejercicios = Ejercicio::all();
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

        $mensaje = ConfiguracionSistema::first(); // Antes se debe establecer un mensaje en configuraciones
        $whatsaap = str_replace(" ", "%20",$mensaje->mensajeWhatsapp); //* reemplazamos espacion para el link de whatsapp

        $empresa = Empresa::find($id);


        return view('modulos.empresas.show')
        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
        ->with('empresa',$empresa)  //! empresa a mostrar
        ->with('sucursalesEncontradas',$sucursalesEncontradas) // este es usado por la vista show
        ->with('ejerciciosEncontrados',$ejerciciosEncontrados) // este es usado por la vista show
        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
        ->with('whatsaap',$whatsaap);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*Administrador
        Contador
        Auxiliar Contable
        PRIVILEGIOS */

        /* PERMISOS */

        if(auth()->user()->editar == 1)
        {
            $empresa = Empresa::find($id);
            //! Busqueda de todas las empresas
            $empresas = Empresa::all();
            //! Busqueda de todas los ejercicios
            $ejercicios = Ejercicio::all();
            //! Busqueda de todas subcuentas para mayores
            $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
            FROM pc_sub_cuenta
            INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
            WHERE estado = 1 ORDER BY id ASC, correlativo ASC');

            return view('modulos.empresas.edit')
            ->with('sub_cuentas',$sub_cuentas) //para modal mayores
            ->with('empresa',$empresa) // este es usado por la vista
            ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios); //este es para el nombre y ejercicio de la empresa activa
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(url('/empresas'));
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
            if($request->get('validador') == 'DarDeAlta')
            {
                $empresa = Empresa::find($id); //* hacemos una consulta
                $empresa->estado = 1;
                $empresa->save();

                session()->flash('actualizar','ok');
                return redirect()->route('empresas.index');
            }
            else
            {
                // * verificamos que no haya duplicidad - -
                //* no verificamos por que no se puede actualizar

                $this->validate($request,[
                    'nit'=>'numeric|required',
                ]);


                $empresa = Empresa::find($id); //* hacemos una consulta

                $empresa->nit = $request->get('nit');
                $empresa->denominacionSocial = strtoupper($request->get('denominacionSocial'));
                $empresa->sociedadTipo = $request->get('sociedadTipo');
                $empresa->actividad = strtoupper($request->get('actividad'));
                $empresa->representanteLegal =	strtoupper($request->get('representanteLegal'));
                $empresa->ci = $request->get('ci');
                $empresa->complemento = $request->get('complemento');
                $empresa->extension = $request->get('extension');
                $empresa->celular = $request->get('celular');
                $empresa->correo = $request->get('correo');
                $empresa->clasificacion = $request->get('clasificacion');

                    /*  NIT -- Subimos el documento pdf */
                    if($nitpdf = $request->file('rutaNit'))
                    {
                        $request->validate(
                            [
                                'rutaNit'=>'mimes:pdf,png,jpg,jpeg'
                            ]
                        );
                        $rutaGuardarNit='storage/empresas/nit/';
                        $docNombreNit = date('YmdHis'). "-nit". $request->get('nit') .".". $nitpdf->getClientOriginalExtension();
                        $nitpdf->move($rutaGuardarNit,$docNombreNit);
                        $empresa->rutaNit= "$docNombreNit";
                    }

                    /* Certificado de Inscripcion -- Subimos el documento pdf */
                    if($inscripcionpdf = $request->file('rutaCertInscripcion'))
                    {
                        $request->validate(
                            [
                                'rutaCertInscripcion'=>'mimes:pdf,png,jpg,jpeg'
                            ]
                        );
                        $rutaGuardarCert='storage/empresas/cert/';
                        $docNombreCert = date('YmdHis'). "-cert". $request->get('nit') .".". $inscripcionpdf->getClientOriginalExtension();
                        $inscripcionpdf->move($rutaGuardarCert,$docNombreCert);
                        $empresa->rutaCertInscripcion= "$docNombreCert";
                    }

                    /* Matricula- Subimos el documento pdf */
                    if($matriculapdf = $request->file('rutaMatricula'))
                    {
                        $request->validate(
                            [
                                'rutaMatricula'=>'mimes:pdf,png,jpg,jpeg'
                            ]
                        );
                        $rutaGuardarMatr='storage/empresas/matr/';
                        $docNombreMatr = date('YmdHis'). "-matr". $request->get('nit') .".". $matriculapdf->getClientOriginalExtension();
                        $matriculapdf->move($rutaGuardarMatr,$docNombreMatr);
                        $empresa->rutaMatricula= "$docNombreMatr";
                    }

                    /* Roe- Subimos el documento pdf */
                    if($roepdf = $request->file('rutaRoe'))
                    {
                        $request->validate(
                            [
                                'rutaRoe'=>'mimes:pdf,png,jpg,jpeg'
                            ]
                        );
                        $rutaGuardarRoe='storage/empresas/roe/';
                        $docNombreRoe = date('YmdHis'). "-roe". $request->get('nit') .".". $roepdf->getClientOriginalExtension();
                        $roepdf->move($rutaGuardarRoe,$docNombreRoe);
                        $empresa->rutaRoe= "$docNombreRoe";
                    }

                $empresa->save();

                session()->flash('actualizar','ok'); // se envia automaticamente -> la linea anterior no funcionaba
                return redirect()->route('empresas.index');
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(url('/empresas'));
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
            $empresa = Empresa::find($id); //* hacemos una consulta
            $empresa->estado = 0;
            $empresa->save();

            session()->flash('eliminar','ok'); //* variable de sesion
            return redirect()->route('empresas.index');
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(url('/empresas'));
        }
    }
}
