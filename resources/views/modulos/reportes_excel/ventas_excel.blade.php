{{-- ! ventas --}}

@isset($ventasEncontradas)
    @if ($ventasEncontradas != "")
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>ESPECIFICACION</th>
                    <th>FECHA DE LA FACTURA</th>
                    <th>N° DE LA FACTURA</th>
                    <th>CODIGO DE AUTORIZACION</th>
                    <th>NIT / CI CLIENTE</th>
                    <th>COMPLEMENTO</th>
                    <th>NOMBRE O RAZON SOCIAL</th>
                    <th>IMPORTE TOTAL DE LA VENTA</th>
                    <th>IMPORTE ICE</th>
                    <th>IMPORTE IEHD</th>
                    <th>IMPORTE IPJ</th>
                    <th>TASAS</th>
                    <th>OTROS NO SUJETOS AL IVA</th>
                    <th>EXPORTACIONES Y OPERACIONES EXENTAS</th>
                    <th>VENTAS GRAVADAS A TASA CERO</th>
                    <th>SUBTOTAL</th>
                    <th>DESCUENTOS, BONIFICACIONES Y REBAJAS SUJETAS AL IVA</th>
                    <th>IMPORTE GIFT CARD</th>
                    <th>IMPORTE BASE PARA DEBITO FISCAL</th>
                    <th>DEBITO FISCAL</th>
                    <th>ESTADO</th>
                    <th>CODIGO DE CONTROL</th>
                    <th>TIPO DE VENTA</th>
                </tr>
            </thead>

            <tbody>
                @php // declaramos la variable, no la imprimimos aun
                    $numero = 0;
                @endphp
                @foreach ($ventasEncontradas as $venta)
                    <tr>

                        <td>{{ $numero = $numero + 1 }}</td>
                        <td>1</td>

                        {{--! fecha --}}
                        @php
                            $f = explode('-',$venta->fecha);
                            $f2 = $f[2]."/".$f[1]."/".$f[0];
                        @endphp
                        <td>{{$f2}}</td>

                        <td>{{ $venta->numeroFactura }}</td>
                        <td>{{ $venta->codigoAutorizacion }}</td>
                        <td>{{ $venta->ciNitCliente }}</td>
                        <td>{{ $venta->complemento }}</td>
                        <td>{{ $venta->razonSocialCliente }}</td>

                        @php
                            $aux = number_format($venta->importeTotal,2,'.','');
                        @endphp
                        <td>{{ $aux}}</td>

                        @php
                            $aux = number_format($venta->ice,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->iehd,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->ipj,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->tasas,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->otrosNoSujetosaIva ,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->exportacionesyExentos,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->tasaCero,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                            $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');
                        @endphp
                        <td>{{ $subtotal_mostrar }}</td>

                        @php
                            $aux = number_format($venta->descuentos,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($venta->gifCard,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $baseParaDF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                            $baseParaDF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');
                        @endphp
                        <td>{{ $baseParaDF_mostar }}</td>

                        @php
                            $auxDf = number_format(round($baseParaDF * 0.13, 2),2,'.','');
                        @endphp
                        <td>{{ $auxDf }}</td>

                        <td>{{ $venta->estado }}</td>

                        @if ($venta->codigoControl != 0 && $venta->codigoControl != "")
                            <td>{{ $venta->codigoControl }}</td>
                            @else
                            <td>0</td>
                        @endif

                        <td>{{ $venta->tipoVenta }}</td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    @endif
@endisset

{{-- ! fin ventas --}}