<?php

namespace App\Http\Controllers\Contabilidad\Reportes_financieros;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\Ejercicio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\SumasySaldosExport;
//use Illuminate\Support\Facades\Redirect;

class ReportesIntermediosController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function BalanceDeSumasySaldos(Request $request)
    {
        //! Busqueda de todas subcuentas para mayores
        $sub_cuentas = DB::select('SELECT pc_sub_cuenta.id, pc_sub_cuenta.cuenta_id, pc_partida_contable.*
        FROM pc_sub_cuenta
        INNER JOIN pc_partida_contable ON pc_sub_cuenta.codigo_partida = pc_partida_contable.codigo
        WHERE estado = 1 ORDER BY id ASC, correlativo ASC');
        //! Busqueda de todas las empresas, las validaciones de altas y bajas se hacen en la vista
        $empresas = Empresa::all();
        //! Busqueda de todas los ejercicios, las validaciones de altas y bajas se hacen en la vista
        $ejercicios = Ejercicio::all();

        if($request->get('process') == 'search')
        {
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            $fechaInicio_buscado = $request->get('fechaInicio');
            $fechaFin_buscado = $request->get('fechaFin');

            //! POR SI LA FECHA ESTA FUERA DEL EJERCICIO
            if($fechaInicio_buscado < $datosEjercicioActivo->fechaInicio || $fechaFin_buscado > $datosEjercicioActivo->fechaCierre )
            {
                session()->flash('error','fechas_de_busqueda');

                return view('modulos.contabilidad.reportes_financieros.suma-y-saldos')
                    ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                    ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                    ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                    ->with('datosEjercicioActivo',$datosEjercicioActivo)
                    ->with('fechaInicio_buscado',$fechaInicio_buscado)
                    ->with('fechaFin_buscado',$fechaFin_buscado);

            }
            else
            {
                //! BUSQUEDA PESONALIZADA
                $registrosBCSS_entontrados=DB::select("SELECT
                    detalle_comprobante.subcuenta_id,
                    pc_partida_contable.descripcion,
                    SUM(detalle_comprobante.debe) AS suma_debe,
                    SUM(detalle_comprobante.haber) AS suma_haber,
                    pc_tipo.id as id_tipo,
                    pc_tipo.descripcion as descripcion_tipo
                FROM detalle_comprobante
                INNER JOIN pc_sub_cuenta ON pc_sub_cuenta.id = detalle_comprobante.subcuenta_id
                INNER JOIN pc_partida_contable ON pc_partida_contable.codigo = pc_sub_cuenta.codigo_partida
                INNER JOIN pc_tipo ON pc_tipo.id = pc_partida_contable.tipo_id
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY detalle_comprobante.subcuenta_id, pc_partida_contable.descripcion,id_tipo,descripcion_tipo
                ORDER BY detalle_comprobante.subcuenta_id ASC, pc_tipo.id ASC",[$fechaInicio_buscado,$fechaFin_buscado,$idEjercicioActivo]);

                //return $registrosBCSS_entontrados;

                return view('modulos.contabilidad.reportes_financieros.suma-y-saldos')
                ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                ->with('datosEjercicioActivo',$datosEjercicioActivo)
                ->with('fechaInicio_buscado',$fechaInicio_buscado)
                ->with('fechaFin_buscado',$fechaFin_buscado)
                ->with('registrosBCSS_entontrados',$registrosBCSS_entontrados);
            }
        }
        else
        {
            //! BUSQUEDA DE TODO PREDETERMINADA DE TODO EL EJERCICIO
            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            $fechaInicio_buscado = $datosEjercicioActivo->fechaInicio;
            $fechaFin_buscado = $datosEjercicioActivo->fechaCierre;

            $registrosBCSS_entontrados=DB::select("SELECT
                detalle_comprobante.subcuenta_id,
                pc_partida_contable.descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                pc_tipo.id as id_tipo,
                pc_tipo.descripcion as descripcion_tipo
            FROM detalle_comprobante
            INNER JOIN pc_sub_cuenta ON pc_sub_cuenta.id = detalle_comprobante.subcuenta_id
            INNER JOIN pc_partida_contable ON pc_partida_contable.codigo = pc_sub_cuenta.codigo_partida
            INNER JOIN pc_tipo ON pc_tipo.id = pc_partida_contable.tipo_id
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY detalle_comprobante.subcuenta_id, pc_partida_contable.descripcion,id_tipo,descripcion_tipo
            ORDER BY detalle_comprobante.subcuenta_id ASC, pc_tipo.id ASC",[$fechaInicio_buscado,$fechaFin_buscado,$idEjercicioActivo]);

            return view('modulos.contabilidad.reportes_financieros.suma-y-saldos')
                       ->with('sub_cuentas',$sub_cuentas) //para modal mayores
                        ->with('empresas',$empresas) //este es para el nombre y ejercicio de la empresa activa
                        ->with('ejercicios',$ejercicios) //este es para el nombre y ejercicio de la empresa activa
                        ->with('datosEjercicioActivo',$datosEjercicioActivo)
                        ->with('fechaInicio_buscado',$fechaInicio_buscado)
                        ->with('fechaFin_buscado',$fechaFin_buscado)
                        ->with('registrosBCSS_entontrados',$registrosBCSS_entontrados);

            //return $registrosBCSS_entontrados;
        }
    }

    public function BalanceDeSumasySaldos_pdf(Request $request)
    {
        //! DATOS
        $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);

        $idEjercicioActivo = auth()->user()->idEjercicioActivo;
        $datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
        $fechaInicio_buscado = $request->get('fechaInicio_buscado');
        $fechaFin_buscado = $request->get('fechaFin_buscado');

        $registrosBCSS_entontrados=DB::select("SELECT
            detalle_comprobante.subcuenta_id,
            pc_partida_contable.descripcion,
            SUM(detalle_comprobante.debe) AS suma_debe,
            SUM(detalle_comprobante.haber) AS suma_haber,
            pc_tipo.id as id_tipo,
            pc_tipo.descripcion as descripcion_tipo
            FROM detalle_comprobante
            INNER JOIN pc_sub_cuenta ON pc_sub_cuenta.id = detalle_comprobante.subcuenta_id
            INNER JOIN pc_partida_contable ON pc_partida_contable.codigo = pc_sub_cuenta.codigo_partida
            INNER JOIN pc_tipo ON pc_tipo.id = pc_partida_contable.tipo_id
            INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
            WHERE comprobantes.fecha BETWEEN ? AND ?
            AND comprobantes.ejercicio_id = ?
            AND comprobantes.estado = 1
            GROUP BY detalle_comprobante.subcuenta_id, pc_partida_contable.descripcion,id_tipo,descripcion_tipo
            ORDER BY detalle_comprobante.subcuenta_id ASC, pc_tipo.id ASC",[$fechaInicio_buscado,$fechaFin_buscado,$idEjercicioActivo]);

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.pdf_sumas_y_saldos',compact('datosEmpresaActiva','fechaInicio_buscado','fechaFin_buscado','registrosBCSS_entontrados'));
        $pdf->setPaper('A4','portrait'); //landscape horizontal

        //return $pdf->download();
        return $pdf->stream('sumas_y_saldos.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)

    }

    public function BalanceDeSumasySaldos_excel(Request $request)
    {
        try {
            //! DATOS
            $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);

            $idEjercicioActivo = auth()->user()->idEjercicioActivo;
            //$datosEjercicioActivo = Ejercicio::find($idEjercicioActivo);
            $fechaInicio_buscado = $request->get('fechaInicio_buscado');
            $fechaFin_buscado = $request->get('fechaFin_buscado');

            $registrosBCSS_entontrados=DB::select("SELECT
                detalle_comprobante.subcuenta_id,
                pc_partida_contable.descripcion,
                SUM(detalle_comprobante.debe) AS suma_debe,
                SUM(detalle_comprobante.haber) AS suma_haber,
                pc_tipo.id as id_tipo,
                pc_tipo.descripcion as descripcion_tipo
                FROM detalle_comprobante
                INNER JOIN pc_sub_cuenta ON pc_sub_cuenta.id = detalle_comprobante.subcuenta_id
                INNER JOIN pc_partida_contable ON pc_partida_contable.codigo = pc_sub_cuenta.codigo_partida
                INNER JOIN pc_tipo ON pc_tipo.id = pc_partida_contable.tipo_id
                INNER JOIN comprobantes ON comprobantes.id = detalle_comprobante.comprobante_id
                WHERE comprobantes.fecha BETWEEN ? AND ?
                AND comprobantes.ejercicio_id = ?
                AND comprobantes.estado = 1
                GROUP BY detalle_comprobante.subcuenta_id, pc_partida_contable.descripcion,id_tipo,descripcion_tipo
                ORDER BY detalle_comprobante.subcuenta_id ASC, pc_tipo.id ASC",[$fechaInicio_buscado,$fechaFin_buscado,$idEjercicioActivo]);


            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new SumasySaldosExport($datosEmpresaActiva, $fechaInicio_buscado, $fechaFin_buscado, $registrosBCSS_entontrados), 'sumas_y_saldos.xlsx');

        } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            return redirect(route('balance-de-sumas-y-saldos'));
        }

    }

}
