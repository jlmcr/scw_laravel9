<?php

namespace App\Http\Controllers\TipoDeCambio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\TipoDeCambio;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TiposDeCambioImport;
use App\Models\ConfiguracionSistema;

class TipoDeCambioController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $gestionBuscada = $request->get('gestion');
        $mesBuscado = $request->get('mes');

        if($gestionBuscada == "" || $mesBuscado == "")
        {
            $cotizaciones_encontradas = DB::table('tipos_de_cambio')
                    ->where('ufv','=','')
                    ->orderBy('fecha','ASC')
                    ->get();
            //! el modelo no permite ORDENAR CON EL METODO ALL() - en otros casos si permite
        }
        else
        {
            $cotizaciones_encontradas = TipoDeCambio::whereMonth('fecha', $mesBuscado)
                        ->whereYear('fecha', $gestionBuscada)
                        ->orderBy('fecha','ASC') //! no funciona el orderBy
                        ->get();
        }

        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();
        //! Año minimo
        $confSistema = ConfiguracionSistema::select('anioMinimo')->first();
        $anioMinimo = $confSistema->anioMinimo;

        return view('modulos.tiposDeCambio.index')
            ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
            ->with('cotizaciones_encontradas',$cotizaciones_encontradas) //! usado para nueva depereciacio - reexpresiones
            ->with('gestionBuscada',$gestionBuscada)
            ->with('mesBuscado', $mesBuscado)
            ->with('anioMinimo',$anioMinimo);

           // return $fecha_1." - ".$cotizaciones_encontradas;
    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            try {

                $fecha = $request->get('fecha'); // dia mes año
                $f = explode('/',$fecha);
                $fh = $f[2]."-".$f[1]."-".$f[0];

                if( checkdate($f[1], $f[0], $f[2]) == true )
                {
                    $tipoCambio = new TipoDeCambio();
                    $tipoCambio->fecha = $fh;
                    $tipoCambio->ufv = $request->get('ufv');
                    $tipoCambio->save();

                    // mesaje de alerta
                    session()->flash('crear','ok'); // se envia automaticamente

                    return redirect(route('tipoCambio.index',['gestion'=>$f[2], 'mes'=>$f[1]]));
                }
                else
                {
                    session()->flash('fecha_','error');
                    return redirect(route('tipoCambio.index'));
                }
            } catch(Exception $e){
                session()->flash('error_ufv','error');
                return redirect(route('tipoCambio.index'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('tipoCambio.index'));
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $fecha = $request->get('fecha');
            $f = explode('/',$fecha);
            $fh = $f[2]."-".$f[1]."-".$f[0];

            if( checkdate($f[1], $f[0], $f[2]) == true )
            {
                $tipoCambio = TipoDeCambio::find($id);
                $tipoCambio->fecha = $fh;
                $tipoCambio->ufv = $request->get('ufv');
                $tipoCambio->save();

                // mesaje de alerta
                session()->flash('actualizar','ok'); // se envia automaticamente

                //return redirect(route('tipoCambio.index'));
                return redirect(route('tipoCambio.index',['gestion'=>$f[2], 'mes'=>$f[1]]));

            }
            else
            {
                session()->flash('fecha_','error');
                return redirect(route('tipoCambio.index'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('tipoCambio.index'));
        }
    }


    public function destroy($id)
    {
        if(auth()->user()->eliminar == 1)
        {
            $tipoCambio = TipoDeCambio::find($id);
            $tipoCambio->delete();

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect(route('tipoCambio.index'));
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('tipoCambio.index'));
        }
    }

    public function importarTiposDeCambio (Request $request)
    {
        if( $request->hasFile('archivo')) //? si se subio el archivo
        {
            $request->validate([
                'archivo'=>'required|mimes:xls,xlsx'
            ]);

            //? variables

            //? recuperamos archivo subido
            $archivo =$request->file('archivo');

            Excel::import(new TiposDeCambioImport(), $archivo);

            session()->flash('importarExcel','ok'); //* variable de sesion
            return back();
        }
    }
}
