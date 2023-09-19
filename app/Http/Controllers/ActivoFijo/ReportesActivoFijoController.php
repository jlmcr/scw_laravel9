<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use App\Models\ActivoFijo;
use App\Models\Ejercicio;
use App\Models\Empresa;
use App\Models\Rubro;
use App\Models\TipoDeCambio;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListaActivoFijoExport;
use App\Exports\DepreciacionesExport;




class ReportesActivoFijoController extends Controller
{
    //! contructor
    function __construct()
    {
        $this->middleware('auth');
    }

    public function cuadroDeDepreciacion_pdf(Request $request){
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

        //! DATO PARA ENCABEZADO
        $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);
        //datos del request
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $idRubroSeleccionado = $request->get('id_rubro_buscado');
        $idEjercicioSeleccionado = $request->get('id_ejercicio_buscado');


        //! datos para el cuadro de depreciacion
        $rubro_buscado_datos = Rubro::find($idRubroSeleccionado);
        $ejercicio_buscado_datos = Ejercicio::find($idEjercicioSeleccionado);

        $activosFijos_encontrados = DB::table('activos_fijos')
                        ->where('empresa_id','=',$idEmpresaActiva)
                        ->where('rubro_id','=',$idRubroSeleccionado)
                        ->orderBy('id','ASC')
                        ->get();
                        //->toSql();

        $depreciaciones_existentes_en_el_ejercicio = DB::table('depreciaciones')
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','ejercicios.ejercicioFiscal')
                        ->where('ejercicio_id','=',$idEjercicioSeleccionado)
                        ->orderBy('ejercicioFiscal','DESC')
                        ->get();
        $todas_las_depreciaciones_de_la_empresa = DB::table('depreciaciones')
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','ejercicios.ejercicioFiscal','ejercicios.empresa_id')
                        ->where('empresa_id','=',$idEmpresaActiva)
                        ->orderBy('ejercicioFiscal','DESC')
                        ->get();
        //! ufv
        $ufvs = TipoDeCambio::all();

        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.cuadro_de_depreciacion_pdf',compact('datosEmpresaActiva','rubro_buscado_datos','ejercicio_buscado_datos','activosFijos_encontrados','depreciaciones_existentes_en_el_ejercicio','todas_las_depreciaciones_de_la_empresa','ufvs'));
        $pdf->setPaper('A4','landscape'); // portrait vert

        //return $pdf->download();
        return $pdf->stream('cuadro_de_depreciación.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }

    public function historialActivoFijo_pdf(Request $request){
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

        //! DATO PARA ENCABEZADO
        $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);
        $id_activo_seleccionado = $request->get('id_activo_seleccionado'); //aumentado para el pdf
        $datos_activoFijo_seleccionado = ActivoFijo::find($id_activo_seleccionado);
        //datos del request
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $rubroSeleccionado = $request->get('id_rubro_buscado');
        $rubro_buscado = Rubro::find($rubroSeleccionado);


        //$activosFijosEncontrados //! ya no es utilizado por que solo mostramos el historial de un activo

        //! depreciaciones para el HISTORIAL
        $depreciaciones = DB::table('depreciaciones')
                        ->join('activos_fijos','depreciaciones.activoFijo_id','=','activos_fijos.id')//tabla, primera, operador, segundo
                        ->join('ejercicios','depreciaciones.ejercicio_id','=','ejercicios.id')
                        ->select('depreciaciones.*','activos_fijos.rubro_id','activos_fijos.empresa_id','ejercicios.ejercicioFiscal')
                        ->where('activos_fijos.empresa_id','=',$idEmpresaActiva) //activos de la empresa activa
                        ->orderBy('ejercicios.ejercicioFiscal','ASC')
                        ->get();


        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.historial_activo_fijo_pdf',compact('datosEmpresaActiva','rubro_buscado','depreciaciones','datos_activoFijo_seleccionado'));
        $pdf->setPaper('A4','portrait'); // portrait vert

        //return $pdf->download();
        return $pdf->stream('item_activo_fijo.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }

    public function listadoActivoFijo_pdf(Request $request){
        /* https://github.com/dompdf/dompdf */
        /* https://www.srcodigofuente.es/aprender-php/guia-dompdf-completa */
        //?

        //! DATO PARA ENCABEZADO
        $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);

        //datos del request
        $idEmpresaActiva = auth()->user()->idEmpresaActiva;
        $rubroSeleccionado = $request->get('id_rubro_buscado');

        if($rubroSeleccionado == '-1')
        {
            $rubro_buscado = "todos";

            //! para el select del buscador
            //! rubros con la cantidad de activos deoendientes en la empresa activa
            $rubros = DB::select(
                "SELECT rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
                COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
                FROM rubros
                LEFT JOIN(
                    SELECT * FROM activos_fijos WHERE activos_fijos.empresa_id = ?
                ) AS activos_fijos
                ON rubros.id = activos_fijos.rubro_id
                GROUP BY
                rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum,
                rubros.sujetoAdepreciacion", [$idEmpresaActiva]);

            //!con eloquente (con los modelos) probamos las relaciones desde los modelos
            $activosFijosEncontrados = ActivoFijo::where('empresa_id','=',$idEmpresaActiva)
                            ->where('rubro_id','<>',null)
                            ->orderBy('id','ASC')
                            ->get();
        }
        else
        {
            //?$rubroBuscado = Rubro::where('id','=','$rubroSeleccionado')->get();
            $rubro_buscado = Rubro::find($rubroSeleccionado);

            //! para el select del buscador
            //! rubros con la cantidad de activos deoendientes en la empresa activa
            $rubros = DB::select(
                "SELECT rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
                COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
                FROM rubros
                LEFT JOIN(
                    SELECT * FROM activos_fijos WHERE activos_fijos.empresa_id = ?
                ) AS activos_fijos
                ON rubros.id = activos_fijos.rubro_id
                GROUP BY
                rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum,
                rubros.sujetoAdepreciacion", [$idEmpresaActiva]);

            $activosFijosEncontrados = DB::table('activos_fijos')
                            ->where('empresa_id','=',$idEmpresaActiva)
                            ->where('rubro_id','=',$rubroSeleccionado)
                            ->orderBy('id','ASC')
                            ->get();
                            //->toSql();
        }


        // https://stackoverflow.com/questions/tagged/dompdf
        $pdf = PDF::loadView('modulos.reportes_pdf.listado_activo_fijo_pdf',compact('datosEmpresaActiva', 'rubros', 'rubroSeleccionado', 'rubro_buscado', 'activosFijosEncontrados'));
        $pdf->setPaper('A4','portrait'); // portrait vert

        //return $pdf->download();
        return $pdf->stream('listado_activo_fijo.pdf'); //previsualiza stream(NOMBRE DEL ARCHIVO A DESCARGAR)
    }

    public function listaActivoFijo_Excel(Request $request){

        /* try
        { */


            //! DATO PARA ENCABEZADO
            $datosEmpresaActiva = Empresa::find(auth()->user()->idEmpresaActiva);

            //datos del request
            $idEmpresaActiva = auth()->user()->idEmpresaActiva;
            $rubroSeleccionado = $request->get('id_rubro_buscado');

            if($rubroSeleccionado == '-1')
            {
                $rubro_buscado = "todos";

                //! para el select del buscador
                //! rubros con la cantidad de activos deoendientes en la empresa activa
                $rubros = DB::select(
                    "SELECT rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
                    COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
                    FROM rubros
                    LEFT JOIN(
                        SELECT * FROM activos_fijos WHERE activos_fijos.empresa_id = ?
                    ) AS activos_fijos
                    ON rubros.id = activos_fijos.rubro_id
                    GROUP BY
                    rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum,
                    rubros.sujetoAdepreciacion", [$idEmpresaActiva]);

                //!con eloquente (con los modelos) probamos las relaciones desde los modelos
                $activosFijosEncontrados = ActivoFijo::where('empresa_id','=',$idEmpresaActiva)
                                ->where('rubro_id','<>',null)
                                ->orderBy('id','ASC')
                                ->get();
            }
            else
            {
                //?$rubroBuscado = Rubro::where('id','=','$rubroSeleccionado')->get();
                $rubro_buscado = Rubro::find($rubroSeleccionado);

                //! para el select del buscador
                //! rubros con la cantidad de activos deoendientes en la empresa activa
                $rubros = DB::select(
                    "SELECT rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum, rubros.sujetoAdepreciacion,
                    COUNT(activos_fijos.rubro_id) AS cantidad_activos_registrados
                    FROM rubros
                    LEFT JOIN(
                        SELECT * FROM activos_fijos WHERE activos_fijos.empresa_id = ?
                    ) AS activos_fijos
                    ON rubros.id = activos_fijos.rubro_id
                    GROUP BY
                    rubros.id, rubros.rubro, rubros.aniosVidaUtil, rubros.codCntaActivo, rubros.codCntaDepreciacion, rubros.codCntaDepreciacionAcum,
                    rubros.sujetoAdepreciacion", [$idEmpresaActiva]);

                $activosFijosEncontrados = DB::table('activos_fijos')
                                ->where('empresa_id','=',$idEmpresaActiva)
                                ->where('rubro_id','=',$rubroSeleccionado)
                                ->orderBy('id','ASC')
                                ->get();
                                //->toSql();
            }




            //descarga la configuracion que tengo en esa clase o modelo
            return Excel::download(new ListaActivoFijoExport($datosEmpresaActiva, $rubros, $rubroSeleccionado, $rubro_buscado, $activosFijosEncontrados), 'lista_activo_fijo.xlsx');

        /* } catch (\Throwable $th) {
            session()->flash('generar_excel','error');
            return redirect(route('libro-diario'));
        } */
    }


    public function CuadroDepreciaciones_Excel(Request $request){

    }

}
