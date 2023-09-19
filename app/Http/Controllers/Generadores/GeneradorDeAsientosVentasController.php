<?php

namespace App\Http\Controllers\Generadores;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\ConfiguracionSistema;
use App\Models\CuentasDefault;
use App\Models\DetalleComprobante;
use App\Models\Ejercicio;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneradorDeAsientosVentasController extends Controller
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

        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $idEjercicioActivo = auth()->user()->idEjercicioActivo;
        $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);



        if($request->get('process') == 'menu')
        {
            $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

            return view('modulos.generadores-asientos.ventas-index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('anioMinimo',$anioMinimo)
                ->with('datosEjercicioActivo',$datosEjercicioActivo);
        }

        if($request->get('process') == 'search')
        {
            $gestionBuscada = $request->get('gestion'); // busqueda por mes
            $mesBuscado = $request->get('mes'); // busqueda por mes
            $idSucursalBuscada = $request->get('sucursal');
            // $fechaInicio_buscado = $request->get('fecha_inicio'); // busqueda por fechas
            // $fechaFin_buscado = $request->get('fecha_fin'); // busqueda por fechas

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
                                    ->orderBy('ventas.fecha','ASC')
                                    ->orderBy('ventas.numeroFactura','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta


                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

                //PARA LA PARTE DE LAS CUENTAS
                $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
                FROM pc_sub_cuenta
                INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
                WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');

                return view('modulos.generadores-asientos.ventas-index')
                    ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                    ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                    ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                    ->with('ventasEncontradas',$ventasEncontradas)
                    ->with('gestionBuscada',$gestionBuscada)
                    ->with('mesBuscado', $mesBuscado)
                    ->with('idSucursalBuscada', $idSucursalBuscada)
                    ->with('anioMinimo',$anioMinimo)
                    ->with('sub_cuentas',$sub_cuentas)
                    ->with('datosEjercicioActivo',$datosEjercicioActivo);

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

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

                //PARA LA PARTE DE LAS CUENTAS
                $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
                FROM pc_sub_cuenta
                INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
                WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');


                return view('modulos.generadores-asientos.ventas-index')
                    ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                    ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                    ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                    ->with('ventasEncontradas',$ventasEncontradas)
                    ->with('gestionBuscada',$gestionBuscada)
                    ->with('mesBuscado', $mesBuscado)
                    ->with('idSucursalBuscada', $idSucursalBuscada)
                    ->with('anioMinimo',$anioMinimo)
                    ->with('sub_cuentas',$sub_cuentas)
                    ->with('datosEjercicioActivo',$datosEjercicioActivo);

                /* return $ultimoCodigoAutorizacion; */
            }
        }

    }

    public function store(Request $request)
    {
        if(auth()->user()->crear == 1)
        {
            //! cuentas predeterminadas
            /*
                VENTA	RESTO	DÉBITO FISCAL-IVA	PASIVO
                VENTA	DESCUENTO	DESCUENTOS Y DEVOLUCIONES SOBRE VENTAS	INGRESO -SALDO NEGATIVO
                VENTA	RESTO	IMPUESTO A LA TRANSACCIONES POR PAGAR	PASIVO
                VENTA	RESTO	IMPUESTO A LAS TRANSACCIONES	GASTO
            */

            $contador_de_comprobantes_creados=0;
            $cuenta_DF_predeterminada = CuentasDefault::find(5); // el id se trae directamente de la tabla
            $cuenta_descuentosEnVentas_predeterminada = CuentasDefault::find(6);
            $cuenta_IT_porPagar_predeterminada = CuentasDefault::find(7);
            $cuenta_IT_predeterminada = CuentasDefault::find(8);
            $cuenta_CF_predeterminada = CuentasDefault::find(1); //para los descuentos

            //! arreglos recibidos
            $codigo_debe_array = $request->get('codigo_debe');
            $codigo_haber_array = $request->get('codigo_haber');

            $tipo_factura_array = $request->get('tipo_factura');
            $id_venta_array = $request->get('id_venta');

            //!importes recibidos
            $baseParaDF_array = $request->get('baseParaDF');
            $debitoFiscal_array = $request->get('debitoFiscal');

            //return $codigo_debe_array;

            if($codigo_debe_array != "")
            {
                try {
                    for ($i=0; $i < count($codigo_debe_array) ; $i++) {

                        if ($codigo_debe_array[$i] != "vacio" && $codigo_debe_array[$i] != "" &&
                            $codigo_haber_array[$i] != "vacio" && $codigo_haber_array[$i] != "" && $tipo_factura_array[$i] != "" ){

                                $contador_de_comprobantes_creados = $contador_de_comprobantes_creados + 1;

                            //! consultamos la compra
                            $factura = Venta::find($id_venta_array[$i]);

                            //! alistamos datos para el comprobante
                                //!armamos el concepto
                                $concepto = "POR LA VENTA A ".$factura->razonSocialCliente." con CI/NIT ".$factura->ciNitCliente." SEGÚN FACTURA NRO. ".$factura->numeroFactura." DE FECHA ".date('d/m/Y',strtotime($factura->fecha));
                                //!armamos las notas
                                $notas = "CI/NIT: ".$factura->ciNitCliente." - FACT. NRO. ".$factura->numeroFactura." (tipo: ".$tipo_factura_array[$i].")";
                                //!armamos documento y numero documento para el comprobante
                                $documento="Factura de venta";
                                $cod_documento = $factura->numeroFactura;

                                //! datos para el comprobante
                                //$fechaComprobante = Carbon::createFromFormat('d/m/Y', $factura->fecha);
                                $fechaComprobante = $factura->fecha;

                                $anio = date("Y", strtotime($fechaComprobante));
                                $mes = date("m", strtotime($fechaComprobante));
                                $idTipoComprobante = 3; // egreso - modificarm hcer que se enviedesde la vista
                                $idEjercicio = auth()->user()->idEjercicioActivo;

                            //! alistamos datos para el comprobante

                            //!creamos comprobante con datos generales
                                $correl = DB::select("SELECT max(correlativo) AS maximo FROM comprobantes
                                WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=?",
                                [$anio, $mes, $idTipoComprobante, $idEjercicio]);

                                //? correlativo
                                $correlativo = $correl[0]->maximo +1; //?actualizamos el correlativo

                                $longitud_correlativo = strlen($correlativo);//longitud de cadena
                                $codigo_correlativo ="";

                                //evaluamos el largo de la cadena
                                switch ($longitud_correlativo) {
                                    case 0:
                                        $codigo_correlativo = "0000".$correlativo; // ejemplo 0000
                                        break;
                                    case 1:
                                        $codigo_correlativo = "000".$correlativo; // ejemplo 0009
                                        break;
                                    case 2:
                                        $codigo_correlativo = "00".$correlativo; // ejemplo 0099
                                        break;
                                    case 3:
                                        $codigo_correlativo = "0".$correlativo; // ejemplo 0999
                                        break;
                                    default:
                                        $codigo_correlativo = $correlativo;
                                        break;
                                }

                                //! numero de comprobante
                                $numeroComprobante = $idTipoComprobante."-".$mes.$codigo_correlativo;

                                $comprobante = new Comprobante();
                                $comprobante->estado=1;
                                $comprobante->nroComprobante = $numeroComprobante;
                                $comprobante->fecha = $fechaComprobante;
                                $comprobante->correlativo = $correlativo;
                                $comprobante->concepto = strtoupper($concepto);
                                $comprobante->notas = strtoupper($notas);
                                $comprobante->observaciones = "";
                                $comprobante->documento = strtoupper($documento);
                                $comprobante->numeroDocumento = strtoupper($cod_documento);
                                $comprobante->ejercicio_id = $idEjercicio;
                                $comprobante->tipoComprobante_id = $idTipoComprobante;
                                $comprobante->save();

                                //? datos del comprobante general ya guardado
                                $comprobante_guardado = DB::select('SELECT * FROM comprobantes
                                WHERE year(fecha)=? AND month(fecha)=? AND tipoComprobante_id=? AND ejercicio_id=? AND nroComprobante=?',
                                [$anio, $mes, $idTipoComprobante, $idEjercicio, $numeroComprobante]);

                                $idComprobante = $comprobante_guardado[0]->id;
                            //!creamos comprobante con datos generales

                            //! detalle del comprabante RESTO
                                if($tipo_factura_array[$i] == "resto")
                                {
                                    $it = round($factura->importeTotal * 0.03, 2);

                                    //*caja DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $factura->importeTotal;
                                        $detalle->haber = 0;

                                        $detalle->orden = 1;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $codigo_debe_array[$i];
                                        $detalle->save();

                                    //*impuesto a las transacciones DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $it;
                                        $detalle->haber = 0;

                                        $detalle->orden = 2;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_IT_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //*ventas HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $factura->importeTotal - $debitoFiscal_array[$i];

                                        $detalle->orden = 3;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $codigo_haber_array[$i];
                                        $detalle->save();

                                    //*debito fiscal HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $debitoFiscal_array[$i];

                                        $detalle->orden = 4;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_DF_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //*impuesto a las transacciones X P HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $it;

                                        $detalle->orden = 5;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_IT_porPagar_predeterminada->subcuenta_id;
                                        $detalle->save();
                                }
                            //! detalle del comprabante

                            //! detalle del comprabante DESCUENTO
                                if($tipo_factura_array[$i] == "descuento")
                                {
                                    $auxDF = round($factura->importeTotal * 0.13,2);//! utilizado en caso de descuentos
                                    $auxCF = round($factura->descuentos * 0.13,2);//! utilizado en caso de descuentos

                                    $it = round($factura->importeTotal * 0.03, 2);

                                    //*caja DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $factura->importeTotal - $factura->descuentos;
                                        $detalle->haber = 0;

                                        $detalle->orden = 1;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $codigo_debe_array[$i];
                                        $detalle->save();

                                    //* Descuentos en ventas DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $factura->descuentos - $auxCF;
                                        $detalle->haber = 0;

                                        $detalle->orden = 1;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_descuentosEnVentas_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //* credito fiscal por Descuentos en ventas DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $auxCF;
                                        $detalle->haber = 0;

                                        $detalle->orden = 1;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_CF_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //*impuesto a las transacciones DEBE
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = $it;
                                        $detalle->haber = 0;

                                        $detalle->orden = 2;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_IT_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //*ventas HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $factura->importeTotal - $auxDF;

                                        $detalle->orden = 3;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $codigo_haber_array[$i];
                                        $detalle->save();

                                    //*debito fiscal HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $auxDF;

                                        $detalle->orden = 4;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_DF_predeterminada->subcuenta_id;
                                        $detalle->save();

                                    //*impuesto a las transacciones X P HABER
                                        $detalle = new DetalleComprobante();

                                        $detalle->debe = 0;
                                        $detalle->haber = $it;

                                        $detalle->orden = 5;
                                        $detalle->comprobante_id = $idComprobante;
                                        $detalle->subcuenta_id = $cuenta_IT_porPagar_predeterminada->subcuenta_id;
                                        $detalle->save();

                                }
                            //! detalle del comprabante

                            $venta = Venta::find($id_venta_array[$i]);
                            $venta->registroContable = $numeroComprobante."*".$idComprobante;
                            $venta->save();
                        }

                    }
                } catch (\Throwable $th) {
                    session()->flash('generar-asientos','error');
                }
            }

            //return count($codigo_debe_array);
            //return $contador_de_comprobantes_creados;
            //! enviamos el mensaje flash por url
            return redirect('/contabilidad/generador-asientos-de-ventas?process=search&gestion='.$request->gestionBuscada.'&mes='.$request->mesBuscado.'&sucursal='.$request->idSucursalBuscada.'&cantVentGen='.$contador_de_comprobantes_creados);

            //! http://sistemacontable.test/contabilidad/generador-asientos-de-ventas?process=search&gestion=2022&mes=1&sucursal=1

            //return "creado";
            //return $codigo_debe;

            //! creamos comprobante contable

        }
        else
        {
            session()->flash('acceso','denegado');
            return redirect(route('dashboard.index'));
        }
    }

}
