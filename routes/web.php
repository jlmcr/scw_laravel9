<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Empresa\SucursalController;
use App\Http\Controllers\Empresa\EjercicioController;
use App\Http\Controllers\Usuario\UserController;

use App\Http\Controllers\Usuario\PreferenciasUsuarioController;
use App\Http\Controllers\TipoDeCambio\TipoDeCambioController;
use App\Http\Controllers\PlanDeCuentas\PlanDeCuentasController;
use App\Http\Controllers\ConfiguracionSistemaController;

use App\Http\Controllers\Contabilidad\LibroDiarioController;
use App\Http\Controllers\Contabilidad\TipoDeComprobanteController;
use App\Http\Controllers\Contabilidad\ComprobanteController;
use App\Http\Controllers\Contabilidad\Reportes_financieros\ReportesIntermediosController;
use App\Http\Controllers\Contabilidad\Reportes_financieros\EstadosFinancierosBasicosController;

use App\Http\Controllers\Contabilidad\PDF\PdfComprobanteController;
use App\Http\Controllers\Contabilidad\PDF\PdfLibroDiarioController;
use App\Http\Controllers\Contabilidad\PDF\PdfMayoresController;

use App\Http\Controllers\CompraVenta\ConsultasCompraVentaController;
use App\Http\Controllers\CompraVenta\compras\CompraController;
use App\Http\Controllers\CompraVenta\compras\BuscadorCompraController;
use App\Http\Controllers\CompraVenta\ventas\BuscadorVentaController;
use App\Http\Controllers\CompraVenta\ventas\VentaController;
use App\Http\Controllers\PlanDeCuentas\ReportesPlanDeCuentasController;

use App\Http\Controllers\Generadores\GeneradorDeAsientosComprasController;
use App\Http\Controllers\Generadores\GeneradorDeAsientosVentasController;

use App\Http\Controllers\ActivoFijo\RubroController;
use App\Http\Controllers\ActivoFijo\ActivoFijoController;
use App\Http\Controllers\ActivoFijo\DepreciacionController;
use App\Http\Controllers\ActivoFijo\ReportesActivoFijoController;

use App\Http\Controllers\RespaldoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    //funcion anonima, predeterminada
    Route::get('/', function () {
        return view('auth.login');
    });

    //! ruta, predeterminada

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    //! 2da opcion - funciona
    /*
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ])->group(function () {
        Route::resource('dashboard',DashboardController::class);
    });
    */

    //* el tipo de controlador  resource   Route::resource('empresas')
    //* la ruta es empresas
    //* especificar controlador
    //* cuando no se especifica un metodo en especifico se toma el index

    Route::resource('dashboard',DashboardController::class);
    //!usuarios
    Route::resource('usuarios',UserController::class);
    //! empresas
    Route::resource('empresas',EmpresaController::class);
    Route::resource('sucursales',SucursalController::class);
    Route::resource('ejercicios',EjercicioController::class);

    //!compras y ventas
    //! compras
    Route::resource('compras',CompraController::class);
    Route::get('search/editarCompra',[CompraController::class,'editarCompra'])->name('search.editarCompra');
    Route::delete('eliminar-multiples-compras',[CompraController::class,'eliminarMultiplesCompras'])->name('eliminar-multiples-compras');

    /* utilizado por compra.index */
    Route::get('search/nitProveedor',[BuscadorCompraController::class,'nitProveedor'])->name('search.nitProveedor');
    Route::get('search/razonSocialProveedor',[BuscadorCompraController::class,'razonSocialProveedor'])->name('search.razonSocialProveedor');
    Route::get('search/autorizacionCompra',[BuscadorCompraController::class,'autorizacionCompra'])->name('search.autorizacionCompra');

    //! ventas
    Route::resource('ventas',VentaController::class);
    Route::get('search/editarVenta',[VentaController::class,'editarVenta'])->name('search.editarVenta');
    Route::delete('eliminar-multiples-ventas',[VentaController::class,'eliminarMultiplesVentas'])->name('eliminar-multiples-ventas');

    /* utilizado por ventas.index */
    Route::get('search/ciNitCliente',[BuscadorVentaController::class,'ciNitCliente'])->name('search.ciNitCliente');
    Route::get('search/razonSocialCliente',[BuscadorVentaController::class,'razonSocialCliente'])->name('search.razonSocialCliente');
    Route::get('search/autorizacionVenta',[BuscadorVentaController::class,'autorizacionVenta'])->name('search.autorizacionVenta');

    //! reportes compras y ventas */
    Route::get('registro-compras-ventas/consultas',[ConsultasCompraVentaController::class,'index'])->name('rcv-consultas');
    Route::get('registro-compras-ventas/compras-ventas-pdf',[ConsultasCompraVentaController::class,'exportarPdf'])->name('rcv-PDF');

    //! excel compras Y ventas
    Route::post('importar-compras-excel',[ConsultasCompraVentaController::class,'importarComprasDeExcel'])->name('importar-compras-excel');
    Route::post('importar-ventas-excel',[ConsultasCompraVentaController::class,'importarVentasDeExcel'])->name('importar-ventas-excel');
    Route::get('exportar-compras-excel',[ConsultasCompraVentaController::class,'exportarComprasAExcel'])->name('exportar-compras-excel');
    Route::get('exportar-ventas-excel',[ConsultasCompraVentaController::class,'exportarVentasAExcel'])->name('exportar-ventas-excel');


    //! sistema
    Route::get('configuracion-sistema',[ConfiguracionSistemaController::class,'index'])->name('configuracion-sistema.index');
    Route::put('configuracion-sistema/{id}',[ConfiguracionSistemaController::class,'update'])->name('configuracion-sistema.update');


    //! preferencias de usuario
    Route::get('preferencias-usuario',[PreferenciasUsuarioController::class,'index'])->name('preferencias-usuario.index');
    Route::put('preferencias-usuario/{id}',[PreferenciasUsuarioController::class,'update'])->name('preferencias-usuario.update');
    Route::get('aside',[PreferenciasUsuarioController::class,'aside'])->name('preferencias-usuario.aside');

    //! activo fijo
    Route::resource('rubrosActivoFijo',RubroController::class);
    Route::resource('activoFijo',ActivoFijoController::class);
    Route::get('activo-fijo/historial-depreciaciones',[DepreciacionController::class,'index'])->name('historial-depreciaciones');
    //! depreciaciones
    Route::get('activo-fijo/nueva-depreciacion-create',[DepreciacionController::class,'nuevaDepreciacion_create'])->name('nueva-depreciacion.create');
    Route::post('nueva-depreciacion-store',[DepreciacionController::class,'store'])->name('nueva-depreciacion.store');
    Route::get('consulta-ufv',[DepreciacionController::class,'consultaUfvAjax'])->name('consulta-ufv');
    //! reportes de activo fijo
    Route::get('activo-fijo/pdf-cuadro-de-depreciacion',[ReportesActivoFijoController::class,'cuadroDeDepreciacion_pdf'])->name('pdf-cuadro-de-depreciacion');
    Route::get('activo-fijo/pdf-historial-af',[ReportesActivoFijoController::class,'historialActivoFijo_pdf'])->name('pdf-historial-activo-fijo');
    Route::get('activo-fijo/pdf-listado-af',[ReportesActivoFijoController::class,'listadoActivoFijo_pdf'])->name('pdf-listado-activo-fijo');


    Route::get('activo-fijo/excel-listado-af',[ReportesActivoFijoController::class,'listaActivoFijo_Excel'])->name('excel-listado-activo-fijo');
    //Route::get('activo-fijo/excel-cuadro-depreci-af',[ReportesActivoFijoController::class,'CuadroDepreciaciones_Excel'])->name('excel-cuadro-depreciacion-activo-fijo');


    //! Tipos de Cambio
    Route::resource('tipos-de-cambio/ufv',TipoDeCambioController::class)->names('tipoCambio');
    Route::post('importar-tipos-de-cambio',[TipoDeCambioController::class,'importarTiposDeCambio'])->name('importar-tipos-de-cambio');


    //! Comprobante
    Route::resource('contabilidad/comprobante',ComprobanteController::class)->names('comprobante');
    Route::get('contabilidad/numero-comprobante',[ComprobanteController::class,'generarNroComprobante'])->name('numero-comprobante');
    Route::get('contabilidad/imprimir-pdf-comprobante',[PdfComprobanteController::class,'comprobanteIndividual_pdf'])->name('pdf-comprobante-individual'); //! primero 1
    Route::get('contabilidad/pdf-comprobantes',[PdfComprobanteController::class,'comprobantesVarios_pdf'])->name('pdf-comprobantes-varios'); //! similar al libro diario 3

    //! Libro diario
    Route::get('contabilidad/libro-diario',[LibroDiarioController::class,'index'])->name('libro-diario');
    Route::get('search/comprobante',[LibroDiarioController::class,'comprobanteDetalle'])->name('search.ComprobanteDetalle');
    Route::get('contabilidad/pdf-libro-diario',[PdfLibroDiarioController::class,'libroDiario_pdf'])->name('pdf-libro-diario'); //! similar a comprobante individual 2
    Route::get('contabilidad/excel-libro-diario',[PdfLibroDiarioController::class,'libroDiario_excel'])->name('excel-libro-diario');

    //! Mayores
    Route::get('contabilidad/pdf-mayor-analitico',[PdfMayoresController::class,'mayorAnalitico_pdf'])->name('pdf-mayor-analitico');

    //! Reportes Financieros vista, PDF excel
    Route::get('contabilidad/reportes/balance-comprobacion-de-sumas-y-saldos',[ReportesIntermediosController::class,'BalanceDeSumasySaldos'])->name('balance-de-sumas-y-saldos');
    Route::get('contabilidad/reportes/estados-financieros',[EstadosFinancierosBasicosController::class,'eeff_basicos'])->name('estados-financieros');
    //pdf
    Route::get('contabilidad/reportes/pdf-balance-comprobacion-de-sumas-y-saldos',[ReportesIntermediosController::class,'BalanceDeSumasySaldos_pdf'])->name('pdf-balance-de-sumas-y-saldos');
    //excel
    Route::get('contabilidad/reportes/excel-balance-comprobacion-de-sumas-y-saldos',[ReportesIntermediosController::class,'BalanceDeSumasySaldos_excel'])->name('excel-balance-de-sumas-y-saldos');
    Route::get('contabilidad/reportes/excel-bb-gg',[EstadosFinancierosBasicosController::class,'bbgg_excel'])->name('excel-balance-general');
    Route::get('contabilidad/reportes/excel-ee-rr',[EstadosFinancierosBasicosController::class,'eerr_excel'])->name('excel-estado-de-resultados');


    //! Tipos de Comprobante
    Route::resource('contabilidad/tipos-comprobantes',TipoDeComprobanteController::class)->names('tipo-comprobante');

    //! Plan de cuentas
    Route::get('contabilidad/plan-de-cuentas',[PlanDeCuentasController::class,'index'])->name('plan-de-cuentas');
    Route::put('contabilidad/plan-de-cuentas/actualizar-tipo/{id}', [PlanDeCuentasController::class,'actualizarTipo'])->name('actualizar-tipo');
    Route::post('contabilidad/plan-de-cuentas/crear-grupo', [PlanDeCuentasController::class,'crearGrupo'])->name('crear-grupo');
    Route::put('contabilidad/plan-de-cuentas/actualizar-grupo/{id}', [PlanDeCuentasController::class,'actualizarGrupo'])->name('actualizar-grupo');
    Route::post('contabilidad/plan-de-cuentas/crear-subgrupo', [PlanDeCuentasController::class,'crearSubGrupo'])->name('crear-subgrupo');
    Route::put('contabilidad/plan-de-cuentas/actualizar-subgrupo/{id}', [PlanDeCuentasController::class,'actualizarSubGrupo'])->name('actualizar-subgrupo');
    Route::post('contabilidad/plan-de-cuentas/crear-cuenta', [PlanDeCuentasController::class,'crearCuenta'])->name('crear-cuenta');
    Route::put('contabilidad/plan-de-cuentas/actualizar-cuenta/{id}', [PlanDeCuentasController::class,'actualizarCuenta'])->name('actualizar-cuenta');
    Route::post('contabilidad/plan-de-cuentas/crear-subcuenta', [PlanDeCuentasController::class,'crearSubCuenta'])->name('crear-subcuenta');
    Route::put('contabilidad/plan-de-cuentas/actualizar-subcuenta/{id}', [PlanDeCuentasController::class,'actualizarSubCuenta'])->name('actualizar-subcuenta');
    /* Reporte */
    Route::get('contabilidad/plan-de-cuentas/pdf',[ReportesPlanDeCuentasController::class,'planDeCuentas_Pdf'])->name('pdf-plan-de-cuentas');


    //! Generacion de asientos contables automaticos
    Route::get('contabilidad/generador-asientos-de-compras',[GeneradorDeAsientosComprasController::class,'index'])->name('generador-asientos-de-compras.index');
    Route::post('contabilidad/generador-asientos-de-compras/generar-compras',[GeneradorDeAsientosComprasController::class,'store'])->name('generador-asientos-de-compras.store');

    Route::get('contabilidad/generador-asientos-de-ventas',[GeneradorDeAsientosVentasController::class,'index'])->name('generador-asientos-de-ventas.index');
    Route::post('contabilidad/generador-asientos-de-ventas/generar-ventas',[GeneradorDeAsientosVentasController::class,'store'])->name('generador-asientos-de-ventas.store');

    //! graficos
    Route::get('graficos/compras-ventas',[GraficosController::class,'compras_ventas'])->name('grafico-compras-ventas');


    //! respaldo
    Route::post('respaldar/respaldar',[RespaldoController::class,'respaldar'])->name('respaldar.ejecutar');
    Route::get('respaldar/index',[RespaldoController::class,'index'])->name('respaldar.index');




