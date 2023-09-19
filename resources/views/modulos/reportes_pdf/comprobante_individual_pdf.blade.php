@extends('plantilla.plantillaPDF')

@section('titulo')
    Comprobante Contable
@endsection

@section('css')
    <style>
        html {
            margin: 250pt 38pt 32pt 40pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
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

        .tabla-comprobante{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .tabla-comprobante td,
        .tabla-comprobante th{
            border-spacing: 0;
            border-collapse: collapse; //una linea en bordes
        }

        .tabla-comprobante th{
            border: 0.1px solid #000000;
        }

        .tabla-comprobante td{
            border-left: 0.1px solid #000000;
            border-right: 0.1px solid #000000;
        }

        #firma th,
        #firma tr{
            border-collapse: collapse; //una linea en bordes
            border-spacing: 0;
            border:0;

            font-size:14px;
        }


        /* parrrafo */
        tbody p,
        tfoot p
        {
            padding: 0px 5px 1px 5px;
        }
    </style>

    <style>
        /* saltos de pagina */
        .page_break {
            page-break-before: always;
        }

        header {
                position: fixed;
                top: -300px;
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
                        {{$datosEmpresa->denominacionSocial}}
                    </td>
                    <td rowspan="2" class="izquierda" style="text-align: right; font-size: 13px">
                        COMPROBANTE CONTABLE
                        @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                            <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                            <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-size: 16px">
                        NIT: {{$datosEmpresa->nit}}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" style="height: 30px"></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center; font-size: 16px;">
                        COMPROBANTE DE {{$datosGeneralesComprobante->tipo->nombre}}
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
        <br>
        <div>
            <table class="datosComprobante" style="font-size: 15px; width: 100%;">
                <tr>
                    <th class="izquierda" style="width: 220px;">
                        <p>Número de Comprobante:</p>
                    </th>
                    <td class="medio" style="width: 320px;">
                        <p>{{$datosGeneralesComprobante->nroComprobante}}</p>
                    </td>

                    <th class="izquierda">
                        <p>Fecha:</p>
                    </th>
                    <td class="izquierda">
                        @php
                            $fecha = date('d/m/Y', strtotime($datosGeneralesComprobante->fecha));
                        @endphp
                        <p>{{$fecha}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="height: 4px"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th class="izquierda" style="width: 170px; vertical-align: text-top;" >
                        <p>Concepto:</p>
                    </th>
                    <td colspan="3">
                        <p>{{$datosGeneralesComprobante->concepto}}</p>
                    </td>
                </tr>
                <tr>
                    <th class="izquierda" style="width: 170px;">
                        <p>Documento:</p>
                    </th>
                    <td colspan="3">
                        <p>{{$datosGeneralesComprobante->documento}}</p>
                    </td>
                </tr>
                <tr>
                    <th class="izquierda">
                        <p>Número Documento:</p>
                    </th>
                    <td colspan="3">
                        <p>{{$datosGeneralesComprobante->numeroDocumento}}</p>
                    </td>
                </tr>
            </table>
        </div>
        {{-- ! Fin Encabezado --}}
    </header>

    <main>

        {{-- ! Contenido --}}
        <section class="content">
            <div class="contenido">
                {{-- tabla nivel 1 --}}
                <table class="tabla-comprobante">
                    <thead>
                        <tr class="azul" style="font-size: 16px;">
                            <th style="width: 100px; height: 30px">
                                <p>CÓDIGO</p>
                            </th>
                            <th>
                                <p>CUENTA</p>
                            </th>
                            <th style="width: 100px">
                                <p >DEBE</p>
                            </th>
                            <th style="width: 100px">
                                <p >HABER</p>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td style="height: 10px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ( $detalleComprobante as $detalle)

                            <tr style="font-size: 15px;">
                                <td>
                                    {{--! codigo cuenta --}}
                                    @php
                                        $contador=0;
                                    @endphp
                                    @foreach ($cuentasDetalle as $cuenta)
                                        @if($cuenta->id == $detalle->cuenta_id)
                                            @if ($contador==0)
                                            <p>{{$cuenta->id}}</p>
                                            @php
                                                $contador=$contador+1;
                                            @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    {{--! nombre  cuenta --}}
                                    @php
                                        $contador=0;
                                    @endphp
                                    @foreach ($cuentasDetalle as $cuenta)
                                        @if($cuenta->id == $detalle->cuenta_id)
                                            @if ($contador==0)
                                            <p style="text-decoration: underline">{{$cuenta->descripcion}}</p>
                                            @php
                                                $contador=$contador+1;
                                            @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr style="font-size: 15px;">
                                <td style="height:30px ;">
                                    {{--! codigo sub cuenta --}}
                                    <p>{{$detalle->codigo}}</p>
                                </td>
                                <td>
                                    {{--! nombre sub cuenta --}}
                                    <p>{{$detalle->descripcion}}</p>
                                </td>

                                {{-- ! debe y haber --}}
                                @php
                                    //debe
                                    if($detalle->debe == 0 || $detalle->debe == ""){
                                        $debe = "";
                                    }
                                    else {
                                        $debe = number_format($detalle->debe,2,".",",");
                                    }
                                    //haber
                                    if($detalle->haber == 0 || $detalle->haber == ""){
                                        $haber = "";
                                    }
                                    else {
                                        $haber = number_format($detalle->haber,2,".",",");
                                    }
                                @endphp
                                <td class="derecha">
                                    <p>{{$debe}}</p>
                                </td>
                                <td class="derecha">
                                    <p>{{$haber}}</p>
                                </td>
                            </tr>

                            <tr>
                                <td style="height: 10px"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="height: 10px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr class="azul" style="font-size: 16px;">
                            <th colspan="2" style="height: 30px">
                                <p>TOTALES</p>
                            </th>

                            @php
                                $sumaDebe=0;
                                $sumaHaber=0;
                                foreach ($detalleComprobante as $detalle) {
                                    $sumaDebe = $sumaDebe + $detalle->debe;
                                    $sumaHaber = $sumaHaber + $detalle->haber;
                                }
                            @endphp
                            <th class="derecha">
                                <p>{{number_format($sumaDebe,2,'.',',')}}</p>
                            </th>
                            <th class="derecha">
                                <p>{{number_format($sumaHaber,2,'.',',')}}</p>
                            </th>
                        </tr>
                        <tr class="azul" style="font-size: 15px;">
                            <th style="height: 30px">
                                <p>SON:</p>
                            </th>
                            <th colspan="3" style="text-align: left">
                                <p>{{$total_literal}}</p>
                            </th>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <table id="firma" style="width: 100%">
                                    <tr>
                                        <th style="height: 150px; width: 50%; vertical-align: text-bottom; padding-top: 5px">
                                            <p>REALIZADO POR:</p>
                                        </th>
                                        <th style="vertical-align: text-bottom; padding-top: 5px">
                                            <p>AUTORIZADO POR:</p>
                                        </th>
                                    </tr>
                                </table>
                            </td>
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
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");

                $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);

            ');
        }
    </script>

@endsection



