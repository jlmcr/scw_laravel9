
<style>
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

    {{-- ! Encabezado --}}
        <table>
            <tr>
                <td style="color: blue; font-weight: bold;">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
            </tr>
            <tr>
                <td style="color: blue; font-weight: bold;">
                    NIT: {{$datosEmpresaActiva->nit}}
                </td>
            </tr>

            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="7" style="color: blue; font-weight: bold; text-align: center;">
                    BALANCE DE COMPROBACIÓN DE SUMAS Y SALDOS
                </td>
            </tr>
            <tr>
                <th colspan="7" style="color: blue; font-weight: bold; text-align: center;">
                    <p>Por el periodo comprendido entre el: {{date('d/m/Y', strtotime($fechaInicio_buscado))}}  y el: {{date('d/m/Y', strtotime($fechaFin_buscado))}}</p>
                </th>
            </tr>
            <tr>
                <td colspan="7" style="color: blue; font-weight: bold; text-align: center;">
                    (Expresado en Bolivianos)
                </td>
            </tr>
        </table>
    {{-- ! Fin Encabezado --}}

    {{-- ! Contenido --}}

            {{--! tabla --}}
            @isset($registrosBCSS_entontrados)
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="color: blue; font-weight: bold; text-align: center; height: 30px; vertical-align: center">NRO.</th>
                        <th rowspan="2" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">CÓDIGO</th>
                        <th rowspan="2" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">SUB-CUENTA</th>
                        {{-- <th rowspan="2" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">TIPO</th> --}}
                        <th colspan="2" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">MOVIMIENTOS/SUMAS</th>
                        <th colspan="2" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">SALDOS</th>
                    </tr>

                    <tr>
                        <th style="color: blue; font-weight: bold; text-align: center; height: 30px; vertical-align: center">
                            DEBE
                        </th>
                        <th style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                            HABER
                        </th>
                        <th style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                            DEUDOR
                        </th>
                        <th style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                            ACREEDOR
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @php // declaramos la variable, no la imprimimos aun
                        $numero = 0;
                    @endphp

                    @foreach ($registrosBCSS_entontrados as $registro )
                        <tr>
                            <td>
                                {{ $numero = $numero + 1 }}
                            </td>

                            <td style="width: 150px">
                                {{$registro->subcuenta_id}}
                            </td>

                            <td style="width: 300px">
                                {{$registro->descripcion}}
                            </td>

                            {{-- <td class="text-center">{{$registro->descripcion_tipo}}</td> --}}

                            <td>
                                {{number_format($registro->suma_debe,2,'.','')}}
                            </td>

                            <td>
                                {{number_format($registro->suma_haber,2,'.','')}}
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
                            <td>
                                {{number_format($deudor,2,'.','')}}
                            </td>

                            <td>
                                {{number_format($acreedor,2,'.','')}}
                            </td>
                        </tr>
                    @endforeach
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

                    <tr>
                        <th colspan="3" style="color: blue; font-weight: bold; text-align: center; height: 30px">
                            SUMAS IGUALES
                        </th>

                        <th style="color: blue; font-weight: bold; text-align: center;">
                            {{number_format($total_debe,2,'.','')}}
                        </th>

                        <th style="color: blue; font-weight: bold; text-align: center;">
                            {{number_format($total_haber,2,'.','')}}
                        </th>

                        <th style="color: blue; font-weight: bold; text-align: center;">
                            {{number_format($total_deudor,2,'.','')}}
                        </th>

                        <th style="color: blue; font-weight: bold; text-align: center;">
                            {{number_format($total_acreedor,2,'.','')}}
                        </th>

                    </tr>
                </tfoot>
            </table>
            @endisset

    {{-- ! Fin Contenido --}}

