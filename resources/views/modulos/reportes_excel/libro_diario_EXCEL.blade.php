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
            <td colspan="4" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                LIBRO DIARIO
            </td>
        </tr>
        <tr>
            <td colspan="4" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                <p>Del: {{date('d/m/Y', strtotime($fechaInicio_buscado))}}  al: {{date('d/m/Y', strtotime($fechaFin_buscado))}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="color: blue; font-weight: bold; text-align: center; vertical-align: center">
                (Expresado en Bolivianos)
            </td>
        </tr>
    </table>

{{-- ! Fin Encabezado --}}

{{-- ! Contenido --}}

    @foreach ( $comprobantesEncontrados as $comprobante)

    {{--! datos generales --}}
    <table>
        <tr>
            <th></th>
            <th></th>
            
            <th>
                <p>Número Documento:</p>
            </th>
            <td>
                <p>{{$comprobante->nroComprobante}}</p>
            </td>
        </tr>

        <tr>
            <th>
                <p>Fecha:</p>
            </th>
            <td>
                <p>{{date('d/m/Y', strtotime($comprobante->fecha))}}</p>
            </td>
            <th>
                <p>Tipo Documento:</p>
            </th>
            <td>
                <p>{{$comprobante->tipo->nombre}}</p>
            </td>
        </tr>

        <tr>
            <th>
                <p>Concepto:</p>
            </th>
            <td colspan="3">
                <p>{{$comprobante->concepto}}</p>
            </td>
        </tr>
    </table>

    {{--! cuentas y subcuentas --}}
    <table>
        <thead>
            <tr>
                <th style="width: 100px; color: blue; font-weight: bold; text-align: center; border: 0.1px solid black">
                    <p>CÓDIGO</p>
                </th>
                <th style="width: 300px; color: blue; font-weight: bold; text-align: center; border: 0.1px solid black">
                    <p>CUENTA</p>
                </th>
                <th style="width: 160px; color: blue; font-weight: bold; text-align: center; border: 0.1px solid black">
                    <p >DEBE</p>
                </th>
                <th style="width: 160px; color: blue; font-weight: bold; text-align: center; border: 0.1px solid black">
                    <p >HABER</p>
                </th>
            </tr>
        </thead>

        {{--! cuerpo del asiento --}}
        <tbody>
                @foreach ($detalleComprobante as $detalle )
                    @if ($detalle->comprobante_id == $comprobante->id)
                        @foreach ($cuentasDetalle as $cuenta)
                            @if ($cuenta->id == $detalle->cuenta_id)
                                <tr>
                                    <td style="text-align: left; font-weight: bold; border: 0.1px solid black; text-decoration: underline">
                                        <p>{{$cuenta->codigo}}</p>
                                    </td>
                                    <td style="font-weight: bold; border: 0.1px solid black; text-decoration: underline">
                                        <p>{{$cuenta->descripcion}}</p>
                                    </td>
                                    <td style="border: 0.1px solid black"></td>
                                    <td style="border: 0.1px solid black"></td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td style="text-align: left; border: 0.1px solid black">
                                <p>{{$detalle->codigo}}</p>
                            </td>
                            <td style="border: 0.1px solid black">
                                <p>{{$detalle->descripcion}}</p>
                            </td>

                            {{-- ! debe y haber --}}
                            @php
                                //debe
                                if($detalle->debe == 0 || $detalle->debe == ""){
                                    $debe = "";
                                }
                                else {
                                    $debe = number_format($detalle->debe,2,".","");
                                }
                                //haber
                                if($detalle->haber == 0 || $detalle->haber == ""){
                                    $haber = "";
                                }
                                else {
                                    $haber = number_format($detalle->haber,2,".","");
                                }
                            @endphp

                            <td style="border: 0.1px solid black">
                                <p>{{$debe}}</p>
                            </td>
                            <td style="border: 0.1px solid black">
                                <p>{{$haber}}</p>
                            </td>
                        </tr>
                    @endif
                @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="2" style="color: blue; font-weight: bold; border: 0.1px solid black">
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
                <th style="color: blue; font-weight: bold; border: 0.1px solid black">
                    <p>{{number_format($sumaDebe,2,'.','')}}</p>
                </th>
                <th style="color: blue; font-weight: bold; border: 0.1px solid black">
                    <p>{{number_format($sumaHaber,2,'.','')}}</p>
                </th>
            </tr>
            <tr></tr>
        </tfoot>
    </table>

    @endforeach

{{-- ! Fin Contenido --}}


