{{-- ! compras --}}

@isset($comprasEncontradas)
    @if ($comprasEncontradas != "")
            
        <table id="tablaCompras">
            <thead>
                <tr style="font-size:10px">
                    <th>Nº</th>
                    <th>ESPECIFICACION</th>
                    <th>NIT PROVEEDOR</th>
                    <th>RAZON SOCIAL PROVEEDOR</th>
                    <th>CODIGO DE AUTORIZACION</th>
                    <th>NUMERO FACTURA</th>
                    <th>NUMERO DUI/DIM</th>
                    <th>FECHA DE FACTURA/DUI/DIM</th>
                    <th>IMPORTE TOTAL COMPRA</th>
                    <th>IMPORTE ICE</th>
                    <th>IMPORTE IEHD</th>
                    <th>IMPORTE IPJ</th>
                    <th>TASAS</th>
                    <th>OTRO NO SUJETO A CREDITO FISCAL</th>
                    <th>IMPORTES EXENTOS</th>
                    <th>IMPORTE COMPRAS GRAVADAS A TASA CERO</th>
                    <th>SUBTOTAL</th>
                    <th>DESCUENTOS/BONIFICACIONES /REBAJAS SUJETAS AL IVA</th>
                    <th>IMPORTE GIFT CARD</th>
                    <th>IMPORTE BASE CF</th>
                    <th>CREDITO FISCAL</th>
                    <th>TIPO COMPRA</th>
                    <th>CODIGO DE CONTROL</th>
                </tr>
            </thead>

            <tbody>
                @php // declaramos la variable, no la imprimimos aun
                    $numero = 0;
                @endphp
                @foreach ($comprasEncontradas as $compra)
                    <tr>
                        <td>{{ $numero = $numero + 1 }}</td>
                        <td>1</td>
                        <td>{{ $compra->nitProveedor }}</td>
                        <td>{{ $compra->razonSocialProveedor }}</td>
                        <td>{{ $compra->codigoAutorizacion }}</td>
                        <td>{{ $compra->numeroFactura }}</td>
                        <td>{{ $compra->dim }}</td>

                        @php
                            $f = explode('-',$compra->fecha);
                            $f2 = $f[2]."/".$f[1]."/".$f[0];
                        @endphp
                        <td>{{$f2}}</td>

                        @php
                            $aux = number_format($compra->importeTotal,2,'.','');
                        @endphp
                        <td>{{ $aux}}</td>

                        @php
                            $aux = number_format($compra->ice,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->iehd,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->ipj,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->tasas,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->otrosNoSujetosaCF,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->exentos,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->tasaCero,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        {{-- subtotal --}}
                        @php
                            $subtotal = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.','');

                            $subtotal_mostrar = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.','');
                        @endphp
                        <td>{{ $subtotal_mostrar }}</td>

                        @php
                            $aux = number_format($compra->descuentos,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $aux = number_format($compra->gifCard,2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        @php
                            $baseParaCF = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.','');

                            $baseParaCF_mostar = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.','');
                        @endphp
                        <td>{{ $baseParaCF_mostar }}</td>

                        @php
                            $aux = number_format(round($baseParaCF * 0.13, 2),2,'.','');
                        @endphp
                        <td>{{ $aux }}</td>

                        <td>{{ $compra->tipoCompra }}</td>

                        @if ($compra->codigoControl != 0 && $compra->codigoControl != "")
                            <td>{{ $compra->codigoControl }}</td>
                            @else
                            <td>0</td>
                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>
        
    @endif
@endisset

{{-- ! fin compras --}}