
{{-- ! Encabezado --}}
<table>
    <tr>
        <td style="color: blue; font-weight: bold">
            {{$datosEmpresa->denominacionSocial}}
        </td>
    </tr>
    <tr>
        <td style="color: blue; font-weight: bold">
            NIT: {{$datosEmpresa->nit}}
        </td>
    </tr>

    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; color: blue; font-weight: bold">
            ESTADO DE RESULTADOS
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; color: blue; font-weight: bold">
            Por el periodo comprendido entre el: {{date('d/m/Y', strtotime($fechaInicio_buscado_er))}} y el: {{date('d/m/Y', strtotime($fechaFin_buscado_er))}}
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; color: blue; font-weight: bold">
            (Expresado en Bolivianos)
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<br>
{{-- ! Fin Encabezado --}}

{{-- ! Contenido --}}

@isset($acumulado_subcuentas_er)
{{-- estado de resultados --}}

    <table id="tablaER">

        <tbody>
            @foreach ($tipos_todos as $tipo )
                @if ($tipo->id == 4 || $tipo->id == 5)
                    <tr>
                        <td style="text-decoration: underline; color: blue; font-weight: bold; text-align: left; width: 150px;">{{$tipo->id}}</td>
                        <td style="text-decoration: underline; color: blue; font-weight: bold; text-align: left; width: 300px;">{{$tipo->descripcion}}</td>

                        <td></td>
                        <td></td>
                        <td></td>

                        <td style="text-decoration: underline; color: blue; font-weight: bold; text-align: right">
                            @php
                                $saldo=0;
                            @endphp
                            @foreach ($acumulado_tipos_er as $tipo_er)
                                @if ($tipo_er->tipo_codigo == $tipo->id)
                                    @php
                                        if($tipo_er->tipo_codigo == 4){
                                            $saldo = $tipo_er->suma_haber - $tipo_er->suma_debe;
                                        }
                                        if($tipo_er->tipo_codigo == 5){
                                            $saldo = $tipo_er->suma_debe - $tipo_er->suma_haber;
                                        }
                                    @endphp
                                @endif
                            @endforeach
                            {{number_format($saldo,2,'.','')}}
                        </td>
                    </tr>
                    {{--! grupo --}}
                    @foreach ( $grupos_todos as $grupo)
                        @if ($grupo->tipo_codigo == $tipo->id)
                            <tr>
                                <td style="color: blue; font-weight: bold; text-align: left">{{$grupo->grupo_codigo}}</td>
                                <td style="color: blue; font-weight: bold; text-align: left">{{$grupo->grupo_descripcion}}</td>

                                <td></td>
                                <td></td>
                                <td></td>

                                <td style="color: blue; font-weight: bold; text-align: right">
                                    @php
                                        $saldo=0;
                                    @endphp
                                    @foreach ($acumulado_grupos_er as $grupo_er)
                                        @if ($grupo_er->tipo_codigo == $tipo->id)
                                            @if ($grupo_er->grupo_codigo == $grupo->grupo_codigo)
                                                @php
                                                    if($grupo_er->tipo_codigo == 4){
                                                        $saldo = $grupo_er->suma_haber - $grupo_er->suma_debe;
                                                    }
                                                    if($grupo_er->tipo_codigo == 5){
                                                        $saldo = $grupo_er->suma_debe - $grupo_er->suma_haber;
                                                    }
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    {{number_format($saldo,2,'.','')}}
                                </td>
                            </tr>

                            {{--! sub grupo --}}
                            @foreach ( $acumulado_subgrupos_er as $subgrupo_er)
                                @if ($subgrupo_er->tipo_codigo == $tipo->id)
                                    @if ($subgrupo_er->grupo_id == $grupo->grupo_codigo)
                                        <tr>
                                            <td style="color: blue; text-align: left">{{$subgrupo_er->subGrupo_codigo}}</td>
                                            <td style="color: blue; text-align: left">{{$subgrupo_er->subGrupo_descripcion}}</td>

                                            <td></td>
                                            <td></td>

                                            <td style="color: blue; text-align: right">
                                                @php
                                                    $saldo=0;
                                                @endphp
                                                    @php
                                                        if($subgrupo_er->tipo_codigo == 4){
                                                            $saldo = $subgrupo_er->suma_haber - $subgrupo_er->suma_debe;
                                                        }
                                                        if($subgrupo_er->tipo_codigo == 5){
                                                            $saldo = $subgrupo_er->suma_debe - $subgrupo_er->suma_haber;
                                                        }
                                                    @endphp
                                                {{number_format($saldo,2,'.','')}}
                                            </td>

                                            <td></td>
                                        </tr>

                                        {{--! cuenta --}}
                                        @foreach ( $acumulado_cuentas_er as $cuenta_er)
                                            @if ($cuenta_er->tipo_codigo == $tipo->id)
                                                @if ($cuenta_er->subGrupo_id ==  $subgrupo_er->subGrupo_codigo)
                                                    <tr>
                                                        <td style="font-weight: bold; text-align: left">{{$cuenta_er->cuenta_codigo}}</td>
                                                        <td style="font-weight: bold; text-align: left">{{$cuenta_er->cuenta_descripcion}}</td>

                                                        <td></td>

                                                        <td style="font-weight: bold; text-align: right">
                                                            @php
                                                                $saldo=0;
                                                            @endphp
                                                                @php
                                                                    if($cuenta_er->tipo_codigo == 4){
                                                                        $saldo = $cuenta_er->suma_haber - $cuenta_er->suma_debe;
                                                                    }
                                                                    if($cuenta_er->tipo_codigo == 5){
                                                                        $saldo = $cuenta_er->suma_debe - $cuenta_er->suma_haber;
                                                                    }
                                                                @endphp
                                                            {{number_format($saldo,2,'.','')}}
                                                        </td>

                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    {{--! sub cuenta --}}
                                                    @foreach ( $acumulado_subcuentas_er as $subCuenta_er)
                                                        @if ($subCuenta_er->tipo_codigo == $tipo->id)
                                                            @if ($subCuenta_er->cuenta_id == $cuenta_er->cuenta_codigo )
                                                                <tr>
                                                                    <td style="text-align: left">{{$subCuenta_er->subCuenta_codigo}}</td>
                                                                    <td style="text-align: left">{{$subCuenta_er->subCuenta_descripcion}}</td>

                                                                    <td style="text-align: right">
                                                                        @php
                                                                            $saldo=0;
                                                                        @endphp
                                                                            @php
                                                                                if($subCuenta_er->tipo_codigo == 4){
                                                                                    $saldo = $subCuenta_er->suma_haber - $subCuenta_er->suma_debe;
                                                                                }
                                                                                if($subCuenta_er->tipo_codigo == 5){
                                                                                    $saldo = $subCuenta_er->suma_debe - $subCuenta_er->suma_haber;
                                                                                }
                                                                            @endphp
                                                                        {{number_format($saldo,2,'.','')}}
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
        </tbody>

    </table>

{{-- pie estado de resultados --}}

    <table>
        <tbody>
            @php
                $total_Ingresos=0;
                $total_Gastos=0;

                foreach ($acumulado_tipos_er as $tipo_er) {
                    if($tipo_er->tipo_codigo == 4){
                        $total_Ingresos = $tipo_er->suma_haber - $tipo_er->suma_debe;
                    }
                    if($tipo_er->tipo_codigo == 5){
                        $total_Gastos = $tipo_er->suma_debe - $tipo_er->suma_haber;
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

            <tr>
                <td>Ingresos:</td>
                <td>{{number_format($total_Ingresos,2,'.','')}}</td>
            </tr>
            <tr>
                <td>(Menos) Gastos:</td>
                <td>{{number_format($total_Gastos,2,'.','')}}</td>
            </tr>
            <tr>
                <td style="color: blue">{{$texto_resultado}}:</td>
                <td style="color: blue">{{number_format($numero_resultado,2,'.','')}}</td>
            </tr>
        </tbody>
    </table>
@endisset

{{-- ! Fin Contenido --}}



