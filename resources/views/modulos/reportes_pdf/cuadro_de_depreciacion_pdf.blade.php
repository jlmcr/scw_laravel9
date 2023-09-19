@extends('plantilla.plantillaPDF')

@section('titulo')
    Cuadro de depreciacion de activo fijo
@endsection

@section('css')
    <style>
        html {
            margin: 200pt 25pt 32pt 25pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
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
                <td colspan="4" style="font-size: 16px">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 13px; width: 300px">
                    REEXPRESIÓN Y DEPRECIACIÓN
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
                    CUADRO DE REEXPRESIÓN Y DEPRECIACIÓN DE ACTIVO FIJO
                </td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center; font-size: 15px;">
                    (Expresado en Bolivianos)
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 10px"></td>
            </tr>
        </table>
    </div>
        {{-- datos generales de la cuenta --}}
    <div>
        <table class="datosEncabezado_2" style="font-size: 14px; width: 100%;">
            <tr>
                <th class="izquierda" style="width: 220px;">
                    <p>Método de Depreciación:</p>
                </th>
                <td>
                    <p>Linea Recta - D.S. Nro. 24051</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Ejercicio Fiscal:</p>
                </th>
                <td>
                    <p>{{ $ejercicio_buscado_datos->ejercicioFiscal }}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Categoría o Rubro:</p>
                </th>
                <td class="azul">
                    <p>{{$rubro_buscado_datos->rubro}}</p>
                </td>
            </tr>
            <tr>
                <th class="izquierda">
                    <p>Años de Vida Útil:</p>
                </th>
                <td>
                    <p>{{$rubro_buscado_datos->aniosVidaUtil}}</p>
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
                            <p>Item/Código</p>
                        </th>
                        <th rowspan="2">
                            <p>Descripción</p>
                        </th>
                        <th colspan="3" style="width: 100px">
                            <p>Reexpresión</p>
                        </th>
                        <th rowspan="2">
                            <p>Valor en Libros</p>
                        </th>
                        <th rowspan="2">
                            <p>Incremento por Actualización</p>
                        </th>
                        <th rowspan="2">
                            <p>Valor Actualizado Final</p>
                        </th>

                        <th rowspan="2">
                            <p>Tiempo (Meses)</p>
                        </th>
                        <th rowspan="2">
                            <p>Depreciación del Periodo</p>
                        </th>

                        <th rowspan="2">
                            <p>Dep. Acumulada Inicial</p>
                        </th>
                        <th rowspan="2">
                            <p>Increm. Actualiz. Deprec. Acumulada</p>
                        </th>
                        <th rowspan="2">
                            <p>Dep. Acumulada Final</p>
                        </th>

                        <th rowspan="2">
                            <p>Valor Neto del Activo</p>
                        </th>
                    </tr>

                    <tr class="blanco bg-azul" style="font-size: 10px;">
                        <th>
                            <p>Aplica</p>
                        </th>
                        <th>
                            <p style="width: 70px">Fecha inicio</p>
                        </th>
                        <th>
                            <p style="width: 70px">Fecha final</p>
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 10px">
                    {{--! inicio lista de activos encontrados --}}
                    @foreach ($activosFijos_encontrados as $activo )
                        <tr id="fila{{$activo->id}}">
                            <td style="height: 30px">
                                <p>{{$activo->id}}</p>
                            </td>
                            <td>
                                <p>{{$activo->activoFijo}}</p>
                            </td>

                            @php
                                $tieneDatosGuardados = "";
                            @endphp

                            {{--! CASO 1: EL ACTIVO TIENE DEPRECIACIONES GUARDADAS --}}
                            @foreach ($depreciaciones_existentes_en_el_ejercicio as $depreciacion)

                                @if ($depreciacion->ejercicio_id == $ejercicio_buscado_datos->id && $depreciacion->activoFijo_id == $activo->id)

                                    @php
                                        $tieneDatosGuardados = "si";
                                    @endphp

                                    {{--? checkbox aplicacion de reexpresion --}}
                                    <td class="centro">
                                        @if ($depreciacion->reexpresar == 1)
                                            <p>Si</p>
                                        @else
                                            <p>No</p>
                                        @endif
                                    </td>

                                    {{-- ? fechas y ufvs --}}
                                    {{--! ufvs en inputs DEPRECIACIONES GUARDADAS--}}
                                    @php
                                        $u1=1;
                                        $u2=1;
                                        foreach ($ufvs as $ufv) {
                                            if ($ufv->fecha == $depreciacion->fechaInicial) {
                                                $u1 = $ufv->ufv;
                                            }
                                            if ($ufv->fecha == $depreciacion->fechaFinal) {
                                                $u2 = $ufv->ufv;
                                            }
                                        }
                                    @endphp

                                    <td class="centro">

                                        <p>{{ date('d/m/Y',strtotime($depreciacion->fechaInicial)) }}</p>
                                    </td>
                                    <td class="centro">
                                        <p>{{ date('d/m/Y',strtotime($depreciacion->fechaFinal)) }}</p>
                                    </td>
                                    {{-- ? fin fechas y ufvs --}}

                                    <td class="derecha">
                                        <p>{{ number_format($depreciacion->valorInicial_depr, 2 , '.' , ',') }}</p>
                                    </td>

                                    {{--! calculo de operaciones caso 1 --}}
                                    @php
                                        $valorInicialBien = $depreciacion->valorInicial_depr;
                                        /* ? Incremento Actualización */
                                        if($depreciacion->reexpresar == 1 ){
                                            $incremPorActualizBien = (($u2/$u1)-1) * $valorInicialBien;
                                        }
                                        else{
                                            $incremPorActualizBien = 0;
                                        }

                                        /* Valor Actualizado Final */
                                        $valorFinalBien = $incremPorActualizBien + $valorInicialBien;
                                        /* Depreciación del Periodo */
                                        $meses = $depreciacion->meses;
                                        $porcentaje = 1 / $rubro_buscado_datos->aniosVidaUtil; //1 = 100%
                                        $deprecBien = (($valorFinalBien * $porcentaje)/12) * $meses;

                                        /* ***************** */
                                        /* Dep. Acumulada Inicial */
                                        $depAcumInicial = $depreciacion->depAcumInicial_depr;
                                        /* Increm. Actualiz. Deprec. Acumulada */
                                        if($depreciacion->reexpresar == 1 ){
                                            $incremPorActualizDepAcum = (($u2/$u1)-1) * $depAcumInicial;
                                        }
                                        else{
                                            $incremPorActualizDepAcum = 0;
                                        }

                                        /* Dep. Acumulada Final */
                                        $depAcFinal = $incremPorActualizDepAcum + $depAcumInicial + $deprecBien;

                                        /* ***************** */
                                        /* Valor Neto del Activo */
                                        $valorNeto = $valorFinalBien - $depAcFinal;
                                    @endphp

                                    <td class="derecha">
                                        <p>{{ number_format($incremPorActualizBien, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($valorFinalBien, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="centro">
                                        <p>{{$depreciacion->meses}}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($deprecBien, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($depreciacion->depAcumInicial_depr, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($incremPorActualizDepAcum, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($depAcFinal, 2 , '.' , ',') }}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ number_format($valorNeto, 2 , '.' , ',' ) }}</p>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    {{-- fin lista de activos encontrados --}}
                </tbody>
                <tfoot>
                    @php
                        $suma_valorLibros = 0;
                        $suma_incrementoActBien = 0;
                        $suma_valorFinalBien = 0;
                        //$suma_tiempo = 0;
                        $suma_depreciacion = 0;
                        $suma_depAcumInicial = 0;
                        $suma_incrementoDepAcum = 0;
                        $suma_depAcumFinal = 0;
                        $suma_valorNeto = 0;
                    @endphp

                    @php
                        foreach ($activosFijos_encontrados as $activo) {
                            $tieneDatosGuardados = "";
                            foreach ($depreciaciones_existentes_en_el_ejercicio as $depreciacion) {
                                if ($depreciacion->ejercicio_id == $ejercicio_buscado_datos->id && $depreciacion->activoFijo_id == $activo->id) {
                                    $tieneDatosGuardados = "si";

                                    // fechas y ufvs
                                        $u1=1;
                                        $u2=1;
                                        foreach ($ufvs as $ufv) {
                                            if ($ufv->fecha == $depreciacion->fechaInicial) {
                                                $u1 = $ufv->ufv;
                                            }
                                            if ($ufv->fecha == $depreciacion->fechaFinal) {
                                                $u2 = $ufv->ufv;
                                            }
                                        }
                                    // fechas y ufvs

                                    // calculo de operaciones caso 1
                                        $valorInicialBien = $depreciacion->valorInicial_depr;
                                        /* ? Incremento Actualización */
                                        if($depreciacion->reexpresar == 1 ){
                                            $incremPorActualizBien = (($u2/$u1)-1) * $valorInicialBien;
                                        }
                                        else{
                                            $incremPorActualizBien = 0;
                                        }

                                        /* Valor Actualizado Final */
                                        $valorFinalBien = $incremPorActualizBien + $valorInicialBien;
                                        /* Depreciación del Periodo */
                                        $meses = $depreciacion->meses;
                                        $porcentaje = 1 / $rubro_buscado_datos->aniosVidaUtil; //1 = 100%
                                        $deprecBien = (($valorFinalBien * $porcentaje)/12) * $meses;

                                        /* ***************** */
                                        /* Dep. Acumulada Inicial */
                                        $depAcumInicial = $depreciacion->depAcumInicial_depr;
                                        /* Increm. Actualiz. Deprec. Acumulada */
                                        if($depreciacion->reexpresar == 1 ){
                                            $incremPorActualizDepAcum = (($u2/$u1)-1) * $depAcumInicial;
                                        }
                                        else{
                                            $incremPorActualizDepAcum = 0;
                                        }

                                        /* Dep. Acumulada Final */
                                        $depAcFinal = $incremPorActualizDepAcum + $depAcumInicial + $deprecBien;

                                        /* ***************** */
                                        /* Valor Neto del Activo */
                                        $valorNeto = $valorFinalBien - $depAcFinal;
                                    // calculo de operaciones caso 1

                                    // calculo de sumas
                                        $suma_valorLibros = $suma_valorLibros + $valorInicialBien;
                                        $suma_incrementoActBien = $suma_incrementoActBien + $incremPorActualizBien;
                                        $suma_valorFinalBien = $suma_valorFinalBien + $valorFinalBien;
                                        //$suma_tiempo = $suma_tiempo + ;
                                        $suma_depreciacion = $suma_depreciacion + $deprecBien;
                                        $suma_depAcumInicial = $suma_depAcumInicial + $depAcumInicial;
                                        $suma_incrementoDepAcum = $suma_incrementoDepAcum + $incremPorActualizDepAcum;
                                        $suma_depAcumFinal = $suma_depAcumFinal + $depAcFinal;
                                        $suma_valorNeto = $suma_valorNeto + $valorNeto;
                                    // calculo de sumas
                                }
                            }
                        }
                    @endphp
                    <tr class="blanco bg-azul" style="font-size: 10px;">
                        <th colspan="5" style="height: 30px"><p>TOTALES</p></th>
                        <th class="derecha">
                            <p>{{ number_format($suma_valorLibros, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_incrementoActBien, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_valorFinalBien, 2 , '.' , ',') }}</p>
                        </th>
                        <th></th>
                        <th class="derecha">
                            <p>{{ number_format($suma_depreciacion, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_depAcumInicial, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_incrementoDepAcum, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_depAcumFinal, 2 , '.' , ',') }}</p>
                        </th>
                        <th class="derecha">
                            <p>{{ number_format($suma_valorNeto, 2 , '.' , ',') }}</p>
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



