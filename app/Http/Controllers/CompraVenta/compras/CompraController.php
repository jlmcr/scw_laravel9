<?php

namespace App\Http\Controllers\CompraVenta\compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\Sucursal;
use App\Models\ConfiguracionSistema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class CompraController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');
        //! Busqueda de todas las empresas
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all();
        //! Año minimo
        $confSistema = ConfiguracionSistema::select('anioMinimo')->first();
        $anioMinimo = $confSistema->anioMinimo;


        if($request->get('process') == 'menu')
        {
            $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

            return view('modulos.compras_ventas.compras.index')
            ->with('sub_cuentas',$sub_cuentas) //para modal mayores
            ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
            ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
            ->with('anioMinimo',$anioMinimo);

            //return $anioMinimo;
        }

        if($request->get('process') == 'search')
        {
            // SELECT * FROM tabla WHERE MONTH(colfecha) = 10 AND YEAR(colfecha) = 2016
            $gestionBuscada = $request->get('gestion');
            $mesBuscado = $request->get('mes');
            $idSucursalBuscada = $request->get('sucursal');
            $idEmpresaActiva = $request->get('idEmpresaActiva');

            if($idSucursalBuscada == '-1')
            {
                //! compras
                $comprasEncontradas=DB::table('compras')
                                    ->join('sucursals','compras.sucursal_id','=','sucursals.id')
                                    ->join('empresas','sucursals.empresa_id','=','empresas.id')
                                    ->select('compras.*','sucursals.descripcion','sucursals.empresa_id','empresas.denominacionSocial')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada) //->where('sucursal_id',$idSucursalBuscada)
                                    ->where('empresas.id','=',$idEmpresaActiva)
                                    ->orderBy('compras.fecha','DESC')
                                    ->orderBy('compras.numeroFactura','DESC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                return view('modulos.compras_ventas.compras.index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('comprasEncontradas',$comprasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo);

                /* return $comprasEncontradas; */
            }
            else{
                $comprasEncontradas=DB::table('compras')
                                    //->join('proveedors','compras.proveedor_nit_id','=','proveedors.nit')
                                    //->select('compras.*','proveedors.nit', 'proveedors.nombreProveedor')
                                    ->select('compras.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('compras.fecha','DESC')
                                    ->orderBy('compras.numeroFactura','DESC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                return view('modulos.compras_ventas.compras.index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('comprasEncontradas',$comprasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo);

                //return $comprasEncontradas;
            }
        }

    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            //https://styde.net/componente-carbon-fechas-laravel-5/

            $fecha1 = $request->get('fechaDia').$request->get('fechaMA');
            $fecha2 = Carbon::createFromFormat('d/m/Y', $fecha1);
            $fechaFactura = $fecha2->format('Y-m-d');

            //valida si una fecha existe - mes, el día y el año - checkdate verifica si la fecha existe
            $f = explode('/',$fecha1);//separamos la fecha en partes - lo guarda en un arreglo
            if(count($f) == 3 && checkdate($f[1], $f[0], $f[2])==true)
            {
                //guardamos compra
                $compra = new Compra();
                $compra->nitProveedor = $request->get('nitProveedor');
                $compra->razonSocialProveedor = strtoupper($request->get('razonSocialProveedor'));
                $compra->codigoAutorizacion = strtoupper($request->get('codigoAutorizacion'));
                $compra->numeroFactura = $request->get('numeroFactura');
                $compra->dim = strtoupper($request->get('dim'));
                $compra->fecha = $fechaFactura;
                $compra->importeTotal = $request->get('importeTotal');
                $compra->ice = $request->get('ice');
                $compra->iehd = $request->get('iehd');
                $compra->ipj = $request->get('ipj');
                $compra->tasas = $request->get('tasas');
                $compra->otrosNoSujetosaCF = $request->get('otrosNoSujetosaCF');
                $compra->exentos = $request->get('exentos');
                $compra->tasaCero = $request->get('tasaCero');
                $compra->descuentos = $request->get('descuentos');
                $compra->gifCard = $request->get('gifCard');
                $compra->tipoCompra = $request->get('tipoCompra');
                //? validamos codigo decontrol
                if($request->get('codigoControl') == "")
                {
                    $compra->codigoControl = "0";
                }
                else
                {
                    $compra->codigoControl = strtoupper($request->get('codigoControl'));
                }

                $compra->sucursal_id = $request->get('sucursal_id');

                //? combustible
                if($request->get('checkboxCombustible') == true)
                {
                    $compra->combustible = 1;
                    /*! actualizamos combustible */
                    DB::update("update compras set combustible =1 where nitProveedor ='".$request->get('nitProveedor')."'");
                }
                else
                {
                    $compra->combustible = 0;
                    /*! actualizamos combustible */
                    DB::update("update compras set combustible =0 where nitProveedor ='".$request->get('nitProveedor')."'");
                }

                $compra->save();

                //ultimo codigo de autorizacion utilizado
                DB::update("update compras set ultimoCodigoAutorizacion='".$request->get('codigoAutorizacion')."' where nitProveedor =".$request->get('nitProveedor'));

                session()->flash('crear','ok');
                return redirect('/compras?process=search&idEmpresaActiva='.$request->get('empresaActivaCompra').'&gestion='.$request->get('gestionCompra').'&mes='.$request->get('mesCompra').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/compras?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
            else
            {
                session()->flash('errorFecha','error');
                return redirect('/compras?process=search&idEmpresaActiva='.$request->get('empresaActivaCompra').'&gestion='.$request->get('gestionCompra').'&mes='.$request->get('mesCompra').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/compras?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/compras?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }

    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            //https://styde.net/componente-carbon-fechas-laravel-5/

            $fecha1 = $request->get('fechaDia_editar').$request->get('fechaMA_editar');
            $fecha2 = Carbon::createFromFormat('d/m/Y', $fecha1);
            $fechaFactura = $fecha2->format('Y-m-d');

            //valida si una fecaha existe - mes, el día y el año - checkdate verifica si la fecha existe
            $f = explode('/',$fecha1);//separamos la fecha en partes - lo guarda en un arreglo

            if(count($f) == 3 && checkdate($f[1], $f[0], $f[2])==true)
            {
                //guardamos compra
                $compra = Compra::find($id);
                $compra->nitProveedor = $request->get('nitProveedor_editar');
                $compra->razonSocialProveedor = strtoupper($request->get('razonSocialProveedor_editar'));
                $compra->codigoAutorizacion = strtoupper($request->get('codigoAutorizacion_editar'));
                $compra->numeroFactura = $request->get('numeroFactura_editar');
                $compra->dim = strtoupper($request->get('dim_editar'));
                $compra->fecha = $fechaFactura;
                $compra->importeTotal = $request->get('importeTotal_editar');
                $compra->ice = $request->get('ice_editar');
                $compra->iehd = $request->get('iehd_editar');
                $compra->ipj = $request->get('ipj_editar');
                $compra->tasas = $request->get('tasas_editar');
                $compra->otrosNoSujetosaCF = $request->get('otrosNoSujetosaCF_editar');
                $compra->exentos = $request->get('exentos_editar');
                $compra->tasaCero = $request->get('tasaCero_editar');
                $compra->descuentos = $request->get('descuentos_editar');
                $compra->gifCard = $request->get('gifCard_editar');
                $compra->tipoCompra = $request->get('tipoCompra_editar');

                //? validamos codigo decontrol
                if($request->get('codigoControl_editar') == "")
                {
                    $compra->codigoControl = "0";
                }
                else
                {
                    $compra->codigoControl = strtoupper($request->get('codigoControl_editar'));
                }

                //$compra->sucursal_id = $request->get('sucursal_id_editar');

                if($request->get('checkboxCombustible_editar') == true)
                {
                    $compra->combustible = 1;
                }
                else
                {
                    $compra->combustible = 0;
                }

                $compra->save();

                session()->flash('actualizar','ok');
                return redirect('/compras?process=search&idEmpresaActiva='.$request->get('empresaActivaCompra').'&gestion='.$request->get('gestionCompra').'&mes='.$request->get('mesCompra').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/compras?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
            else
            {
                session()->flash('errorFecha','error');
                return redirect('/compras?process=search&idEmpresaActiva='.$request->get('empresaActivaCompra').'&gestion='.$request->get('gestionCompra').'&mes='.$request->get('mesCompra').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/compras?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/compras?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }
    }

    public function destroy($id)
    {
        if(auth()->user()->eliminar == 1)
        {

            $CompraAuxiliar = Compra::find($id); //* hacemos una consulta para usar su id
            $compra = Compra::find($id); //* hacemos una consulta
            $compra->delete();
            //* sucursal
            $sucursal = Sucursal::find($CompraAuxiliar->sucursal_id); //DB::table('sucursals')->where('id','=',$CompraAuxiliar->sucursal_id);
            //* empresa activa
            $EmpresaActiva = Empresa::find($sucursal->empresa_id); //DB::table('empresas')->where('id','=',$sucursal->empresa_id);
            //* gestion
            $Y = explode('-',$CompraAuxiliar->fecha);
            $gestion = $Y[0];
            //* mes
            $mes = $Y[1];

            //compras?process=search&idEmpresaActiva=2&gestion=2022&mes=1&sucursal=7
            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('/compras?process=search&idEmpresaActiva='.$EmpresaActiva->id.'&gestion='.$gestion.'&mes='.$mes.'&sucursal='.$sucursal->id);
            //return $EmpresaActiva;
            //return route('compras.index',[idEmpresaActiva=$idEmpresaActiva->id, mes=$mes,gestion =$gestion, sucursal =$sucursal]);

        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/compras?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }
    }

    public function editarCompra(request $request)
    {
        $idCompra=$request->get('idCompra');

        $compraEditar = Compra::find($idCompra);

        return  $compraEditar;
    }

    public function eliminarMultiplesCompras(Request $request){
        $ids = $request->get('ids');

        DB::table("compras")->whereIn('id',explode(",",$ids))->delete();
        //el explode es para quitar la coma entre cada id ejemplo 2, 5,8,5,


        return response()->json([
            'respuesta_eliminados' =>'compras eliminadas'
        ]);

        //return response()->json(['success'=>'User Deleted Successfully!']);
        //return "estas en el controlador";
    }
}
