@extends('plantilla.plantillaPDF')

@section('titulo')
    Plan de Cuentas
@endsection

@section('css')

<style>
    html {
        margin: 100pt 35pt 32pt 35pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
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
        color: rgb(28, 28, 145);
        margin: 0px;
    }
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

        /* saltos de pagina */
        .page_break {
            page-break-before: always;
        }
    /* tabla */
    .tabla-plan{
        width: 100%;
        border-spacing: 0;
        border: 0.1px solid #000000;
    }

    .tabla-plan tr,
    .tabla-plan td,
    .tabla-plan th{
        font-size: 9px;
        border-spacing: 0;
        border: 0.1px solid #000000;
    }

    p,
    {
        padding: 8px 2px 8px 2px;
    }

    tbody p,
    {
        padding: 5px 2px 5px 5px;
    }
</style>

<style>
    header {
            position: fixed;
            top: -90px;
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
        <table class="tabla-encabezado" style="width: 100%; font-weight: bold;">
            <tr>
                <td colspan="4" style="font-size: 12px">
                    <p>SISTEMA WEB DE CONTROL CONTABLE - CONTABILIDAD EN LA NUBE</p>
                </td>
                <td class="izquierda" style="text-align: right">
                    @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                        <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                        <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                    @endif
                </td>
            </tr>
            <tr style="height: 20px">
            </tr>
            <tr>
                <td colspan="5">
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center; font-size: 12px">
                    <p>PLAN DE CUENTAS</p>
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
            <table class="tabla-plan">
                <thead>
                    <tr class="texto-medio">
                        <th>
                            <p>CÓDIGO</p>
                        </th>
                        <th>
                            <p>CUENTA</p>
                        </th>
                        <th style="max-width: 25px;">
                            <p>CORREL</p>
                        </th>
                        <th style="max-width: 25px;">
                            <p>NIVEL</p>
                        </th>
                        <th>
                            <p>TIPO</p>
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($tipos as $tipo )
                        {{--! nivel 1 --}}
                        <tr style="color: black; font-weight: bold;"> {{-- #0000a0 --}}
                            <td class="izquierda">
                                <p>{{$tipo->id}} {{-- NIVEL 1 codigo--}}</p>
                            </td>
                            <td class="izquierda">
                                <p>{{$tipo->descripcion}} {{-- NIVEL 1 nombre--}}</p>
                            </td>
                            <td class="centro">
                                <p>{{$tipo->id}} {{-- NIVEL 1 correlativo--}}</p>
                            </td>
                            <td class="centro">
                                <p>1 {{-- NIVEL 1 nivel--}}</p>
                            </td>
                            <td class="centro">
                                <p>{{$tipo->descripcion}}</p>
                            </td>
                        </tr>

                        {{--! nivel 2 --}}
                        @foreach ( $grupos as $grupo )
                        @if ($grupo->tipo_id == $tipo->id)
                            <tr style="color: black ; font-weight: bold"> {{-- #0909ff --}}
                                <td>
                                    <p>{{$grupo->id}} {{-- NIVEL 2 codigo--}}</p>
                                </td>
                                <td>
                                    <p style="padding-left: 15px">{{$grupo->descripcion}} {{-- NIVEL 2 nombre--}}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$grupo->correlativo}} {{-- NIVEL 2 correlativo--}}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$grupo->nivel}} {{-- NIVEL 2 nivel--}}</p>
                                </td>
                                <td class="centro">
                                    <p>{{$tipo->descripcion}}</p>
                                </td>
                            </tr>

                                {{--! nivel 3 --}}
                                @foreach ( $sub_grupos as $sub_grupo )
                                @if ($sub_grupo->tipo_id == $tipo->id && $sub_grupo->grupo_id == $grupo->id)
                                    <tr style="color: #3b3bff; font-weight: bold">
                                        <td>
                                            <p>{{$sub_grupo->id}} {{-- NIVEL 3 codigo--}}</p>
                                        </td>
                                        <td>
                                            <p style="padding-left: 25px" >{{$sub_grupo->descripcion}} {{-- NIVEL 3 nombre--}}</p>
                                        </td>
                                        <td class="centro">
                                            <p>{{$sub_grupo->correlativo}} {{-- NIVEL 3 correlativo--}}</p>
                                        </td>
                                        <td class="centro">
                                            <p>{{$sub_grupo->nivel}} {{-- NIVEL 3 nivel--}}</p>
                                        </td>
                                        <td class="centro">
                                            <p>{{$tipo->descripcion}}</p>
                                        </td>
                                    </tr>

                                        {{--! nivel 4 --}}
                                        @foreach ( $cuentas as $cuenta )
                                        @if ($cuenta->tipo_id == $tipo->id && $cuenta->subGrupo_id == $sub_grupo->id)
                                            <tr style="color: #3b3bff;">
                                                <td>
                                                    <p>{{$cuenta->id}} {{-- NIVEL 4 codigo--}}</p>
                                                </td>
                                                <td>
                                                    <p style="padding-left: 35px">{{$cuenta->descripcion}} {{-- NIVEL 4 nombre--}}</p>
                                                </td>
                                                <td class="centro">
                                                    <p>{{$cuenta->correlativo}} {{-- NIVEL 4 correlativo--}}</p>
                                                </td>
                                                <td class="centro">
                                                    <p>{{$cuenta->nivel}} {{-- NIVEL 4 nivel--}}</p>
                                                </td>
                                                <td class="centro">
                                                    <p>{{$tipo->descripcion}}</p>
                                                </td>
                                            </tr>

                                                {{--! nivel 5 --}}
                                                @foreach ( $sub_cuentas as $sub_cuenta )
                                                @if ($sub_cuenta->tipo_id == $tipo->id && $sub_cuenta->cuenta_id == $cuenta->id)
                                                    <tr>
                                                        <td>
                                                            <p>{{$sub_cuenta->id}} {{-- NIVEL 5 codigo--}}</p>
                                                        </td>
                                                        <td>
                                                            <p style="padding-left: 45px">{{$sub_cuenta->descripcion}} {{-- NIVEL 5 nombre--}}</p>
                                                        </td>
                                                        <td class="centro">
                                                            <p>{{$sub_cuenta->correlativo}} {{-- NIVEL 5 correlativo--}}</p>
                                                        </td>
                                                        <td class="centro">
                                                            <p>{{$sub_cuenta->nivel}} {{-- NIVEL 5 nivel--}}</p>
                                                        </td>
                                                        <td class="centro">
                                                            <p>{{$tipo->descripcion}}</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @endforeach

                                        @endif
                                        @endforeach

                                @endif
                                @endforeach


                        @endif
                        @endforeach

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

            $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 8);

        ');
    }
</script>

@endsection



