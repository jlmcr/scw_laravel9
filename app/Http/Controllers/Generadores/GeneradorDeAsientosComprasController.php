<?php

namespace App\Http\Controllers\Generadores;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\ConfiguracionSistema;
use App\Models\CuentasDefault;
use App\Models\DetalleComprobante;
use App\Models\Ejercicio;
use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneradorDeAsientosComprasController extends Controller
{

    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //?consulta por fecha
        /* $comprasEncontradas = DB::select("SELECT compras.*, sucursals.descripcion, sucursals.empresa_id, empresas.denominacionSocial FROM compras INNER JOIN sucursals ON compras.sucursal_id = sucursals.id inner join empresas on sucursals.empresa_id = empresas.id where fecha between ? and ? and empresas.id = ? order by compras.fecha desc",[$fechaInicio, $fechaCierre, $idEmpresaActiva]); */

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

        //!
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $idEjercicioActivo = auth()->user()->idEjercicioActivo;
        $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);

        if($request->get('process') == 'menu')
        {
            $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

            return view('modulos.generadores-asientos.compras-index')
                        ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                        ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                        ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                        ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                        ->with('datosEjercicioActivo',$datosEjercicioActivo)
                        ->with('anioMinimo',$anioMinimo);
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
                //! todas las compras
                //?  con fechas
                /* SELECT * from compras
                WHERE
                fecha BETWEEN '2022-01-01' and '2022-01-31' */
                /* $comprasEncontradas=DB::table('compras')
                            ->join('sucursals','compras.sucursal_id','=','sucursals.id')
                            ->join('empresas','sucursals.empresa_id','=','empresas.id')
                            ->select('compras.*','sucursals.descripcion','sucursals.empresa_id','empresas.denominacionSocial')
                            ->whereBetween('fecha',[$fechaInicio_buscado,$fechaFin_buscado])
                            ->where('empresas.id','=',$idEmpresaActiva)
                            ->orderBy('compras.fecha','ASC')
                            ->orderBy('sucursals.descripcion','ASC')
                            ->get(); */
                //? por mes
                $comprasEncontradas=DB::table('compras')
                                    ->join('sucursals','compras.sucursal_id','=','sucursals.id')
                                    ->join('empresas','sucursals.empresa_id','=','empresas.id')
                                    ->select('compras.*','sucursals.descripcion','sucursals.empresa_id','empresas.denominacionSocial')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada) //->where('sucursal_id',$idSucursalBuscada)
                                    ->where('empresas.id','=',$idEmpresaActiva)
                                    ->orderBy('compras.fecha','ASC')
                                    ->get();

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

                //PARA LA PARTE DE LAS CUENTAS
                $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
                FROM pc_sub_cuenta
                INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
                WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');


                return view('modulos.generadores-asientos.compras-index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('comprasEncontradas',$comprasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('sub_cuentas',$sub_cuentas)
                ->with('datosEjercicioActivo',$datosEjercicioActivo);

                /* return $comprasEncontradas; */
            }
            else{
                $comprasEncontradas=DB::table('compras')
                                        ->select('compras.*')
                                        ->whereMonth('fecha', $mesBuscado)
                                        ->whereYear('fecha', $gestionBuscada)
                                        ->where('sucursal_id',$idSucursalBuscada)
                                        ->orderBy('compras.fecha','ASC')
                                        ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$idEmpresaActiva);

                //PARA LA PARTE DE LAS CUENTAS
                $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
                FROM pc_sub_cuenta
                INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
                WHERE estado = 1 ORDER BY pc_partida_contable.descripcion ASC');

                return view('modulos.generadores-asientos.compras-index')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('comprasEncontradas',$comprasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('sub_cuentas',$sub_cuentas)
                ->with('datosEjercicioActivo',$datosEjercicioActivo);
                //return $comprasEncontradas;
            }
        }

    //return $comprasEncontradas;
    }


    public function store(Request $request)
    {

        if(auth()->user()->crear == 1)
        {
            //! cuentas predeterminadas
            /*
            COMPRA	RESTO	        CRÉDITO FISCAL-IVA	                        ACTIVO
            COMPRA	DESCUENTO	    DESCUENTOS Y DEVOLUCIONES SOBRE COMPRAS	    GASTO -SALDO NEGATIVO
            COMPRA	COMBUSTIBLE	    IVA SUBENCIONADO (COMBUSTIBLE)	            GASTO
            COMPRA	ELECTRICIDAD	TASA DE ASEO Y ALUMBRADO PUBLICO	        GASTO
            */

            $contador_de_comprobantes_creados=0;
            $cuenta_CF_predeterminada = CuentasDefault::find(1); // el id se trae directamente de la tabla
            $cuenta_descuentosEnCompras_predeterminada = CuentasDefault::find(2);
            $cuenta_ivaSubencionado_predeterminada = CuentasDefault::find(3);
            $cuenta_tasaAseoUrbano_predeterminada = CuentasDefault::find(4);
            $cuenta_DF_predeterminada = CuentasDefault::find(5); //para los descuentos

            //! arreglos recibidos
            $codigo_debe_array = $request->get('codigo_debe');
            $codigo_haber_array = $request->get('codigo_haber');

            $tipo_factura_array = $request->get('tipo_factura');
            $id_compra_array = $request->get('id_compra');
            //!importes recibidos
            $baseParaCF_array = $request->get('baseParaCF');
            $creditoFiscal_array = $request->get('creditoFiscal');

            //return $codigo_debe_array;

            if($codigo_debe_array != "")
            {
                try {
                    for ($i=0; $i < count($codigo_debe_array) ; $i++) {
                        if ($codigo_debe_array[$i] != "vacio" && $codigo_debe_array[$i] != "" &&
                            $codigo_haber_array[$i] != "vacio" && $codigo_haber_array[$i] != "" && $tipo_factura_array[$i] != "" ){
        
                                $contador_de_comprobantes_creados = $contador_de_comprobantes_creados + 1;
        
                            //! consultamos la compra
                            $factura = Compra::find($id_compra_array[$i]);
        
                            //! alistamos datos para el comprobante
                                //!armamos el concepto
                                $concepto = "POR LA COMPRA A ".$factura->razonSocialProveedor." con NIT ".$factura->nitProveedor." SEGÚN FACTURA NRO. ".$factura->numeroFactura." DE FECHA ".date('d/m/Y',strtotime($factura->fecha));
                                //!armamos las notas
                                $notas = "NIT: ".$factura->nitProveedor." - FACT. NRO. ".$factura->numeroFactura." (tipo: ".$tipo_factura_array[$i].")";
                                //!armamos documento y numero documento para el comprobante
                                $documento="Factura de compra";
                                $cod_documento = $factura->numeroFactura;
        
                                //! datos para el comprobante
                                //$fechaComprobante = Carbon::createFromFormat('d/m/Y', $factura->fecha);
                                $fechaComprobante = $factura->fecha;
        
                                $anio = date("Y", strtotime($fechaComprobante));
                                $mes = date("m", strtotime($fechaComprobante));
                                $idTipoComprobante = 2; // egreso - modificarm hcer que se enviedesde la vista
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
                                    //*activo o gasto DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $factura->importeTotal - $creditoFiscal_array[$i];
                                    $detalle->haber = 0;
                                    $detalle->orden = 1;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_debe_array[$i];
                                    $detalle->save();
        
                                    //*credito fiscal DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $creditoFiscal_array[$i];
                                    $detalle->haber = 0;
                                    $detalle->orden = 2;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_CF_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*activo o pasivo HABER (caja-)
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $factura->importeTotal;
                                    $detalle->orden = 3;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_haber_array[$i];
                                    $detalle->save();
                                }
                            //! detalle del comprabante
        
                            //! detalle del comprabante DESCUENTO
                                if($tipo_factura_array[$i] == "descuento")
                                {
                                    $auxCF = round($factura->importeTotal * 0.13,2);//! utilizado en caso de descuentos
                                    $auxDF = round($factura->descuentos * 0.13,2);//! utilizado en caso de descuentos
        
                                    //*activo o gasto DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $factura->importeTotal - $auxCF;
                                    $detalle->haber = 0;
                                    $detalle->orden = 1;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_debe_array[$i];
                                    $detalle->save();
        
                                    //*credito fiscal DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $auxCF;
                                    $detalle->haber = 0;
                                    $detalle->orden = 2;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_CF_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*descuentos sobre compras (gasto negativo) HABER
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $factura->descuentos - $auxDF;
                                    $detalle->orden = 3;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_descuentosEnCompras_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*debito fiscal descuentos  HABER
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $auxDF;
                                    $detalle->orden = 4;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_DF_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*activo o pasivo HABER (caja)
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $factura->importeTotal - $factura->descuentos;
                                    $detalle->orden = 5;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_haber_array[$i];
                                    $detalle->save();
                                }
                            //! detalle del comprabante
        
                            //! detalle del comprabante COMBUSTIBLE
                                if($tipo_factura_array[$i] == "combustible")
                                {
                                    //*activo o gasto DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $factura->importeTotal - $creditoFiscal_array[$i];
                                    $detalle->haber = 0;
                                    $detalle->orden = 1;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_debe_array[$i];
                                    $detalle->save();
        
                                    //*credito fiscal DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = round($creditoFiscal_array[$i] * 0.7 , 2);
                                    $detalle->haber = 0;
                                    $detalle->orden = 2;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_CF_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*credito fiscal SUBENCIONADO DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = round($creditoFiscal_array[$i] - ($creditoFiscal_array[$i] * 0.7), 2);
                                    $detalle->haber = 0;
                                    $detalle->orden = 3;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_ivaSubencionado_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*activo o pasivo HABER (caja)
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $factura->importeTotal;
                                    $detalle->orden = 4;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_haber_array[$i];
                                    $detalle->save();
                                }
                            //! detalle del comprabante
        
                            //! detalle del comprabante ENELECTRICIDAD
                                if($tipo_factura_array[$i] == "electricidad")
                                {
                                    //*activo o gasto DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $factura->importeTotal - $creditoFiscal_array[$i] - $factura->tasas;
                                    $detalle->haber = 0;
                                    $detalle->orden = 1;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_debe_array[$i];
                                    $detalle->save();
        
                                    //*activo o gasto DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $factura->tasas;
                                    $detalle->haber = 0;
                                    $detalle->orden = 2;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_tasaAseoUrbano_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*credito fiscal DEBE
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = $creditoFiscal_array[$i];
                                    $detalle->haber = 0;
                                    $detalle->orden = 3;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $cuenta_CF_predeterminada->subcuenta_id;
                                    $detalle->save();
        
                                    //*activo o pasivo HABER (caja-)
                                    $detalle = new DetalleComprobante();
                                    $detalle->debe = 0;
                                    $detalle->haber = $factura->importeTotal;
                                    $detalle->orden = 4;
                                    $detalle->comprobante_id = $idComprobante;
                                    $detalle->subcuenta_id = $codigo_haber_array[$i];
                                    $detalle->save();
                                }
                            //! detalle del comprabante
        
        
                            $compra = Compra::find($id_compra_array[$i]);
                            $compra->registroContable = $numeroComprobante."*".$idComprobante;
                            $compra->save();
                        }
                    }
                } catch (\Throwable $th) {
                    session()->flash('generar-asientos','error');
                }
            }

            //return count($codigo_debe_array);
            //return $contador_de_comprobantes_creados;
            //! enviamos el mensaje flash por url
            return redirect('/contabilidad/generador-asientos-de-compras?process=search&gestion='.$request->gestionBuscada.'&mes='.$request->mesBuscado.'&sucursal='.$request->idSucursalBuscada.'&cantCompGen='.$contador_de_comprobantes_creados);

            //! http://sistemacontable.test/contabilidad/generador-asientos-de-compras?process=search&gestion=2022&mes=1&sucursal=1

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
