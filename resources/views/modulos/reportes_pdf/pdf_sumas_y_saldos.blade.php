@extends('plantilla.plantillaPDF')

@section('titulo')
    Sumas y Saldos
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

    .tabla-bcss{
        width: 100%;
        border-spacing: 0;
        border: 0.1px solid #000000;
    }

    .tabla-bcss td,
    .tabla-bcss th{
        border-spacing: 0;
        border-collapse: collapse; //una linea en bordes
    }

    .tabla-bcss th{
        border: 0.1px solid #000000;
    }

    .tabla-bcss td{
        border-left: 0.1px solid #000000;
        border-right: 0.1px solid #000000;
    }


    /* parrrafo */
    tbody p{
        padding: 2px 5px 5px 3px;
    }

    tfoot p{
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
        <table class="tabla-encabezado azul" style="font-size: 16px">
            <tr>
                <td colspan="4">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 13px">
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
                    BALANCE DE COMPROBACIÓN DE SUMAS Y SALDOS
                </td>
            </tr>
            <tr>
                <th colspan="5">
                    <p>Por el periodo comprendido entre el: {{date('d/m/Y', strtotime($fechaInicio_buscado))}}  y el: {{date('d/m/Y', strtotime($fechaFin_buscado))}}</p>
                </th>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center; font-size: 15px;">
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
            <br>

            {{--! tabla --}}
            @isset($registrosBCSS_entontrados)
            <table class="tabla-bcss" style="width:100%">
                <thead>
                    <tr  class="blanco bg-azul" style="font-size: 13px;">
                        <th rowspan="2" style="width: 40px; height: 45px">NRO.</th>
                        <th rowspan="2">CÓDIGO</th>
                        <th rowspan="2">SUB-CUENTA</th>
                        {{-- <th rowspan="2">TIPO</th> --}}
                        <th colspan="2">MOVIMIENTOS/SUMAS</th>
                        <th colspan="2">SALDOS</th>
                    </tr>
                    <tr class="blanco bg-azul" style="font-size: 13px;">
                        {{-- <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th> --}}
                        <th>
                            <p>DEBE</p>
                        </th>
                        <th>
                            <p>HABER</p>
                        </th>
                        <th>
                            <p>DEUDOR</p>
                        </th>
                        <th>
                            <p>ACREEDOR</p>
                        </th>
                    </tr>
                </thead>

                <tbody style="font-size: 12px">
                    <tr>
                        <td style="height: 7pt"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    @php // declaramos la variable, no la imprimimos aun
                        $numero = 0;
                    @endphp

                    @foreach ($registrosBCSS_entontrados as $registro )
                        <tr>
                            <td class="centro">
                                <p>{{ $numero = $numero + 1 }}</p>
                            </td>

                            <td class="centro">
                                <p>{{$registro->subcuenta_id}}</p>
                            </td>

                            <td>
                                <p>{{$registro->descripcion}}</p>
                            </td>

                            {{-- <td class="text-center">{{$registro->descripcion_tipo}}</td> --}}

                            <td importe="{{$registro->suma_debe}}" class="col_debe derecha">
                                <p>{{number_format($registro->suma_debe,2,'.',',')}}</p>
                            </td>

                            <td importe="{{$registro->suma_haber}}" class="col_haber derecha">
                                <p>{{number_format($registro->suma_haber,2,'.',',')}}</p>
                            </td>

                            @php
                                $deudor=0;
                                $acreedor=0;
                                $debe = $registro->suma_debe;
                                $haber = $registro->suma_haber;

                                if($debe > $haber){
                                    $deudor = $debe - $haber;
                                }
                                else {
                                    $acreedor = $haber - $debe;
                                }
                            @endphp
                            <td importe="{{$deudor}}" class="col_deudor derecha">
                                <p>{{number_format($deudor,2,'.',',')}}</p>
                            </td>

                            <td importe="{{$acreedor}}" class="col_acreedor derecha">
                                <p>{{number_format($acreedor,2,'.',',')}}</p>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="height:7px"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>

                <tfoot>
                    @php
                        $total_debe=0;
                        $total_haber=0;
                        $total_deudor=0;
                        $total_acreedor=0;
                        foreach ($registrosBCSS_entontrados as $registro ) {
                            /* columnas debe haber deudor acreedor */
                            $deudor=0;
                            $acreedor=0;
                            $debe = $registro->suma_debe;
                            $haber = $registro->suma_haber;

                            if($debe > $haber){
                                $deudor = $debe - $haber;
                            }
                            else {
                                $acreedor = $haber - $debe;
                            }

                            /* sumas */
                            $total_debe = $total_debe + $debe;
                            $total_haber = $total_haber + $haber;
                            $total_deudor = $total_deudor + $deudor;
                            $total_acreedor = $total_acreedor + $acreedor;
                        }
                    @endphp

                    <tr class="blanco bg-azul" style="font-size:13px">
                        <th colspan="3" style="height: 30px">SUMAS IGUALES</th>
                        <th id="footer_debe" class="derecha">
                            <p>{{number_format($total_debe,2,'.',',')}}</p>
                        </th>

                        <th id="footer_haber" class="derecha">
                            <p>{{number_format($total_haber,2,'.',',')}}</p>
                        </th>

                        <th id="footer_deudor" class="derecha">
                            <p>{{number_format($total_deudor,2,'.',',')}}</p>
                        </th>

                        <th id="footer_acreedor" class="derecha">
                            <p>{{number_format($total_acreedor,2,'.',',')}}</p>
                        </th>

                    </tr>
                </tfoot>
            </table>
            @endisset

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



