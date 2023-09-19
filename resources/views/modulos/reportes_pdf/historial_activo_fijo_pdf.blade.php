@extends('plantilla.plantillaPDF')

@section('titulo')
    Historial activo fijo
@endsection

@section('css')
    <style>
        html {
            margin: 200pt 38pt 32pt 40pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
            //aumentamos el margin top para que el encabezado no se sobreponga al contenido
            //y para eso tambien en al header le poner -xx en top
            // esto aplica para el caso de uso en en cabezado limitado por el margin, en el caso de que quiera un header de ancho completo es distinto
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }

        /* encabezados de tablas y hoja */

        .tabla-encabezado {
            width: 100%;
            font-weight: bold;
            margin: 0px;
        }
        .azul{
            color: rgb(28, 28, 145);
        }

        .bg-azul{
            background-color: rgb(68, 68, 187);
        }
        .blanco{
            color: white;
        }

        .datosEncabezado_2 tr,
        .datosEncabezado_2 td,
        .datosEncabezado_2 th{
            border-spacing: 0;
            width: 100%;
        }

        //CONTENIDO
        .encabezado-de-tabla,
        .footer-de-tabla{
            color: rgb(28, 28, 145);
        }

        /* texto */
        .centro {
            text-align: center;
        }

        .izquierda {
            text-align: left;
        }

        .derecha {
            text-align: right;
        }

        .arriba{
            vertical-align: text-top;
        }

        .abajo{
            vertical-align: text-bottom;
        }

        .medio{
            vertical-align: middle;
        }

        /* tabla */

        .tabla-detalle{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .tabla-detalle td,
        .tabla-detalle th{
            border-spacing: 0;
            border-collapse: collapse;
            border: 0.1px solid #000000;
        }



        /* parrrafo */
        thead p,
        tbody p,
        tfoot p
        {
            padding: 5px 5px 5px 5px;
        }
        .datosEncabezado_2 p{
            padding: 0px 5px 2px 5px;
        }
    </style>

    <style>
        /* saltos de pagina */
        .page_break {
            page-break-before: always;
        }

        header {
                position: fixed;
                top: -220px;
                left: 0px;
                right: 0px;
                height: 60px;

                /** Extra personal styles **/
                background-color: white;
            }
    </style>
@endsection

@section('contenido')

<header>
    {{-- ! Encabezado --}}
    <div class="div-encabezados">
        <table class="tabla-encabezado azul">
            <tr>
                <td colspan="4" style="font-size: 14px">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 12px; width: 200px">
                    ACTIVO FIJO
                    @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                        <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                        <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="4" style="font-size: 14px">
                    NIT: {{$datosEmpresaActiva->nit}}
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 5px"></td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center; font-size: 15px;">
                    ACTIVO FIJO - HOJA DE DEPRECIACIONES
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 5px"></td>
            </tr>
        </table>
    </div>
        {{-- datos generales de la cuenta --}}
    <div>
        <table class="datosEncabezado_2" style="font-size: 11px; width: 100%;">
            <tr>
                <th class="izquierda" style="width: 140px;">
                    <p>Categoría o Rubro:</p>
                </th>
                <td class="azul">
                    <p>{{$rubro_buscado->rubro}}</p>
                </td>
                <th class="izquierda">
                    <p>Años de Vida Útil:</p>
                </th>
                <td>
                    <p>{{$rubro_buscado->aniosVidaUtil}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Item:</p>
                </th>
                <td class="azul">
                    <p>{{$datos_activoFijo_seleccionado->id}}</p>
                </td>
                <th class="izquierda">
                    <p>Cantidad:</p>
                </th>
                <td>
                    <p>{{$datos_activoFijo_seleccionado->cantidad}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Descripción Item:</p>
                </th>
                <td colspan="3" class="azul">
                    <p>{{$datos_activoFijo_seleccionado->activoFijo}}</p>
                </td>
            </tr>

            <tr>
                <th class="izquierda">
                    <p>Medida:</p>
                </th>
                <td colspan="3">
                    <p>{{$datos_activoFijo_seleccionado->medida}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Situación:</p>
                </th>
                <td colspan="3">
                    <p>{{$datos_activoFijo_seleccionado->situacion}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Estado:</p>
                </th>
                <td colspan="3">
                    <p>{{$datos_activoFijo_seleccionado->estadoAF}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Unidad Monetaria:</p>
                </th>
                <td colspan="3">
                    <p>Bolivianos</p>
                </td>
            </tr>
            <tr>
                <td style="height: 4px"></td>
                <td></td>
            </tr>
        </table>
    </div>
    {{-- ! Fin Encabezado --}}
</header>

<main>

    {{-- ! Contenido --}}
    <section class="content">
        <div class="contenido">
            <br>
            <table class="tabla-detalle">
                <thead>
                    <tr class="blanco bg-azul" style="font-size: 10px;">
                        <th rowspan="2" style="height: 45px">
                            <p>Ejercicio</p>
                        </th>
                        <th colspan="2">
                            <p>Valores al Iniciar Ejercicio</p>
                        </th>
                        <th colspan="3">
                            <p>Reexpresión y Depreciación</p>
                        </th>
                        <th colspan="3">
                            <p>Valores al Finalizar Ejercicio</p>
                        </th>
                    </tr>

                    <tr class="blanco bg-azul" style="font-size: 10px;">
                        <th>
                            <p>Valor Inicial del Bien</p>
                        </th>
                        <th>
                            <p>Dep. Acum. Inicial</p>
                        </th>
                        <th>
                            <p>Reexp.</p>
                        </th>
                        <th>
                            <p>Meses Depreciados</p>
                        </th>
                        <th>
                            <p>Importe Depreciación</p>
                        </th>
                        <th>
                            <p>Valor Final del Bien</p>
                        </th>
                        <th>
                            <p>Dep. Acum. Final</p>
                        </th>
                        <th>
                            <p>Valor Neto</p>
                        </th>
                    </tr>
                </thead>

                <tbody style="font-size: 10px">

                        {{--! historial de depresiaciones --}}
                        @foreach ($depreciaciones as $depreciacion )
                            @if ($datos_activoFijo_seleccionado->id == $depreciacion->activoFijo_id)
                            <tr>
                                <td style="height: 30px">
                                    <p class="centro">{{$depreciacion->ejercicioFiscal}}</p>
                                </td>

                                <td class="derecha">
                                    <p>{{number_format($depreciacion->valorInicial_depr,2,'.',',')}}</p>
                                </td>
                                <td class="derecha">
                                    <p>{{number_format($depreciacion->depAcumInicial_depr,2,'.',',')}}</p>
                                </td>

                                @if ($depreciacion->reexpresar == 1)
                                    <td class="centro">
                                        <p>Si</p>
                                    </td>
                                    @else
                                    <td class="centro">
                                        <p>No</p>
                                    </td>
                                @endif

                                <td class="centro">
                                    {{number_format($depreciacion->meses,2,'.',',')}}
                                </td>

                                <td class="derecha">
                                    @php
                                        $deprecBien =0;
                                        $valorFinalBien = $depreciacion->valorFinal_depr;
                                        $meses = $depreciacion->meses;
                                        $porcentaje = 1 / $rubro_buscado->aniosVidaUtil; //1 = 100%
                                        $depreciacionBien = (($valorFinalBien * $porcentaje)/12) * $meses;
                                    @endphp
                                    <p>{{number_format($depreciacionBien,2,'.',',')}}</p>
                                </td>
                                <td class="derecha">
                                    <p>{{number_format($depreciacion->valorFinal_depr,2,'.',',')}}</p>
                                </td>
                                <td class="derecha">
                                    <p>{{number_format($depreciacion->depAcumFinal_depr,2,'.',',')}}</p>
                                </td>
                                <td class="derecha">
                                    <p>{{number_format($depreciacion->valorFinal_depr-$depreciacion->depAcumFinal_depr,2,'.',',')}}</p>
                                </td>
                            </tr>
                            @endif
                        @endforeach

                </tbody>
            </table>
        </div>
    </section>
    {{-- ! Fin Contenido --}}

</main>

{{-- $pdf->text(370, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10); --}}
{{-- x,y --}}
{{-- 500,810,  vertical--}}
{{-- 510,55 --}}
{{-- $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 8,array(0,0,0)); --}}

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");

            $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);

        ');
    }
</script>

@endsection



