<?php

namespace App\Http\Controllers\CompraVenta\ventas;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionSistema;
use App\Models\Ejercicio;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class VentaController extends Controller
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

            return view('modulos.compras_ventas.ventas.index')
            ->with('sub_cuentas',$sub_cuentas) //para modal mayores
            ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
            ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
            ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
            ->with('anioMinimo',$anioMinimo);

            //return $anioMinimo;
        }

        if($request->get('process') == 'search')
        {
            $gestionBuscada = $request->get('gestion');
            $mesBuscado = $request->get('mes');
            $idSucursalBuscada = $request->get('sucursal');
            $idEmpresaActiva = $request->get('idEmpresaActiva');

            if($idSucursalBuscada == '-1')
            {
                //! ventas
                $ventasEncontradas=DB::table('ventas')
                                    ->join('sucursals','ventas.sucursal_id','=','sucursals.id')
                                    ->join('empresas','sucursals.empresa_id','=','empresas.id')
                                    ->select('ventas.*','sucursals.descripcion','sucursals.empresa_id','empresas.denominacionSocial')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada) //->where('sucursal_id',$idSucursalBuscada)
                                    ->where('empresas.id','=',$idEmpresaActiva)
                                    ->orderBy('ventas.fecha','DESC')
                                    ->orderBy('ventas.numeroFactura','DESC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                //? sin where en sucursal
                $ultimoCodigoAutorizacion = DB::select("SELECT codigoAutorizacion FROM ventas WHERE id = (select MAX(id) AS id FROM ventas)");

                return view('modulos.compras_ventas.ventas.index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('ventasEncontradas',$ventasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('ultimoCodigoAutorizacion',$ultimoCodigoAutorizacion);

                /* return $ventasEncontradas; */
            }
            else{
                $ventasEncontradas=DB::table('ventas')
                                    ->select('ventas.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('ventas.fecha','DESC')
                                    ->orderBy('ventas.numeroFactura','DESC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                $ultimoCodigoAutorizacion = DB::select("SELECT codigoAutorizacion FROM ventas WHERE id = (select MAX(id) AS id FROM ventas) and sucursal_id = ".$idSucursalBuscada);

                return view('modulos.compras_ventas.ventas.index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('ventasEncontradas',$ventasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('ultimoCodigoAutorizacion',$ultimoCodigoAutorizacion);

                /* return $ultimoCodigoAutorizacion; */
            }
        }

    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {

            $fecha1 = $request->get('fechaDia').$request->get('fechaMA');
            $fecha2 = Carbon::createFromFormat('d/m/Y', $fecha1);
            $fechaFactura = $fecha2->format('Y-m-d');

            //valida si una fecaha existe - mes, el día y el año - checkdate verifica si la fecha existe
            $f = explode('/',$fecha1);//separamos la fecha en partes - lo guarda en un arreglo
            if(count($f) == 3 && checkdate($f[1], $f[0], $f[2])==true)
            {
                //guardamos venta
                $venta = new Venta();
                $venta->fecha = $fechaFactura;
                $venta->numeroFactura = $request->get('numeroFactura');
                $venta->codigoAutorizacion = strtoupper($request->get('codigoAutorizacion'));
                $venta->ciNitCliente = $request->get('ciNitCliente');
                $venta->complemento = strtoupper($request->get('complemento'));
                $venta->razonSocialCliente = strtoupper($request->get('razonSocialCliente'));
                $venta->importeTotal = $request->get('importeTotal');
                $venta->ice = $request->get('ice');
                $venta->iehd = $request->get('iehd');
                $venta->ipj = $request->get('ipj');
                $venta->tasas = $request->get('tasas');
                $venta->otrosNoSujetosaIva = $request->get('otrosNoSujetosaIva');
                $venta->exportacionesyExentos = $request->get('exportacionesyExentos');
                $venta->tasaCero = $request->get('tasaCero');
                $venta->descuentos = $request->get('descuentos');
                $venta->gifCard = $request->get('gifCard');
                $venta->estado = $request->get('estado');
                //? validamos codigo decontrol
                if($request->get('codigoControl') == "")
                {
                    $venta->codigoControl = "0";
                }
                else
                {
                    $venta->codigoControl = strtoupper($request->get('codigoControl'));
                }

                $venta->tipoVenta = $request->get('tipoVenta');
                $venta->sucursal_id = $request->get('sucursal_id');
                $venta->save();

                session()->flash('crear','ok');
                return redirect('/ventas?process=search&idEmpresaActiva='.$request->get('empresaActivaVenta').'&gestion='.$request->get('gestionVenta').'&mes='.$request->get('mesVenta').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/ventas?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
            else
            {
                session()->flash('errorFecha','error');
                return redirect('/ventas?process=search&idEmpresaActiva='.$request->get('empresaActivaVenta').'&gestion='.$request->get('gestionVenta').'&mes='.$request->get('mesVenta').'&sucursal='.$request->get('sucursal_id'));
                //* http://sistemacontable.test/ventas?process=search&idEmpresaActiva=1&gestion=2022&mes=9&sucursal=10
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/ventas?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }

    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->editar == 1)
        {
            $fecha1 = $request->get('fechaDia_editar').$request->get('fechaMA_editar');
            $fecha2 = Carbon::createFromFormat('d/m/Y', $fecha1);
            $fechaFactura = $fecha2->format('Y-m-d');

            //valida si una fecaha existe - mes, el día y el año - checkdate verifica si la fecha existe

            $f = explode('/',$fecha1);//separamos la fecha en partes - lo guarda en un arreglo
            if(count($f) == 3 && checkdate($f[1], $f[0], $f[2])==true)
            {
                //guardamos venta
                $venta = Venta::find($id);
                $venta->fecha = $fechaFactura;
                $venta->numeroFactura = $request->get('numeroFactura_editar');
                $venta->codigoAutorizacion = strtoupper($request->get('codigoAutorizacion_editar'));
                $venta->ciNitCliente = $request->get('ciNitCliente_editar');
                $venta->complemento = strtoupper($request->get('complemento_editar'));
                $venta->razonSocialCliente = strtoupper($request->get('razonSocialCliente_editar'));
                $venta->importeTotal = $request->get('importeTotal_editar');
                $venta->ice = $request->get('ice_editar');
                $venta->iehd = $request->get('iehd_editar');
                $venta->ipj = $request->get('ipj_editar');
                $venta->tasas = $request->get('tasas_editar');
                $venta->otrosNoSujetosaIva = $request->get('otrosNoSujetosaIva_editar');
                $venta->exportacionesyExentos = $request->get('exportacionesyExentos_editar');
                $venta->tasaCero = $request->get('tasaCero_editar');
                $venta->descuentos = $request->get('descuentos_editar');
                $venta->gifCard = $request->get('gifCard_editar');
                $venta->estado = $request->get('estado_editar');

                //! codigo decontrol
                if($request->get('codigoControl_editar') == "")
                {
                    $venta->codigoControl = "0";
                }
                else
                {
                    $venta->codigoControl = strtoupper($request->get('codigoControl_editar'));
                }

                $venta->tipoVenta = $request->get('tipoVenta_editar');
                $venta->sucursal_id = $request->get('sucursal_id');
                $venta->save();

                session()->flash('actualizar','ok');
                return redirect('/ventas?process=search&idEmpresaActiva='.$request->get('empresaActivaVenta').'&gestion='.$request->get('gestionVenta').'&mes='.$request->get('mesVenta').'&sucursal='.$request->get('sucursal_id'));
            }
            else
            {
                session()->flash('errorFecha','error');
                return redirect('/ventas?process=search&idEmpresaActiva='.$request->get('empresaActivaVenta').'&gestion='.$request->get('gestionVenta').'&mes='.$request->get('mesVenta').'&sucursal='.$request->get('sucursal_id'));
            }
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/ventas?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }
    }

    public function destroy($id)
    {
        if(auth()->user()->eliminar == 1)
        {

            $ventaAuxiliar = Venta::find($id); //* hacemos una consulta para usar su id
            $venta = Venta::find($id); //* hacemos una consulta
            $venta->delete();
            //* sucursal
            $sucursal = Sucursal::find($ventaAuxiliar->sucursal_id);
            //* empresa activa
            $EmpresaActiva = Empresa::find($sucursal->empresa_id);
            //* gestion
            $Y = explode('-',$ventaAuxiliar->fecha);
            $gestion = $Y[0];
            //* mes
            $mes = $Y[1];

            session()->flash('eliminar','ok'); //* varaiable de sesion
            return redirect('/ventas?process=search&idEmpresaActiva='.$EmpresaActiva->id.'&gestion='.$gestion.'&mes='.$mes.'&sucursal='.$sucursal->id);
        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect('/ventas?process=menu&idEmpresaActiva='.auth()->user()->idEmpresaActiva);
        }
    }

    /* ajax */
    public function editarVenta(request $request)
    {
        $idVenta=$request->get('idVenta');

        $ventaEditar = Venta::find($idVenta);

        return  $ventaEditar;
    }

    public function eliminarMultiplesVentas(Request $request){
        $ids = $request->get('ids');
    
        DB::table("ventas")->whereIn('id',explode(",",$ids))->delete(); 
        //el explode es para quitar la coma entre cada id ejemplo 2, 5,8,5,


        return response()->json([
            'respuesta_eliminados' =>'ventas eliminadas'
        ]); 

        //return response()->json(['success'=>'User Deleted Successfully!']);
        //return "estas en el controlador";
    }

}
