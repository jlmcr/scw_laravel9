@extends('plantilla.plantillaPDF')

@section('titulo')
    Mayor Analítico
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

        .datosComprobante tr,
        .datosComprobante td,
        .datosComprobante th{
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

        .tabla-detalle-mayor{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .tabla-detalle-mayor td,
        .tabla-detalle-mayor th{
            border-spacing: 0;
            border-collapse: collapse;
            border: 0.1px solid #000000;
        }



        /* parrrafo */
        tbody p,
        tfoot p
        {
            padding: 5px 5px 5px 5px;
        }
        .datosComprobante p{
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
                <td colspan="4" style="font-size: 16px">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 13px">
                    MAYOR ANALÍTICO
                    @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                        <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                        <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="4" style="font-size: 16px">
                    NIT: {{$datosEmpresaActiva->nit}}
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 30px"></td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center; font-size: 16px;">
                    LIBRO MAYOR ANALÍTICO
                </td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center; font-size: 15px;">
                    (Expresado en Bolivianos)
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 20px"></td>
            </tr>
        </table>
    </div>
        {{-- datos generales de la cuenta --}}
    <div>
        <table class="datosComprobante" style="font-size: 15px; width: 100%;">
            <tr>
                <th class="izquierda" style="width: 220px;">
                    <p>Código Sub-Cuenta:</p>
                </th>
                <td>
                    <p>{{$datos_Subcuenta[0]->id}}</p>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Nombre Sub-Cuenta:</p>
                </th>
                <td class="azul">
                    <p>{{$datos_Subcuenta[0]->descripcion}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Periodo:</p>
                </th>
                <td>
                    <p>Del: {{date('d/m/Y',strtotime($fechaInicio_buscado))}} al: {{date('d/m/Y',strtotime($fechaFin_buscado))}}</p>
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
            <table class="tabla-detalle-mayor">
                <thead>
                    <tr class="blanco bg-azul" style="font-size: 13px;">

                        <th style="width: 85px; height: 40px">
                            <p>FECHA</p>
                        </th>
                        <th style="width: 80px;">
                            <p>NRO. COMPR.</p>
                        </th>
                        <th style="width: 80px;">
                            <p>TIPO COMPR.</p>
                        </th>
                        <th>
                            <p>CONCEPTO</p>
                        </th>
                        <th style="width:35px">
                            <p>Ref</p>
                        </th>
                        <th style="width: 100px">
                            <p>DEBE</p>
                        </th>
                        <th style="width: 100px">
                            <p>HABER</p>
                        </th>
                        <th style="width: 100px">
                            <p>SALDO</p>
                        </th>
                    </tr>
                </thead>

                <tbody style="font-size: 12px;">
                    @php
                        $saldo=0;
                    @endphp
                    @if (count($registrosEncontrados) > 0)
                        @foreach ($registrosEncontrados as $registro )
                            <tr>
                                <td class="centro" style="height: 10px">
                                    <p>{{ date('d/m/Y', strtotime($registro->fecha)) }}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$registro->nroComprobante}}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$registro->nombre_tipo_comprobante}}</p>
                                </td>
                                <td>
                                    <p>{{$registro->concepto}}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$registro->orden_subcuenta}}</p>
                                </td>

                                {{-- importes --}}
                                {{-- ! debe y haber --}}
                                @php
                                    //saldo
                                    $saldo =$saldo+ $registro->debe - $registro->haber;
                                    //debe
                                    if($registro->debe == 0 || $registro->debe == ""){
                                        $debe = "";
                                    }
                                    else {
                                        $debe = number_format($registro->debe,2,".",",");
                                    }
                                    //haber
                                    if($registro->haber == 0 || $registro->haber == ""){
                                        $haber = "";
                                    }
                                    else {
                                        $haber = number_format($registro->haber,2,".",",");
                                    }
                                @endphp

                                <td class="derecha">
                                    <p>{{$debe}}</p>
                                </td>

                                <td class="derecha">
                                    <p>{{$haber}}</p>
                                </td>

                                <td class="derecha">
                                    <p>{{ number_format($saldo,2,".",",")}}</p>
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <th colspan="8" class="cemtro medio">
                                    <p>SIN MOVIMIENTO</p>
                                </th>
                            </tr>
                    @endif
                </tbody>

                <tfoot>
                    <tr class="blanco bg-azul" style="font-size: 13px;">
                        <th colspan="5" style="height: 30px">
                            <p>SUMAS</p>
                        </th>

                        @php
                            $sumaDebe=0;
                            $sumaHaber=0;
                            foreach ($registrosEncontrados as $registro ) {
                                $sumaDebe = $sumaDebe + $registro->debe;
                                $sumaHaber = $sumaHaber + $registro->haber;
                            }
                            $sumaSaldo = $sumaDebe-$sumaHaber;
                        @endphp
                        <th class="derecha">
                            <p>{{number_format($sumaDebe,2,'.',',')}}</p>
                        </th>
                        <th class="derecha">
                            <p>{{number_format($sumaHaber,2,'.',',')}}</p>
                        </th>
                        <th class="derecha">
                            <p>{{number_format($sumaSaldo,2,'.',',')}}</p>
                        </th>
                    </tr>
                </tfoot>

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

            $pdf->text(750, 568, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);

        ');
    }
</script>

@endsection



