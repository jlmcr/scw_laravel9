@extends('plantilla.plantillaPDF')

@section('titulo')
    Balance General
@endsection

@section('css')
    <style>
        html {
            margin: 145pt 38pt 32pt 40pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
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

        .'tabla-de-desarrollo{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .'tabla-de-desarrollo td,
        .'tabla-de-desarrollo th{
            border-spacing: 0;
            border-collapse: collapse; //una linea en bordes
        }

        .'tabla-de-desarrollo th{
            border: 0.1px solid #000000;
        }

        .'tabla-de-desarrollo td{
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
                top: -150px;
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
                        BALANCE GENERAL
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
        {{-- ! Fin Encabezado --}}
    </header>

    <main>

        {{-- ! Contenido --}}
        <section class="content">
            <div class="contenido">
                @isset($acumulado_subcuentas_bg)
                {{-- balance general --}}
                {{--! calculo del resultado en el ejercicio buscado --}}
                    @php
                        $total_Ingresos=0;
                        $total_Gastos=0;

                        foreach ($acumulado_tipos_bg as $tipo_bg) {
                            if($tipo_bg->tipo_codigo == 4){
                                $total_Ingresos = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                            }
                            if($tipo_bg->tipo_codigo == 5){
                                $total_Gastos = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                            }
                        }

                        $texto_resultado ="";
                        if($total_Ingresos > $total_Gastos){
                            $texto_resultado ="Utilidad del Ejercicio";
                        }
                        else {
                            $texto_resultado ="Pérdida del Ejercicio";
                        }
                        
                        $numero_resultado = $total_Ingresos - $total_Gastos;
                    @endphp

                    @php
                        $total_activo=0;
                        $total_pasivo=0;
                        $total_patrimonio=0;

                        foreach ($acumulado_tipos_bg as $tipo_bg) {
                            if($tipo_bg->tipo_codigo == 1){
                                $total_activo = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                            }
                            if($tipo_bg->tipo_codigo == 2){
                                $total_pasivo = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                            }
                            if($tipo_bg->tipo_codigo == 3){
                                $total_patrimonio = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                            }
                        }

                        $total_patrimonio_2= $total_patrimonio + $numero_resultado;
                        $total_pasivo_y_patrimonio= $total_patrimonio_2 + $total_pasivo;

                    @endphp
                {{--! fin calculo del resultado en el ejercicio buscado --}}

                {{-- balance general contenido--}}

                <table id="tablaBG" style="width:100%">
                    <tbody style="font-size: 13px">
                        @foreach ($tipos_todos as $tipo )
                            @if ($tipo->id == 1 || $tipo->id == 2 || $tipo->id == 3)
                                <tr style="text-decoration: underline; font-weight:bold; color: blue;">
                                    <td>{{$tipo->id}}</td>
                                    <td>{{$tipo->descripcion}}</td>
                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                    <td style="text-align: right">
                                        @php
                                            $saldo=0;                                                            
                                        @endphp
                                        @foreach ($acumulado_tipos_bg as $tipo_bg)
                                            @if ($tipo_bg->tipo_codigo == $tipo->id)
                                                @php
                                                    if($tipo_bg->tipo_codigo == 1){
                                                        $saldo = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                                                    }

                                                    if($tipo_bg->tipo_codigo == 2){
                                                        $saldo = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                                                    }
                                                    if($tipo_bg->tipo_codigo == 3){
                                                        $saldo = $tipo_bg->suma_haber - $tipo_bg->suma_debe + $numero_resultado;
                                                    }
                                                @endphp
                                            @endif
                                        @endforeach
                                        {{number_format($saldo,2,'.',',')}}
                                    </td>
                                </tr>
                                {{--! grupo --}}
                                @foreach ( $grupos_todos as $grupo)
                                    @if ($grupo->tipo_codigo == $tipo->id)
                                        <tr style="font-weight:bold; color: blue;">
                                            <td>{{$grupo->grupo_codigo}}</td>
                                            <td>{{$grupo->grupo_descripcion}}</td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td style="text-align: right">
                                                @php
                                                    $saldo=0;                                                            
                                                @endphp
                                                @foreach ($acumulado_grupos_bg as $grupo_bg)
                                                    @if ($grupo_bg->tipo_codigo == $tipo->id)
                                                        @if ($grupo_bg->grupo_codigo == $grupo->grupo_codigo)
                                                            @php
                                                                if($grupo_bg->tipo_codigo == 1){
                                                                    $saldo = $grupo_bg->suma_debe - $grupo_bg->suma_haber;
                                                                }

                                                                if($grupo_bg->tipo_codigo == 2){
                                                                    $saldo = $grupo_bg->suma_haber - $grupo_bg->suma_debe;
                                                                }
                                                                if($grupo_bg->tipo_codigo == 3){
                                                                    $saldo = $grupo_bg->suma_haber - $grupo_bg->suma_debe + $numero_resultado;
                                                                }
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endforeach
                                                {{number_format($saldo,2,'.',',')}}
                                            </td>
                                        </tr>

                                        {{--! sub grupo --}}
                                        @foreach ( $acumulado_subgrupos_bg as $subgrupo_bg)
                                            @if ($subgrupo_bg->tipo_codigo == $tipo->id)
                                                @if ($subgrupo_bg->grupo_id == $grupo->grupo_codigo)
                                                    <tr style="color:blue">
                                                        <td>{{$subgrupo_bg->subGrupo_codigo}}</td>
                                                        <td>{{$subgrupo_bg->subGrupo_descripcion}}</td>

                                                        <td></td>
                                                        <td></td>

                                                        <td style="text-align: right">
                                                            @php
                                                                $saldo=0;                                                            
                                                            @endphp
                                                                @php
                                                                    if($subgrupo_bg->tipo_codigo == 1){
                                                                        $saldo = $subgrupo_bg->suma_debe - $subgrupo_bg->suma_haber;
                                                                    }

                                                                    if($subgrupo_bg->tipo_codigo == 2){
                                                                        $saldo = $subgrupo_bg->suma_haber - $subgrupo_bg->suma_debe;
                                                                    }
                                                                    if($subgrupo_bg->tipo_codigo == 3){
                                                                        $saldo = $subgrupo_bg->suma_haber - $subgrupo_bg->suma_debe + $numero_resultado;
                                                                    }
                                                                @endphp
                                                            {{number_format($saldo,2,'.',',')}}
                                                        </td>

                                                        <td></td>

                                                    </tr>
                                                    
                                                    {{--! cuenta --}}
                                                    @foreach ( $acumulado_cuentas_bg as $cuenta_bg)
                                                        @if ($cuenta_bg->tipo_codigo == $tipo->id)
                                                            @if ($cuenta_bg->subGrupo_id ==  $subgrupo_bg->subGrupo_codigo)
                                                                <tr>
                                                                    <td>{{$cuenta_bg->cuenta_codigo}}</td>
                                                                    <td>{{$cuenta_bg->cuenta_descripcion}}</td>

                                                                    <td></td>

                                                                    <td style="text-align: right">
                                                                        @php
                                                                            $saldo=0;                                                            
                                                                        @endphp
                                                                            @php
                                                                                if($cuenta_bg->tipo_codigo == 1){
                                                                                    $saldo = $cuenta_bg->suma_debe - $cuenta_bg->suma_haber;
                                                                                }

                                                                                if($cuenta_bg->tipo_codigo == 2){
                                                                                    $saldo = $cuenta_bg->suma_haber - $cuenta_bg->suma_debe;
                                                                                }
                                                                                if($cuenta_bg->tipo_codigo == 3){
                                                                                    $saldo = $cuenta_bg->suma_haber - $cuenta_bg->suma_debe + $numero_resultado;
                                                                                }
                                                                            @endphp
                                                                        {{number_format($saldo,2,'.',',')}}
                                                                    </td>

                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>

                                                                {{--! sub cuenta --}}
                                                                @foreach ( $acumulado_subcuentas_bg as $subCuenta_bg)
                                                                    @if ($subCuenta_bg->tipo_codigo == $tipo->id)
                                                                        @if ($subCuenta_bg->cuenta_id == $cuenta_bg->cuenta_codigo )
                                                                            <tr>
                                                                                <td>{{$subCuenta_bg->subCuenta_codigo}}</td>
                                                                                <td>{{$subCuenta_bg->subCuenta_descripcion}}</td>

                                                                                <td style="text-align: right">
                                                                                    @php
                                                                                        $saldo=0;                                                            
                                                                                    @endphp
                                                                                        @php
                                                                                            if($subCuenta_bg->tipo_codigo == 1){
                                                                                                $saldo = $subCuenta_bg->suma_debe - $subCuenta_bg->suma_haber;
                                                                                            }

                                                                                            if($subCuenta_bg->tipo_codigo == 2){
                                                                                                $saldo = $subCuenta_bg->suma_haber - $subCuenta_bg->suma_debe;
                                                                                            }
                                                                                            if($subCuenta_bg->tipo_codigo == 3){
                                                                                                $saldo = $subCuenta_bg->suma_haber - $subCuenta_bg->suma_debe;
                                                                                            }
                                                                                        @endphp
                                                                                    {{number_format($saldo,2,'.',',')}}
                                                                                </td>

                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        @endif
                                                                    @endif
                                                                @endforeach

                                                            @endif
                                                        @endif
                                                    @endforeach

                                                    
                                                @endif
                                            @endif
                                        @endforeach

                                    @endif
                                @endforeach

                            @endif
                        @endforeach
                        <tr>
                            <td>3010101006</td>
                            <td>Resultado del Ejercicio</td>
                            <td style="text-align: right">{{number_format($numero_resultado,2,'.',',')}}</td>

                        </tr>
                    </tbody>

                </table>

                <br>
                {{-- pie balance general --}}
                <div class="row">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="height: 100px"></td>
                                <td style="height: 100px"></td>
                            </tr>

                            <tr>
                                <td style="width:50%; text-align: center; font-weight: bold">.............................</td>
                                <td style="width:50%; text-align: center; font-weight: bold">.............................</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            @endisset
            
            </div>
        </section>
        {{-- ! Fin Contenido --}}

    </main>


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");

                $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>

@endsection



