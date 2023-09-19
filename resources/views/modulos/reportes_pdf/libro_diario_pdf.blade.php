@extends('plantilla.plantillaPDF')

@section('titulo')
    Libro Diario
@endsection

@section('css')

    <style>
        html {
            margin: 150pt 38pt 32pt 40pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
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

        .tabla-libro-diario{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .tabla-libro-diario td,
        .tabla-libro-diario th{
            border-spacing: 0;
            border-collapse: collapse; //una linea en bordes
        }

        .tabla-libro-diario th{
            border: 0.1px solid #000000;
        }

        .tabla-libro-diario td{
            border-left: 0.1px solid #000000;
            border-right: 0.1px solid #000000;
        }


        /* parrrafo */
        tbody p,
        tfoot p
        {
            padding: 2px 3px 1px 3px;
        }

        hr{
            border: 0.5px rgb(28, 28, 145);
        }
        #separador-uno{
            width: 100%;
            height: 1.1px;
            background-color: rgb(28, 28, 145);
            margin-bottom:1px
        }
        #separador-dos{
            width: 100%;
            height: 0.7px;
            background-color: rgb(28, 28, 145);
        }
    </style>

    <style>
        /* saltos de pagina */
        .page_break {
            page-break-before: always;
        }

        header {
                position: fixed;
                top: -160px;
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
        <table class="tabla-encabezado azul" style="font-size: 14px">
            <tr>
                <td colspan="4">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 12px">
                    <p>LIBRO DIARIO</p>
                    @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                        <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                        <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    NIT: {{$datosEmpresaActiva->nit}}
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 30px"></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center">
                    LIBRO DIARIO
                </td>
            </tr>
            <tr>
                <th colspan="5">
                    <p>Del: {{date('d/m/Y', strtotime($fechaInicio_buscado))}}  al: {{date('d/m/Y', strtotime($fechaFin_buscado))}}</p>
                </th>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center; font-size: 12px;">
                    (Expresado en Bolivianos)
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
            @foreach ( $comprobantesEncontrados as $comprobante)

            <div id="separador-uno"></div>
            <div id="separador-dos"></div>

            {{--! datos generales --}}
            <table style="width:100%; font-size: 12px">
                <tr>
                    <td style="width: 50px">
                    </td>
                    <td style="width: 280px">
                    </td>
                    <th class="derecha" style="width: 200px">
                        <p>Número Documento:</p>
                    </th>
                    <td>
                        <p>{{$comprobante->nroComprobante}}</p>
                    </td>
                </tr>
                <tr>
                    <th class="izquierda">
                        <p>Fecha:</p>
                    </th>
                    <td>
                        <p>{{date('d/m/Y', strtotime($comprobante->fecha))}}</p>
                    </td>
                    <th class="derecha">
                        <p>Tipo Documento:</p>
                    </th>
                    <td>
                        <p>{{$comprobante->tipo->nombre}}</p>
                    </td>
                </tr>
                <tr>
                    <th class="izquierda arriba" style="width: 50px">
                        <p>Concepto:</p>
                    </th>
                    <td colspan="3">
                        <p>{{$comprobante->concepto}}</p>
                    </td>
                </tr>
            </table>

            {{--! cuentas y subcuentas --}}
            <table class="tabla-libro-diario">
                <thead>
                    <tr class="blanco bg-azul" style="font-size: 13px;">
                        <th style="width: 100px; height: 20px">
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
                {{--! cuerpo del asiento --}}
                <tbody style="font-size: 12px">
                        @foreach ($detalleComprobante as $detalle )
                            @if ($detalle->comprobante_id == $comprobante->id)
                                @foreach ($cuentasDetalle as $cuenta)
                                    @if ($cuenta->id == $detalle->cuenta_id)
                                        <tr>
                                            <td>
                                                <p style="text-decoration: underline">{{$cuenta->codigo}}</p>
                                            </td>
                                            <td>
                                                <p style="text-decoration: underline">{{$cuenta->descripcion}}</p>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td style="padding-bottom: 7px">
                                        <p>{{$detalle->codigo}}</p>
                                    </td>
                                    <td style="padding-bottom: 7px">
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

                                    <td style="padding-bottom: 7px" class="derecha">
                                        <p>{{$debe}}</p>
                                    </td>
                                    <td style="padding-bottom: 7px" class="derecha">
                                        <p>{{$haber}}</p>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                </tbody>

                <tfoot>
                    <tr class="blanco bg-azul" style="font-size: 12px;">
                        <th colspan="2" style="height: 20px">
                            <p>TOTALES</p>
                        </th>

                        @php
                            //por cada comprobante ya que seguimos en el foreach
                            $sumaDebe=0;
                            $sumaHaber=0;
                            foreach ($detalleComprobante as $detalle) {
                                if($detalle->comprobante_id == $comprobante->id)
                                {
                                    $sumaDebe = $sumaDebe + $detalle->debe;
                                    $sumaHaber = $sumaHaber + $detalle->haber;
                                }
                            }
                        @endphp
                        <th class="derecha">
                            <p>{{number_format($sumaDebe,2,'.',',')}}</p>
                        </th>
                        <th class="derecha">
                            <p>{{number_format($sumaHaber,2,'.',',')}}</p>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <br>
            <br>
            @endforeach
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



