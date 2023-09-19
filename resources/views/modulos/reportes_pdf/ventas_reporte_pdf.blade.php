@extends('plantilla.plantillaPDF')

@section('titulo')
    Reporte Mensual de Ventas
@endsection
@section('css')
    <style>
        html {
            margin: 32pt 20pt 28pt 20pt; //margenes de la hpja pdf a d ab iz
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }

        /* encabezados de tablas y hoja */
        .tabla-encabezado {
            text-align: left;
            font-size: 13px;
            color: rgb(28, 28, 145);
        }
        .encabezado-de-tabla,
        .footer-de-tabla{
            background-color: rgb(215, 215, 215);
            color:rgb(28, 28, 145);
        }

        /* sin movimientos */
        .sin-movimiento{
            text-align: center;
            font-size: 55px;
            margin-top: 30px;
            margin-bottom: 30px;

        }
        /* tablas ventas */
        .div-contenido-main table,
        .div-contenido-main tr,
        .div-contenido-main th,
        .div-contenido-main td {
            font-size: 8px;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .div-contenido-main p,
        .div-contenido-main th {
            padding: 8px 2px 8px 2px;
        }

        .div-contenido-main td p{
            /* min-height: 15px; */
            /*    line-height: 20px; */
        }

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
    </style>
@endsection

@section('contenido')
    @if ($idSucursalBuscada == "-1")
        {{-- varias sucursales ********************************************************** --}}
        @php
            $pagina=1;
        @endphp
        @foreach ($sucursalesDeLaEmpresa as $sucursal)
        <div @if ($pagina >1) class="page_break" @endif >
            @php
                $pagina=$pagina+1;
            @endphp
            <header>
                <div class="div-encabezados">
                    <table class="tabla-encabezado" style=" width: 100%;">
                        <tr>
                            <td colspan="4">
                                <b>{{ $empresa_encabezado->denominacionSocial }}</b>
                            </td>
                            <th rowspan="2" class="izquierda" style="text-align: right; font-size: 12px">
                                <p>REGISTRO DE VENTAS</p>
                                @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                                    <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                                    <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <td colspan="4"><b>NIT: {{ $empresa_encabezado->nit }}</b></td>
                        </tr>
        
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <b>REGISTRO DE FACTURAS DE VENTAS</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <b>(Expresado en Bolivianos)</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="" style="text-align: right">
                                <b>Año:</b> {{ $gestionBuscada }}
                            </td>
                            <td colspan="" style="text-align: center">
                                <b>Mes:</b>
                                @switch($mesBuscado)
                                    @case(1)
                                        Enero
                                    @break
        
                                    @case(2)
                                        Febrero
                                    @break
        
                                    @case(3)
                                        Marzo
                                    @break
        
                                    @case(4)
                                        Abril
                                    @break
        
                                    @case(5)
                                        Mayo
                                    @break
        
                                    @case(6)
                                        Junio
                                    @break
        
                                    @case(7)
                                        Julio
                                    @break
        
                                    @case(8)
                                        Agosto
                                    @break
        
                                    @case(9)
                                        Septiembre
                                    @break
        
                                    @case(10)
                                        Octubre
                                    @break
        
                                    @case(11)
                                        Noviembre
                                    @break
        
                                    @case(12)
                                        Diciembre
                                    @break
        
                                    @default
                                @endswitch
                            </td>
                            <td colspan="3" style="text-align: center">
                                <b>Sucursal: </b> {{ $sucursal->descripcion }}
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
            </header>
        
            <main>
                <div class="div-contenido-main">
                    <table class="tabla-ventas">
                        <thead>
                            <tr class="encabezado-de-tabla">
                                <th style="width: 20px">Nº</th>
                                <th>ESP</th>
                                <th>FECHA DE LA FACTURA</th>
                                <th>NUMERO FACTURA</th>
                                <th>CÓDIGO DE AUTORIZACIÓN</th>
                                <th>NIT/CI DEL CLIENTE</th>
                                <th>COMP</th>
                                <th style="width: 30%">CLIENTE</th>
                                <th>IMPORTE TOTAL VENTA</th>
                                <th>IMPORTE ICE</th>
                                <th>IMPORTE IEHD</th>
                                <th>IMPORTE IPJ</th>
                                <th>TASAS</th>
                                <th>NO SUJETO A IVA</th>
                                <th>EXPORT. Y EXENTOS</th>
                                <th>VENTAS GRAVADAS A TASA CERO</th>
                                <th>SUBTOTAL</th>
                                <th>DESC. /BONIF. /REBAJAS SUJETAS AL IVA</th>
                                <th>IMPORTE GIFT CARD</th>
                                <th>IMPORTE BASE DF</th>
                                <th>DEBITO FISCAL</th>
                                <th>ESTADO</th>
                                <th>CODIGO DE CONTROL</th>
                                <th>TIPO VENTA</th>
                            </tr>
                        </thead>
        
                        @if ($sucursal->cantidad_ventas > 0)
                            {{-- tienen registros --}}
                            <tbody>
                                @php
                                    $numero = 0;
                                @endphp
                                @foreach ($ventasEncontradas as $venta)
                                    @if ($venta->sucursal_id == $sucursal->id)
                                        <tr>
                                            <td class="centro">{{ $numero = $numero + 1 }}</td>
                                            <td class="centro">1</td>

                                            {{--! fecha --}}
                                            @php
                                                $f = explode('-',$venta->fecha);
                                                $f2 = $f[2]."/".$f[1]."/".$f[0];
                                            @endphp
                                            <td class="derecha">
                                                <p>{{$f2}}</p>
                                            </td>

                                            <td class="derecha">
                                                <p>{{ $venta->numeroFactura }}</p>
                                            </td>

                                            {{-- codigo de autorizacion --}}
                                            <td class="derecha">
                                                @php
                                                    if(Str::length($venta->codigoAutorizacion)>20)
                                                    {
                                                        $ca1 = Str::substr($venta->codigoAutorizacion, 0, Str::length($venta->codigoAutorizacion)/3);
                                                        $ca2 = Str::substr($venta->codigoAutorizacion, (Str::length($venta->codigoAutorizacion)/3)-1,Str::length($venta->codigoAutorizacion)/3 );
                                                        $ca3 = Str::substr($venta->codigoAutorizacion, ((Str::length($venta->codigoAutorizacion)/3)*2)-1,Str::length($venta->codigoAutorizacion)/3 );
                                                    }
                                                    else {
                                                        $ca1 = $venta->codigoAutorizacion;
                                                        $ca2="";
                                                        $ca3="";
                                                    }
                                                @endphp
                                                <p>{{  $ca1." ".$ca2." ".$ca3 }}</p>
                                            </td>


                                            <td class="derecha">
                                                <p>
                                                    {{ $venta->ciNitCliente }}
                                                </p>
                                            </td>
                                            <td>{{ $venta->complemento }}</td>
                                            <td>
                                                <p>{{ $venta->razonSocialCliente }}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->importeTotal,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->ice,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->iehd,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->ipj,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->tasas,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->otrosNoSujetosaIva ,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->exportacionesyExentos,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->tasaCero,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                                                $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $subtotal_mostrar }}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->descuentos,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $aux = number_format($venta->gifCard,2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $aux}}</p>
                                            </td>

                                            @php
                                                $baseParaCF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                                $baseParaCF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $baseParaCF_mostar }}</p>
                                            </td>

                                            @php
                                                $auxDf = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                            @endphp
                                            <td class="derecha">
                                                <p>{{ $auxDf }}</p>
                                            </td>

                                            <td class="centro">{{ $venta->estado }}</td>

                                            <td>
                                                <p>{{ $venta->codigoControl }}</p>
                                            </td>

                                            <td class="centro">{{ $venta->tipoVenta }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        @else
                            {{-- sin movimiento --}}
                            <tbody>
                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="27">
                                        <p class="sin-movimiento">
                                            SIN MOVIMIENTO
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                
                                <tr>
                                    <td style="height: 32px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        @endif

                        {{--! sumas footer --}}
                        @php
                            $suma_importeTotal = 0;
                            $suma_ice = 0;
                            $suma_iehd = 0;
                            $suma_ipj = 0;
                            $suma_tasas = 0;
                            $suma_otros = 0;
                            $suma_exportaciones = 0;
                            $suma_tasaCero = 0;
                            $suma_descuentos = 0;
                            $suma_gifCard = 0;
                            foreach ($ventasEncontradas as $venta) {

                                if($venta->sucursal_id == $sucursal->id){
                                    $suma_importeTotal = $suma_importeTotal + $venta->importeTotal;
                                    $suma_ice = $suma_ice + $venta->ice;
                                    $suma_iehd = $suma_iehd + $venta->iehd;
                                    $suma_ipj = $suma_ipj + $venta->iehd;
                                    $suma_tasas = $suma_tasas + $venta->tasas;
                                    $suma_otros = $suma_otros + $venta->otrosNoSujetosaIva;
                                    $suma_exportaciones = $suma_exportaciones + $venta->exportacionesyExentos;
                                    $suma_tasaCero = $suma_tasaCero + $venta->tasaCero;
                                    $suma_descuentos = $suma_descuentos + $venta->descuentos;
                                    $suma_gifCard = $suma_gifCard + $venta->gifCard;
                                }

                            }
                        @endphp

                        @php
                            $suma_subtotal = $suma_importeTotal -$suma_ice - $suma_iehd - $suma_ipj - $suma_tasas - $suma_otros - $suma_exportaciones - $suma_tasaCero;
        
                            $suma_baseDF = $suma_subtotal - $suma_descuentos - $suma_gifCard;
                            $suma_df = round($suma_baseDF * 0.13, 2);
                            
                        @endphp

                        <tfoot>
                            <tr class="footer-de-tabla">
                                <td colspan="8" class="centro"><b>TOTALES</b></td>
                                <th class="derecha"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_ice,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_iehd,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_ipj,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_tasas,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_otros,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_exportaciones,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_tasaCero,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_descuentos,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_gifCard,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_baseDF,2,'.',',') }}</p></th>
                                <th class="derecha"><p>{{ number_format($suma_df,2,'.',',') }}</p></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </main>
        </div>

        @endforeach
    @else
        {{-- una sucursal ***********************************************  --}}
        <header>
            <div class="div-encabezados">
                <table class="tabla-encabezado" style=" width: 100%;">
                    <tr>
                        <td colspan="4">
                            <b>{{ $empresa_encabezado->denominacionSocial }}</b>
                        </td>
                        <th rowspan="2" class="izquierda" style="text-align: right; font-size: 12px">
                            <p>REGISTRO DE VENTAS</p>
                            @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                                <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                                <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <td colspan="4"><b>NIT: {{ $empresa_encabezado->nit }}</b></td>
                    </tr>
    
                    <tr>
                        <td colspan="5" style="text-align: center">
                            <b>REGISTRO DE FACTURAS DE VENTAS</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: center">
                            <b>(Expresado en Bolivianos)</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="" style="text-align: right">
                            <b>Año:</b> {{ $gestionBuscada }}
                        </td>
                        <td colspan="" style="text-align: center">
                            <b>Mes:</b>
                            @switch($mesBuscado)
                                @case(1)
                                    Enero
                                @break
    
                                @case(2)
                                    Febrero
                                @break
    
                                @case(3)
                                    Marzo
                                @break
    
                                @case(4)
                                    Abril
                                @break
    
                                @case(5)
                                    Mayo
                                @break
    
                                @case(6)
                                    Junio
                                @break
    
                                @case(7)
                                    Julio
                                @break
    
                                @case(8)
                                    Agosto
                                @break
    
                                @case(9)
                                    Septiembre
                                @break
    
                                @case(10)
                                    Octubre
                                @break
    
                                @case(11)
                                    Noviembre
                                @break
    
                                @case(12)
                                    Diciembre
                                @break
    
                                @default
                            @endswitch
                        </td>
                        <td colspan="3" style="text-align: center">
                            <b>Sucursal: </b> {{ $sucursal_encabezado->descripcion }}
                        </td>
                    </tr>
                </table>
            </div>
            <br>
        </header>
    
        <main>
            <div class="div-contenido-main">
                <table class="tabla-ventas">
                    <thead>
                        <tr class="encabezado-de-tabla">
                            <th style="width: 20px">Nº</th>
                            <th>ESP</th>
                            <th>FECHA DE LA FACTURA</th>
                            <th>NUMERO FACTURA</th>
                            <th>CÓDIGO DE AUTORIZACIÓN</th>
                            <th>NIT/CI DEL CLIENTE</th>
                            <th>COMP</th>
                            <th style="width: 30%">CLIENTE</th>
                            <th>IMPORTE TOTAL VENTA</th>
                            <th>IMPORTE ICE</th>
                            <th>IMPORTE IEHD</th>
                            <th>IMPORTE IPJ</th>
                            <th>TASAS</th>
                            <th>NO SUJETO A IVA</th>
                            <th>EXPORT. Y EXENTOS</th>
                            <th>VENTAS GRAVADAS A TASA CERO</th>
                            <th>SUBTOTAL</th>
                            <th>DESC. /BONIF. /REBAJAS SUJETAS AL IVA</th>
                            <th>IMPORTE GIFT CARD</th>
                            <th>IMPORTE BASE DF</th>
                            <th>DEBITO FISCAL</th>
                            <th>ESTADO</th>
                            <th>CODIGO DE CONTROL</th>
                            <th>TIPO VENTA</th>
                        </tr>
                    </thead>
    
                    @if (count($ventasEncontradas) > 0)
                        {{-- tienen registros --}}
                        <tbody>
                            @php
                                $numero = 0;
                            @endphp
                            @foreach ($ventasEncontradas as $venta)
                                <tr>
                                    <td class="centro">{{ $numero = $numero + 1 }}</td>
                                    <td class="centro">1</td>

                                    {{--! fecha --}}
                                    @php
                                        $f = explode('-',$venta->fecha);
                                        $f2 = $f[2]."/".$f[1]."/".$f[0];
                                    @endphp
                                    <td class="derecha">
                                        <p>{{$f2}}</p>
                                    </td>

                                    <td class="derecha">
                                        <p>{{ $venta->numeroFactura }}</p>
                                    </td>

                                    {{-- codigo de autorizacion --}}
                                    <td class="derecha">
                                        @php
                                            if(Str::length($venta->codigoAutorizacion)>20)
                                            {
                                                $ca1 = Str::substr($venta->codigoAutorizacion, 0, Str::length($venta->codigoAutorizacion)/3);
                                                $ca2 = Str::substr($venta->codigoAutorizacion, (Str::length($venta->codigoAutorizacion)/3)-1,Str::length($venta->codigoAutorizacion)/3 );
                                                $ca3 = Str::substr($venta->codigoAutorizacion, ((Str::length($venta->codigoAutorizacion)/3)*2)-1,Str::length($venta->codigoAutorizacion)/3 );
                                            }
                                            else {
                                                $ca1 = $venta->codigoAutorizacion;
                                                $ca2="";
                                                $ca3="";
                                            }
                                        @endphp
                                        <p>{{  $ca1." ".$ca2." ".$ca3 }}</p>
                                    </td>


                                    <td class="derecha">
                                        <p>
                                            {{ $venta->ciNitCliente }}
                                        </p>
                                    </td>
                                    <td>{{ $venta->complemento }}</td>
                                    <td>
                                        <p>{{ $venta->razonSocialCliente }}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->importeTotal,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->ice,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->iehd,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->ipj,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->tasas,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->otrosNoSujetosaIva ,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->exportacionesyExentos,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->tasaCero,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                                        $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $subtotal_mostrar }}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->descuentos,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $aux = number_format($venta->gifCard,2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $aux}}</p>
                                    </td>

                                    @php
                                        $baseParaCF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                        $baseParaCF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $baseParaCF_mostar }}</p>
                                    </td>

                                    @php
                                        $auxDf = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                    @endphp
                                    <td class="derecha">
                                        <p>{{ $auxDf }}</p>
                                    </td>

                                    <td class="centro">{{ $venta->estado }}</td>

                                    <td>
                                        <p>{{ $venta->codigoControl }}</p>
                                    </td>

                                    <td class="centro">{{ $venta->tipoVenta }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @else
                        {{-- sin movimiento --}}
                        <tbody>
                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td colspan="27">
                                    <p class="sin-movimiento">
                                        SIN MOVIMIENTO
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td style="height: 32px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    @endif

                    {{--! sumas footer --}}
                    @php
                        $suma_importeTotal = 0;
                        $suma_ice = 0;
                        $suma_iehd = 0;
                        $suma_ipj = 0;
                        $suma_tasas = 0;
                        $suma_otros = 0;
                        $suma_exportaciones = 0;
                        $suma_tasaCero = 0;
                        $suma_descuentos = 0;
                        $suma_gifCard = 0;
                        foreach ($ventasEncontradas as $venta) {
                            $suma_importeTotal = $suma_importeTotal + $venta->importeTotal;
                            $suma_ice = $suma_ice + $venta->ice;
                            $suma_iehd = $suma_iehd + $venta->iehd;
                            $suma_ipj = $suma_ipj + $venta->iehd;
                            $suma_tasas = $suma_tasas + $venta->tasas;
                            $suma_otros = $suma_otros + $venta->otrosNoSujetosaIva;
                            $suma_exportaciones = $suma_exportaciones + $venta->exportacionesyExentos;
                            $suma_tasaCero = $suma_tasaCero + $venta->tasaCero;
                            $suma_descuentos = $suma_descuentos + $venta->descuentos;
                            $suma_gifCard = $suma_gifCard + $venta->gifCard;
                        }
                    @endphp

                    @php
                        $suma_subtotal = $suma_importeTotal -$suma_ice - $suma_iehd - $suma_ipj - $suma_tasas - $suma_otros - $suma_exportaciones - $suma_tasaCero;

                        $suma_baseDF = $suma_subtotal - $suma_descuentos - $suma_gifCard;
                        $suma_df = round($suma_baseDF * 0.13, 2);
                        
                    @endphp

                    <tfoot>
                        <tr class="footer-de-tabla">
                            <td colspan="8" class="centro"><b>TOTALES</b></td>
                            <th class="derecha"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_ice,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_iehd,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_ipj,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_tasas,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_otros,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_exportaciones,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_tasaCero,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_descuentos,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_gifCard,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_baseDF,2,'.',',') }}</p></th>
                            <th class="derecha"><p>{{ number_format($suma_df,2,'.',',') }}</p></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </main>
    @endif


    {{-- $pdf->text(370, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10); --}}
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");

                $pdf->text(750, 568, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);

            ');
        }
    </script>

    @endsection
