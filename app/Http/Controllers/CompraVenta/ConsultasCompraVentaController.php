<?php

namespace App\Http\Controllers\CompraVenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Empresa;
use App\Models\Ejercicio;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;

//modelo de exportacion
use App\Exports\ComprasExport;
use App\Exports\VentasExport;
use App\Imports\ComprasImport;
use App\Imports\VentasImport;
use App\Models\ConfiguracionSistema;

class ConsultasCompraVentaController extends Controller
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
            //! Datos unicos de los Años en el sistema
            // SELECT DISTINCT YEAR(fecha) FROM `compras` ORDER BY fecha DESC
            // $aniosCompras = DB::select('SELECT DISTINCT YEAR(fecha) FROM compras ORDER BY fecha DESC');

            $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);
            //return $request->get('idEmpresaActiva');
            return view('modulos.compras_ventas.consultas')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('anioMinimo',$anioMinimo);
        }

        if($request->get('process') == 'search')
        {
            // SELECT * FROM tabla WHERE MONTH(colfecha) = 10 AND YEAR(colfecha) = 2016
            $gestionBuscada = $request->get('gestion');
            $mesBuscado = $request->get('mes');
            $idSucursalBuscada = $request->get('sucursal');
            $idEmpresaActiva = $request->get('idEmpresaActiva');
            $concepto = $request->get('concepto');
            $mostrar_vista = $request->get('mostrar_vista'); //solo para la vista, esta variable no se usa en la vista y evitar usar obtenerlo desde la url en la vista


            if($concepto == "compras"){
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
                                        ->orderBy('compras.fecha','ASC')
                                        ->orderBy('compras.numeroFactura','ASC')
                                        ->get();
                                        //->toSql(); // con tosql se imprime la consulta

                    $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                }
                else{
                    $comprasEncontradas=DB::table('compras')
                                        ->select('compras.*')
                                        ->whereMonth('fecha', $mesBuscado)
                                        ->whereYear('fecha', $gestionBuscada)
                                        ->where('sucursal_id',$idSucursalBuscada)
                                        ->orderBy('compras.fecha','ASC')
                                        ->orderBy('compras.numeroFactura','ASC')
                                        ->get();
                                        //->toSql(); // con tosql se imprime la consulta

                    $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);
                }

                return view('modulos.compras_ventas.consultas')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('comprasEncontradas',$comprasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('concepto',$concepto) //? vista consulta
                ->with('mostrar_vista',$mostrar_vista); //? vista consulta


                /* return $mostrar_vista; */
            }
            if($concepto == "ventas"){
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

                    $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                    //? sin where en sucursal
                    $ultimoCodigoAutorizacion = DB::select("SELECT codigoAutorizacion FROM ventas WHERE id = (select MAX(id) AS id FROM ventas)");

                }
                else{
                    $ventasEncontradas=DB::table('ventas')
                                        ->select('ventas.*')
                                        ->whereMonth('fecha', $mesBuscado)
                                        ->whereYear('fecha', $gestionBuscada)
                                        ->where('sucursal_id',$idSucursalBuscada)
                                        ->orderBy('ventas.fecha','ASC')
                                        ->orderBy('ventas.numeroFactura','ASC')
                                        ->get();
                                        //->toSql(); // con tosql se imprime la consulta

                    $sucursalesDeLaEmpresa= Sucursal::all()->where('empresa_id','=',$request->idEmpresaActiva);

                    $ultimoCodigoAutorizacion = DB::select("SELECT codigoAutorizacion FROM ventas WHERE id = (select MAX(id) AS id FROM ventas) and sucursal_id = ".$idSucursalBuscada);
                }
                return view('modulos.compras_ventas.consultas')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) // este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) // este es para el nombre y ejercicio de la empresa activa
                ->with('sucursalesDeLaEmpresa',$sucursalesDeLaEmpresa)
                ->with('ventasEncontradas',$ventasEncontradas)
                ->with('gestionBuscada',$gestionBuscada)
                ->with('mesBuscado', $mesBuscado)
                ->with('idSucursalBuscada', $idSucursalBuscada)
                ->with('anioMinimo',$anioMinimo)
                ->with('ultimoCodigoAutorizacion',$ultimoCodigoAutorizacion)
                ->with('concepto',$concepto) //? vista consulta
                ->with('mostrar_vista',$mostrar_vista); //? vista consulta

                /* return $ultimoCodigoAutorizacion; */
            }


        }

    }

    public function exportarPdf(Request $request)
    {
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //! Busqueda de todas las empresas
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios
        $ejercicios = Ejercicio::all();
        //?
        $gestionBuscada = $request->get('gestionBuscada');
        $mesBuscado = $request->get('mesBuscado');
        $idSucursalBuscada = $request->get('idSucursalBuscada');
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $concepto = $request->get('concepto');

        if($concepto == "compras"){
            if($idSucursalBuscada == '-1')
            {
                //! compras
                /* varias sucursales */
                $comprasEncontradas=DB::table('compras')
                                    ->join('sucursals','compras.sucursal_id','=','sucursals.id')
                                    ->join('empresas','sucursals.empresa_id','=','empresas.id')
                                    ->select('compras.*','sucursals.descripcion','sucursals.empresa_id','empresas.denominacionSocial')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada) //->where('sucursal_id',$idSucursalBuscada)
                                    ->where('empresas.id','=',$idEmpresaActiva)
                                    ->orderBy('compras.fecha','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                //para el encabezado
                $empresa_encabezado = Empresa::find($idEmpresaActiva);

                $sucursalesDeLaEmpresa=DB::select('SELECT sucursals.id, sucursals.descripcion, COUNT(compras2.sucursal_id) AS cantidad_compras
                FROM sucursals LEFT JOIN (SELECT * FROM compras WHERE year(compras.fecha) = ? AND month(compras.fecha) = ? ) compras2
                ON compras2.sucursal_id = sucursals.id
                WHERE sucursals.empresa_id = ?
                GROUP BY sucursals.id, sucursals.descripcion', [ $gestionBuscada, $mesBuscado, $idEmpresaActiva]);

                //return $sucursalesDeLaEmpresa;

                $pdf = PDF::loadView('modulos.reportes_pdf.compras_reporte_pdf',compact('sucursalesDeLaEmpresa','empresa_encabezado','gestionBuscada','mesBuscado','comprasEncontradas','idSucursalBuscada'));
                $pdf->setPaper('A4', 'landscape'); //landscape horizontal

                //return $pdf->download('compras-pdf.pdf');
                return $pdf->stream('compras-'.$gestionBuscada.'-'.$mesBuscado.'.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
            }
            else{
                /* una sucursal */
                $comprasEncontradas=DB::table('compras')
                                    ->select('compras.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('compras.fecha','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                //para el encabezado
                $empresa_encabezado = Empresa::find($idEmpresaActiva);
                $sucursal_encabezado = Sucursal::find($idSucursalBuscada);

                $pdf = PDF::loadView('modulos.reportes_pdf.compras_reporte_pdf',compact('sucursal_encabezado','empresa_encabezado','gestionBuscada','mesBuscado','comprasEncontradas','idSucursalBuscada'));
                $pdf->setPaper('A4', 'landscape'); //landscape horizontal

                //return $pdf->download('compras-pdf.pdf');
                return $pdf->stream('compras-'.$gestionBuscada.'-'.$mesBuscado.'.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)

            }
        }
        if($concepto == "ventas"){
            if($idSucursalBuscada == '-1')
            {
                //! ventas
                //todas las sucursales
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

                //para el encabezado
                $empresa_encabezado = Empresa::find($idEmpresaActiva);

                $sucursalesDeLaEmpresa=DB::select('SELECT sucursals.id, sucursals.descripcion, COUNT(ventas2.sucursal_id) AS cantidad_ventas
                FROM sucursals LEFT JOIN (SELECT * FROM ventas WHERE year(ventas.fecha) = ? AND month(ventas.fecha) = ? ) ventas2
                ON ventas2.sucursal_id = sucursals.id
                WHERE sucursals.empresa_id = ?
                GROUP BY sucursals.id, sucursals.descripcion', [ $gestionBuscada, $mesBuscado, $idEmpresaActiva]);

                //return $sucursalesDeLaEmpresa;

                $pdf = PDF::loadView('modulos.reportes_pdf.ventas_reporte_pdf',compact('sucursalesDeLaEmpresa','empresa_encabezado','gestionBuscada','mesBuscado','ventasEncontradas','idSucursalBuscada'));
                $pdf->setPaper('A4', 'landscape'); //landscape horizontal

                //return $pdf->download('ventas-pdf.pdf');
                return $pdf->stream('ventas-'.$gestionBuscada.'-'.$mesBuscado.'.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
            }
            else{
                $ventasEncontradas=DB::table('ventas')
                                    ->select('ventas.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('ventas.fecha','ASC')
                                    ->orderBy('ventas.numeroFactura','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

                //para el encabezado
                $empresa_encabezado = Empresa::find($idEmpresaActiva);
                $sucursal_encabezado = Sucursal::find($idSucursalBuscada);

                $pdf = PDF::loadView('modulos.reportes_pdf.ventas_reporte_pdf',compact('sucursal_encabezado','empresa_encabezado','gestionBuscada','mesBuscado','ventasEncontradas','idSucursalBuscada'));
                $pdf->setPaper('A4', 'landscape'); //landscape horizontal

                //return $pdf->download('compras-pdf.pdf');
                return $pdf->stream('ventas-'.$gestionBuscada.'-'.$mesBuscado.'.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)

            }
        }

    }

    //! EXPORT
    public function exportarComprasAExcel(Request $request)
    {
        $gestionBuscada = $request->get('gestionBuscada');
        $mesBuscado = $request->get('mesBuscado');
        $idSucursalBuscada = $request->get('idSucursalBuscada');
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;

        try {

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
                                    ->orderBy('compras.fecha','ASC')
                                    ->orderBy('compras.numeroFactura','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

            }
            else{
                $comprasEncontradas=DB::table('compras')
                                    ->select('compras.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('compras.fecha','ASC')
                                    ->orderBy('compras.numeroFactura','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta
            }

            //session()->flash('generar_excel','sin_datos');

            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new ComprasExport($comprasEncontradas, $idSucursalBuscada), 'compras '.$gestionBuscada.'-'.$mesBuscado.'.xlsx');

        } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            // sistemacontable.test/registro-compras-ventas/consultas?process=menu&idEmpresaActiva=1
            return redirect('/registro-compras-ventas/consultas?process=menu&idEmpresaActiva='.$idEmpresaActiva);
        }
    }

    public function exportarVentasAExcel(Request $request)
    {
        $gestionBuscada = $request->get('gestionBuscada');
        $mesBuscado = $request->get('mesBuscado');
        $idSucursalBuscada = $request->get('idSucursalBuscada');
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;

        try {

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

            }
            else{
                $ventasEncontradas=DB::table('ventas')
                                    ->select('ventas.*')
                                    ->whereMonth('fecha', $mesBuscado)
                                    ->whereYear('fecha', $gestionBuscada)
                                    ->where('sucursal_id',$idSucursalBuscada)
                                    ->orderBy('ventas.fecha','ASC')
                                    ->orderBy('ventas.numeroFactura','ASC')
                                    ->get();
                                    //->toSql(); // con tosql se imprime la consulta

            }

            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new VentasExport($ventasEncontradas, $idSucursalBuscada), 'ventas '.$gestionBuscada.'-'.$mesBuscado.'.xlsx');

        } catch (\Throwable $th) {

            session()->flash('generar_excel','error');

            // sistemacontable.test/registro-compras-ventas/consultas?process=menu&idEmpresaActiva=1
            return redirect('/registro-compras-ventas/consultas?process=menu&idEmpresaActiva='.$idEmpresaActiva);
        }
    }

    //! IMPORT
    public function importarComprasDeExcel(Request $request)
    {
        if( $request->hasFile('archivo')) //? si se sibio el archivo
        {
            $request->validate([
                'archivo'=>'required|mimes:xls,xlsx'
            ]);

            //? varialbles
            //! $gestionBuscada = $request->get('gestionBuscada');
            //! $mesBuscado = $request->get('mesBuscado');
            $idSucursalBuscada = $request->get('idSucursalBuscada');

            //? recuperamos archivo subido archivo
            $archivo =$request->file('archivo');

            //Excel::toCollection(new ComprasImport, $archivo);
            Excel::import(new ComprasImport($idSucursalBuscada), $archivo);
            //Excel::import(new ComprasImport($gestionBuscada, $mesBuscado, $idSucursalBuscada), $archivo);


           // return "importado correctamente";
            //return $gestionBuscada;
            session()->flash('importarExcel','ok'); //* varaiable de sesion
            return back();

        }
    }

    public function importarVentasDeExcel(Request $request)
    {
        if( $request->hasFile('archivo')) //? si se sibio el archivo
        {
            $request->validate([
                'archivo'=>'required|mimes:xls,xlsx'
            ]);

            //? varialbles
            // $gestionBuscada = $request->get('gestionBuscada');
            // $mesBuscado = $request->get('mesBuscado');
            $idSucursalBuscada = $request->get('idSucursalBuscada');

            //? recuperamos archivo subido archivo
            $archivo =$request->file('archivo');

            Excel::import(new VentasImport($idSucursalBuscada), $archivo);
            //Excel::import(new VentasImport($gestionBuscada, $mesBuscado, $idSucursalBuscada), $archivo);

            session()->flash('importarExcel','ok'); //* varaiable de sesion
            return back();

        }
    }

}
