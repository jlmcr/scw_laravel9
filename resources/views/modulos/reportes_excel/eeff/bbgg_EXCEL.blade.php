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
                BALANCE GENERAL
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; color: blue; font-weight: bold">
                Al: {{date('d/m/Y', strtotime($fechaFin_buscado_bg))}}
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
                        <tr>
                            <td style="text-align: left; text-decoration: underline; font-weight:bold; color: blue; width: 150px;">{{$tipo->id}}</td>
                            <td style="text-decoration: underline; font-weight:bold; color: blue; width: 300px;">{{$tipo->descripcion}}</td>

                            <td></td>
                            <td></td>
                            <td></td>

                            <td style="text-align: right; text-decoration: underline; font-weight:bold; color: blue;">
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
                                {{number_format($saldo,2,'.','')}}
                            </td>
                        </tr>
                        {{--! grupo --}}
                        @foreach ( $grupos_todos as $grupo)
                            @if ($grupo->tipo_codigo == $tipo->id)
                                <tr>
                                    <td style="text-align: left; font-weight:bold; color:blue;">{{$grupo->grupo_codigo}}</td>
                                    <td style="font-weight:bold; color:blue;">{{$grupo->grupo_descripcion}}</td>

                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td style="text-align: right; font-weight:bold; color:blue;">
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
                                        {{number_format($saldo,2,'.','')}}
                                    </td>
                                </tr>

                                {{--! sub grupo --}}
                                @foreach ( $acumulado_subgrupos_bg as $subgrupo_bg)
                                    @if ($subgrupo_bg->tipo_codigo == $tipo->id)
                                        @if ($subgrupo_bg->grupo_id == $grupo->grupo_codigo)
                                            <tr>
                                                <td style="text-align: left; color:blue;">{{$subgrupo_bg->subGrupo_codigo}}</td>
                                                <td style="color:blue">{{$subgrupo_bg->subGrupo_descripcion}}</td>

                                                <td></td>
                                                <td></td>

                                                <td style="text-align: right; color:blue">
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
                                                    {{number_format($saldo,2,'.','')}}
                                                </td>

                                                <td></td>

                                            </tr>

                                            {{--! cuenta --}}
                                            @foreach ( $acumulado_cuentas_bg as $cuenta_bg)
                                                @if ($cuenta_bg->tipo_codigo == $tipo->id)
                                                    @if ($cuenta_bg->subGrupo_id ==  $subgrupo_bg->subGrupo_codigo)
                                                        <tr>
                                                            <td style="text-align: left; font-weight: bold">{{$cuenta_bg->cuenta_codigo}}</td>
                                                            <td style="font-weight: bold">{{$cuenta_bg->cuenta_descripcion}}</td>

                                                            <td></td>

                                                            <td style="text-align: right; font-weight: bold">
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
                                                                {{number_format($saldo,2,'.','')}}
                                                            </td>

                                                            <td></td>
                                                            <td></td>
                                                        </tr>

                                                        {{--! sub cuenta --}}
                                                        @foreach ( $acumulado_subcuentas_bg as $subCuenta_bg)
                                                            @if ($subCuenta_bg->tipo_codigo == $tipo->id)
                                                                @if ($subCuenta_bg->cuenta_id == $cuenta_bg->cuenta_codigo )
                                                                    <tr>
                                                                        <td style="text-align: left">{{$subCuenta_bg->subCuenta_codigo}}</td>
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
                <tr>
                    <td style="text-align: left">3010101006</td>
                    <td>Resultado del Ejercicio</td>
                    <td style="text-align: right">{{number_format($numero_resultado,2,'.','')}}</td>

                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td>Activo:</td>
                    <td>{{number_format($total_activo,2,'.','')}}</td>
                </tr>
                <tr>
                    <td style="color: blue">Total Activo:</td>
                    <td style="color: blue">{{number_format($total_activo,2,'.','')}}</td>
                </tr>

                <tr>
                    <td>Pasivo:</td>
                    <td>{{number_format($total_pasivo,2,'.','')}}</td>
                </tr>
                <tr>
                    <td>(Más) Patrimonio:</td>
                    <td>{{number_format($total_patrimonio_2,2,'.','')}}</td>
                </tr>
                <tr>
                    <td style="color: blue">Total Pasivo y Patrimonio:</td>
                    <td style="color: blue">{{number_format($total_pasivo_y_patrimonio,2,'.','')}}</td>
                </tr>
            </tbody>
        </table>
    @endisset

{{-- ! Fin Contenido --}}



