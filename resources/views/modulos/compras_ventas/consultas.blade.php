@extends('plantilla.adminlte')

@section('titulo')
RCV
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{--! Jquery UI --}}
    <link rel="stylesheet" href="{{ asset('custom-code/jquery-ui-1.13.2/jquery-ui.min.css') }}">

    {{--! /* Quitamos flechas del imput number */ --}}
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
        input[type=number] { -moz-appearance:textfield; }
    </style>

    {{-- ! personalizacion de datatable --}}
    <style>
        table.dataTable thead {
            /*background: linear-gradient(to left, #43cea2, #185a9d);
            color:white; */
            font-size: 12px;
            text-align: center;
        }
        /* Para usar solo con scrollX */
        div.dataTables_wrapper {
    /*            width: 90%;
            margin: 0 auto;*/
            font-size: 14px;
        }

        table thead{
            vertical-align: middle !important;
        }
    </style>

    {{-- tootltip - mensajes emergentes --}}
    <style>
        .tooltip{

        }
    </style>
@endsection

@section('contenido')
        <div class="content-wrapper">
            {{-- ! Encabezado --}}
            <div class="content-header ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <a href="/registro-compras-ventas/consultas?process=menu&idEmpresaActiva={{Auth::user()->idEmpresaActiva}}">
                                Consultas de Compras y Ventas
                                </a>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                                <li class="breadcrumb-item active">Compras</li>
                                <li class="breadcrumb-item active">Registro de Compras</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
            {{-- ! Fin Encabezado --}}

            {{-- {{$usuario_->tema->nombre}}
            igual funciona pero por cada vista se debe enviar datos del usuarios actual--}}



            {{-- ! Contenido --}}
            <section class="content">
                <div class="container-fluid">
                    {{--* Buscador --}}
                    <form method="GET" action="{{ route('rcv-consultas') }}">
                        <div class="row">
                            {{--! Criterios de busqueda --}}

                            <input type="hidden" name="process" value="search">
                            {{--* empresa activa --}}
                            <input type="hidden" name="idEmpresaActiva" value="{{Auth::user()->idEmpresaActiva}}">

                            {{--* mostrar --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Mostrar:</label>

                                    <select name="mostrar_vista" id="mostrar_vista" class="form-control" style="width: 100%;" required>
                                        @if (isset($mostrar_vista))
                                            @if ($mostrar_vista == "a_detalle")
                                                <option value="a_detalle" selected>A detalle</option>
                                                <option value="resumen">resumen</option>
                                                @else
                                                <option value="a_detalle">A detalle</option>
                                                <option value="resumen" selected>resumen</option>
                                            @endif
                                        @else
                                            <option value="a_detalle">A detalle</option>
                                            <option value="resumen">resumen</option>
                                        @endif

                                    </select>
                                </div>
                            </div>

                            {{--* conepto compra/venta --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Concepto:</label>

                                    <select name="concepto" id="concepto" class="form-control" style="width: 100%;" required>

                                        <option value=""></option>
                                        @if (isset($concepto))
                                            @if ($concepto == "compras")
                                                <option value="compras" selected>Compras</option>
                                                <option value="ventas">Ventas</option>
                                                @else
                                                <option value="compras">Compras</option>
                                                <option value="ventas" selected>Ventas</option>
                                            @endif
                                        @else
                                            <option value="compras">Compras</option>
                                            <option value="ventas">Ventas</option>
                                        @endif

                                    </select>
                                </div>
                            </div>

                            {{--* gestion --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Gestión:</label>

                                    <select name="gestion" id="gestion" class="form-control" style="width: 100%;" required>
                                        @php
                                            $anio =  date("Y");
                                            $anioReferencia = $anioMinimo;//añp minimo
                                        @endphp
                                        @while ($anio >= $anioReferencia)
                                            @if (isset($gestionBuscada))
                                                @if ($gestionBuscada == $anio)
                                                    <option value="{{$anio}}" selected>{{$anio}}</option>
                                                @else
                                                    <option value="{{$anio}}">{{$anio}}</option>
                                                @endif
                                            @else
                                                <option value="{{$anio}}">{{$anio}}</option>
                                            @endif

                                            @php
                                                $anio = $anio-1;
                                            @endphp
                                        @endwhile
                                    </select>
                                </div>
                            </div>

                            {{--* mes --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Mes:</label>
                                    <select name="mes" id="mes" class="form-control" style="width: 100%;" required>
                                        @if (isset($mesBuscado))
                                            @if ($mesBuscado == 1)
                                                <option value="1" selected>Enero</option>
                                            @else
                                                <option value="1">Enero</option>
                                            @endif

                                            @if ($mesBuscado == 2)
                                                <option value="2" selected>Febrero</option>
                                            @else
                                                <option value="2">Febrero</option>
                                            @endif

                                            @if ($mesBuscado == 3)
                                                <option value="3" selected>Marzo</option>
                                            @else
                                                <option value="3">Marzo</option>
                                            @endif

                                            @if ($mesBuscado == 4)
                                                <option value="4" selected>Abril</option>
                                            @else
                                                <option value="4">Abril</option>
                                            @endif

                                            @if ($mesBuscado == 5)
                                                <option value="5" selected>Mayo</option>
                                            @else
                                                <option value="5">Mayo</option>
                                            @endif

                                            @if ($mesBuscado == 6)
                                                <option value="6" selected>Junio</option>
                                            @else
                                                <option value="6">Junio</option>
                                            @endif

                                            @if ($mesBuscado == 7)
                                                <option value="7" selected>Julio</option>
                                            @else
                                                <option value="7">Julio</option>
                                            @endif

                                            @if ($mesBuscado == 8)
                                                <option value="8" selected>Agosto</option>
                                            @else
                                                <option value="8">Agosto</option>
                                            @endif

                                            @if ($mesBuscado == 9)
                                                <option value="9" selected>Septiembre</option>
                                            @else
                                                <option value="9">Septiembre</option>
                                            @endif

                                            @if ($mesBuscado == 10)
                                                <option value="10" selected>Octubre</option>
                                            @else
                                                <option value="10">Octubre</option>
                                            @endif

                                            @if ($mesBuscado == 11)
                                                <option value="11" selected>Noviembre</option>
                                            @else
                                                <option value="11">Noviembre</option>
                                            @endif

                                            @if ($mesBuscado == 12)
                                                <option value="12" selected>Diciembre</option>
                                            @else
                                                <option value="12">Diciembre</option>
                                            @endif
                                        @else
                                            <option value=""></option>
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{--* Sucursal --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Sucursal:</label>
                                    <select name="sucursal" id="sucursal" class="form-control" style="width: 100%;" required>

                                        @if(isset($idSucursalBuscada))
                                            @foreach ($sucursalesDeLaEmpresa as $sucursal )
                                                @if ($sucursal->id == $idSucursalBuscada)
                                                    <option value="{{$sucursal->id}}" selected >{{$sucursal->descripcion}}</option>
                                                @else
                                                    <option value="{{$sucursal->id}}">{{$sucursal->descripcion}}</option>
                                                @endif
                                            @endforeach

                                            @if ($idSucursalBuscada == '-1')
                                                <option value="-1" selected >Todos</option>
                                            @else
                                                <option value="-1">Todos</option>
                                            @endif
                                        @else
                                            <option value=""></option>
                                            {{-- mostramos sucursales - opciones predeterminadas --}}
                                            @foreach ($sucursalesDeLaEmpresa as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->descripcion }}</option>
                                            @endforeach
                                            <option value="-1">Todos</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- * Botones busqueda --}}
                            <div class="col-md-2">
                                <label for=""></label>
                                <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                    {{--* Fin Buscador --}}

                    <br>

                    @isset($concepto)
                        {{-- ! DataTable --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-success card-outline">

                                    <!-- /.card-header -->
                                    <div class="card-header">
                                        <div class="row justify-content-end">
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-7">
                                                @if ($idSucursalBuscada == '-1')
                                                    <h3 class="card-title">Sucursal: Todos</h3>
                                                @else
                                                    @foreach ($sucursalesDeLaEmpresa as $sucursal )
                                                        @if ($sucursal->id == $idSucursalBuscada)
                                                            <h3 class="card-title">Sucursal: {{$sucursal->descripcion}}</h3>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                    <br>
                                                    <p class="p-0 m-0">Periodo: {{$mesBuscado}} - {{$gestionBuscada}}  | {{$concepto}}</p>
                                            </div>

                                            <div class="col-md-2">
                                            <a href="{{route('rcv-PDF',['gestionBuscada'=>$gestionBuscada,'mesBuscado'=>$mesBuscado,'idSucursalBuscada'=>$idSucursalBuscada,'concepto'=>$concepto])}}" target="_blank" role="button" class="btn btn-outline-danger mt-2 w-100" name="btnImprimir" id="btnImprimir" >
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            </div>


                                            @if (isset($concepto))
                                                @if ($concepto == "compras")
                                                <div class="col-md-3">
                                                    <a href="{{route('exportar-compras-excel',['gestionBuscada'=>$gestionBuscada, 'mesBuscado'=>$mesBuscado, 'idSucursalBuscada'=>$idSucursalBuscada])}}" role="button" class="btn btn-outline-success mt-2 w-100" name="btnExportarExcel" id="btnExportarExcel">
                                                        <i class="fas fa-file-excel"></i> Excel: RND 10-21030
                                                    </a>
                                                </div>
                                                @endif
                                                @if ($concepto == "ventas")
                                                <div class="col-md-3">
                                                    <a href="{{route('exportar-ventas-excel',['gestionBuscada'=>$gestionBuscada, 'mesBuscado'=>$mesBuscado, 'idSucursalBuscada'=>$idSucursalBuscada])}}" role="button" class="btn btn-outline-success mt-2 w-100" name="btnExportarExcel" id="btnExportarExcel">
                                                        <i class="fas fa-file-excel"></i> Excel: RND 10-21030
                                                    </a>
                                                </div>
                                                @endif
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <!-- /. fin card-header -->

                                    {{--? doby --}}
                                    {{-- ! compras --}}
                                    @if ($concepto == "compras")
                                        <div class="card-body">
                                            <table id="tablaResultados" data-page-length='10' class="table table-bordered table-striped display" style="width:100%">
                                                @if ($mostrar_vista=="resumen")
                                                    <thead>
                                                        <tr style="font-size:10px">
                                                            <th class="align-middle">Nº</th>

                                                            @if ($idSucursalBuscada == '-1')
                                                                <th class="align-middle">SUCURSAL</th>
                                                            @endif

                                                            <th class="align-middle">NIT PROVEEDOR</th>
                                                            <th class="align-middle"><p style="padding: 0 100px 0 100px">PROVEEDOR</p></th>
                                                            <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                            <th class="align-middle">NUMERO FACTURA</th>
                                                            <th class="align-middle">NUMERO DUI/DIM</th>
                                                            <th class="align-middle">FECHA DE FACTURA/DUI/DIM</th>
                                                            <th class="align-middle">IMPORTE TOTAL COMPRA</th>
                                                            <th class="align-middle">SUBTOTAL</th>
                                                            <th class="align-middle">IMPORTE BASE CF</th>
                                                            <th class="align-middle">CREDITO FISCAL</th>
                                                            {{-- <th class="align-middle">TIPO COMPRA</th> --}}
                                                            <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody style="font-size: 12px">
                                                        @php // declaramos la variable, no la imprimimos aun
                                                            $numero = 0;
                                                        @endphp
                                                        @foreach ($comprasEncontradas as $compra)
                                                            <tr id="fila{{$compra->id}}" id_validador="{{$compra->nitProveedor.$compra->codigoAutorizacion.$compra->numeroFactura.$compra->fecha}}"
                                                                class="{{$compra->nitProveedor.$compra->codigoAutorizacion.$compra->numeroFactura.$compra->fecha}}">

                                                                <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                                {{--! sucursal --}}
                                                                @if ($idSucursalBuscada == '-1')
                                                                    <td>{{ $compra->descripcion }}</td>                                                                    
                                                                @endif
                                                                
                                                                <td class="nit">{{ $compra->nitProveedor }}</td>
                                                                <td>{{ $compra->razonSocialProveedor }}</td>
                                                                <td class="autorizacion">{{ $compra->codigoAutorizacion }}</td>
                                                                <td class="numerofac text-right">{{ $compra->numeroFactura }}</td>
                                                                <td>{{ $compra->dim }}</td>

                                                                @php
                                                                    $f = explode('-',$compra->fecha);
                                                                    $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                                @endphp
                                                                <td class="text-right fecha">{{$f2}}</td>

                                                                @php
                                                                    $aux = number_format($compra->importeTotal,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux}}</td>

                                                                @php
                                                                    $subtotal = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.','');

                                                                    $subtotal_mostrar = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $subtotal_mostrar }}</td>

                                                                @php
                                                                    $baseParaCF = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.','');

                                                                    $baseParaCF_mostar = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $baseParaCF_mostar }}</td>

                                                                @php
                                                                    $aux = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                {{-- <td style="text-align: end">{{ $compra->tipoCompra }}</td> --}}

                                                                <td>{{ $compra->codigoControl }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                    {{--! sumas footer --}}
                                                    @php
                                                        $suma_importeTotal = 0;
                                                        $suma_ice = 0;
                                                        $suma_iehd = 0;
                                                        $suma_ipj = 0;
                                                        $suma_tasas = 0;
                                                        $suma_otros = 0;
                                                        $suma_exentos = 0;
                                                        $suma_tasaCero = 0;
                                                        $suma_descuentos = 0;
                                                        $suma_gifCard = 0;
                                                        foreach ($comprasEncontradas as $compra) {
                                                            $suma_importeTotal = $suma_importeTotal + $compra->importeTotal;
                                                            $suma_ice = $suma_ice + $compra->ice;
                                                            $suma_iehd = $suma_iehd + $compra->iehd;
                                                            $suma_ipj = $suma_ipj + $compra->iehd;
                                                            $suma_tasas = $suma_tasas + $compra->tasas;
                                                            $suma_otros = $suma_otros + $compra->otrosNoSujetosaCF;
                                                            $suma_exentos = $suma_exentos + $compra->exentos;
                                                            $suma_tasaCero = $suma_tasaCero + $compra->tasaCero;
                                                            $suma_descuentos = $suma_descuentos + $compra->descuentos;
                                                            $suma_gifCard = $suma_gifCard + $compra->gifCard;
                                                        }
                                                    @endphp
                                                    @php
                                                        $suma_subtotal = $suma_importeTotal -$suma_ice - $suma_iehd - $suma_ipj - $suma_tasas - $suma_otros - $suma_exentos - $suma_tasaCero;

                                                        $suma_baseCF = $suma_subtotal - $suma_descuentos - $suma_gifCard;
                                                        $suma_cf = round($suma_baseCF * 0.13, 2);
                                                    @endphp
                                                    <tfoot>
                                                        <tr class="footer-de-tabla">
                                                            @if ($idSucursalBuscada == '-1')
                                                                <td colspan="8" class="text-center"><b>TOTALES</b></td>                                                            
                                                            @else
                                                                <td colspan="7" class="text-center"><b>TOTALES</b></td>
                                                            @endif
                                                            <th class="text-right"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_baseCF,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_cf,2,'.',',') }}</p></th>
                                                            <th colspan="1"></th>
                                                        </tr>
                                                    </tfoot>

                                                @else

                                                    <thead>
                                                        <tr style="font-size:10px">
                                                            <th class="align-middle">Nº</th>

                                                            @if ($idSucursalBuscada == '-1')
                                                                <th class="align-middle">SUCURSAL</th>                                                                
                                                            @endif

                                                            <th class="align-middle">NIT PROVEEDOR</th>
                                                            <th class="align-middle"><p style="padding: 0 100px 0 100px">PROVEEDOR</p></th>
                                                            <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                            <th class="align-middle">NUMERO FACTURA</th>
                                                            <th class="align-middle">NUMERO DUI/DIM</th>
                                                            <th class="align-middle">FECHA DE FACTURA/DUI/DIM</th>
                                                            <th class="align-middle">IMPORTE TOTAL COMPRA</th>
                                                            <th class="align-middle">IMPORTE ICE</th>
                                                            <th class="align-middle">IMPORTE IEHD</th>
                                                            <th class="align-middle">IMPORTE IPJ</th>
                                                            <th class="align-middle">TASAS</th>
                                                            <th class="align-middle">NO SUJETO A CREDITO FISCAL</th>
                                                            <th class="align-middle">IMPORTES EXENTOS</th>
                                                            <th class="align-middle">COMPRAS GRAVADAS A TASA CERO</th>
                                                            <th class="align-middle">SUBTOTAL</th>
                                                            <th class="align-middle">DESCUENTOS /BONIFICACIONES /REBAJAS SUJETAS AL IVA</th>
                                                            <th class="align-middle">IMPORTE GIFT CARD</th>
                                                            <th class="align-middle">IMPORTE BASE CF</th>
                                                            <th class="align-middle">CREDITO FISCAL</th>
                                                            <th class="align-middle">TIPO COMPRA</th>
                                                            <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody style="font-size: 12px">
                                                        @php // declaramos la variable, no la imprimimos aun
                                                            $numero = 0;
                                                        @endphp
                                                        @foreach ($comprasEncontradas as $compra)
                                                            <tr id="fila{{$compra->id}}" id_validador="{{$compra->nitProveedor.$compra->codigoAutorizacion.$compra->numeroFactura.$compra->fecha}}"
                                                                class="{{$compra->nitProveedor.$compra->codigoAutorizacion.$compra->numeroFactura.$compra->fecha}}">

                                                                <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                                @if ($idSucursalBuscada == '-1')
                                                                    <td>{{ $compra->descripcion }}</td>
                                                                @endif

                                                                <td class="nit">{{ $compra->nitProveedor }}</td>
                                                                <td>{{ $compra->razonSocialProveedor }}</td>
                                                                <td class="autorizacion">{{ $compra->codigoAutorizacion }}</td>
                                                                <td class="numerofac text-right">{{ $compra->numeroFactura }}</td>
                                                                <td>{{ $compra->dim }}</td>

                                                                @php
                                                                    $f = explode('-',$compra->fecha);
                                                                    $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                                @endphp
                                                                <td class="text-right fecha">{{$f2}}</td>

                                                                @php
                                                                    $aux = number_format($compra->importeTotal,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux}}</td>

                                                                @php
                                                                    $aux = number_format($compra->ice,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->iehd,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->ipj,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->tasas,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->otrosNoSujetosaCF,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->exentos,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->tasaCero,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $subtotal = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.','');

                                                                    $subtotal_mostrar = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $subtotal_mostrar }}</td>

                                                                @php
                                                                    $aux = number_format($compra->descuentos,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($compra->gifCard,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $baseParaCF = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.','');

                                                                    $baseParaCF_mostar = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $baseParaCF_mostar }}</td>

                                                                @php
                                                                    $aux = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                <td style="text-align: end">{{ $compra->tipoCompra }}</td>

                                                                <td>{{ $compra->codigoControl }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                    {{--! sumas footer --}}
                                                    @php
                                                        $suma_importeTotal = 0;
                                                        $suma_ice = 0;
                                                        $suma_iehd = 0;
                                                        $suma_ipj = 0;
                                                        $suma_tasas = 0;
                                                        $suma_otros = 0;
                                                        $suma_exentos = 0;
                                                        $suma_tasaCero = 0;
                                                        $suma_descuentos = 0;
                                                        $suma_gifCard = 0;
                                                        foreach ($comprasEncontradas as $compra) {
                                                            $suma_importeTotal = $suma_importeTotal + $compra->importeTotal;
                                                            $suma_ice = $suma_ice + $compra->ice;
                                                            $suma_iehd = $suma_iehd + $compra->iehd;
                                                            $suma_ipj = $suma_ipj + $compra->iehd;
                                                            $suma_tasas = $suma_tasas + $compra->tasas;
                                                            $suma_otros = $suma_otros + $compra->otrosNoSujetosaCF;
                                                            $suma_exentos = $suma_exentos + $compra->exentos;
                                                            $suma_tasaCero = $suma_tasaCero + $compra->tasaCero;
                                                            $suma_descuentos = $suma_descuentos + $compra->descuentos;
                                                            $suma_gifCard = $suma_gifCard + $compra->gifCard;
                                                        }
                                                    @endphp
                                                    @php
                                                        $suma_subtotal = $suma_importeTotal -$suma_ice - $suma_iehd - $suma_ipj - $suma_tasas - $suma_otros - $suma_exentos - $suma_tasaCero;

                                                        $suma_baseCF = $suma_subtotal - $suma_descuentos - $suma_gifCard;
                                                        $suma_cf = round($suma_baseCF * 0.13, 2);
                                                    @endphp
                                                    <tfoot>
                                                        <tr class="footer-de-tabla">
                                                            @if ($idSucursalBuscada == '-1')
                                                                <td colspan="8" class="text-center"><b>TOTALES</b></td>                                                            
                                                            @else
                                                                <td colspan="7" class="text-center"><b>TOTALES</b></td>
                                                            @endif
                                                            <th class="text-right"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_ice,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_iehd,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_ipj,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_tasas,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_otros,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_exentos,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_tasaCero,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_descuentos,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_gifCard,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_baseCF,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_cf,2,'.',',') }}</p></th>
                                                            <th colspan="2"></th>
                                                        </tr>
                                                    </tfoot>
                                                @endif
                                            </table>
                                        </div>
                                    @endif
                                    {{-- ! fin compras --}}

                                    {{-- ! ventas --}}
                                    @if ($concepto == "ventas")
                                        <div class="card-body">
                                            <table id="tablaResultados" data-page-length='10' class="table table-bordered table-striped display" style="width:100%">
                                                @if ($mostrar_vista=="resumen")
                                                    <thead>
                                                        <tr style="font-size:10px">
                                                            <th class="align-middle">Nº</th>
                                                            @if ($idSucursalBuscada == '-1')
                                                                <th class="align-middle">SUCURSAL</th>
                                                            @endif
                                                            <th class="align-middle">FECHA DE LA FACTURA</th>
                                                            <th class="align-middle">NUMERO FACTURA</th>
                                                            <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                            <th class="align-middle">NIT/CI CLIENTE</th>
                                                            <th class="align-middle">COMPLEMENTO</th>
                                                            <th class="align-middle"><p style="padding: 0 100px 0 100px">CLIENTE</p></th>
                                                            <th class="align-middle">IMPORTE TOTAL VENTA</th>
                                                            <th class="align-middle">SUBTOTAL</th>
                                                            <th class="align-middle">IMPORTE BASE DF</th>
                                                            <th class="align-middle">DEBITO FISCAL</th>
                                                            <th class="align-middle">ESTADO</th>
                                                            <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody style="font-size: 12px">
                                                        @php // declaramos la variable, no la imprimimos aun
                                                            $numero = 0;
                                                        @endphp
                                                        @foreach ($ventasEncontradas as $venta)
                                                            <tr id="fila{{$venta->id}}" id_validador="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}"
                                                                class="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}">

                                                                <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                                {{--! sucursal --}}
                                                                @if ($idSucursalBuscada == '-1')
                                                                    <td>{{ $venta->descripcion }}</td>
                                                                @endif

                                                                {{--! fecha --}}
                                                                @php
                                                                    $f = explode('-',$venta->fecha);
                                                                    $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                                @endphp
                                                                <td class="text-right">{{$f2}}</td>

                                                                <td>{{ $venta->numeroFactura }}</td>
                                                                <td>{{ $venta->codigoAutorizacion }}</td>
                                                                <td>{{ $venta->ciNitCliente }}</td>
                                                                <td>{{ $venta->complemento }}</td>
                                                                <td>{{ $venta->razonSocialCliente }}</td>

                                                                @php
                                                                    $aux = number_format($venta->importeTotal,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux}}</td>

                                                                @php
                                                                    $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                                                                    $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $subtotal_mostrar }}</td>

                                                                @php
                                                                    $baseParaCF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                                                    $baseParaCF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $baseParaCF_mostar }}</td>

                                                                @php
                                                                    $auxDf = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $auxDf }}</td>

                                                                <td style="text-align: center">{{ $venta->estado }}</td>

                                                                <td>{{ $venta->codigoControl }}</td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>

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
                                                            @if ($idSucursalBuscada == '-1')
                                                                <td colspan="8" class="text-center"><b>TOTALES</b></td>                                                            
                                                            @else
                                                                <td colspan="7" class="text-center"><b>TOTALES</b></td>
                                                            @endif
                                                            <th class="text-right"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_baseDF,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_df,2,'.',',') }}</p></th>
                                                            <th colspan="2"></th>
                                                        </tr>
                                                    </tfoot>
                                                @else

                                                    <thead>
                                                        <tr style="font-size:10px">
                                                            <th class="align-middle">Nº</th>
                                                            @if ($idSucursalBuscada == '-1')
                                                                <th class="align-middle">SUCURSAL</th>
                                                            @endif
                                                            <th class="align-middle">FECHA DE LA FACTURA</th>
                                                            <th class="align-middle">NUMERO FACTURA</th>
                                                            <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                            <th class="align-middle">NIT CI CLIENTE</th>
                                                            <th class="align-middle">COMPLEMENTO</th>
                                                            <th class="align-middle"><p style="padding: 0 100px 0 100px">CLIENTE</p></th>
                                                            <th class="align-middle">IMPORTE TOTAL VENTA</th>
                                                            <th class="align-middle">IMPORTE ICE</th>
                                                            <th class="align-middle">IMPORTE IEHD</th>
                                                            <th class="align-middle">IMPORTE IPJ</th>
                                                            <th class="align-middle">TASAS</th>
                                                            <th class="align-middle">NO SUJETO A IVA</th>
                                                            <th class="align-middle">EXPORTACIONES Y EXENTOS</th>
                                                            <th class="align-middle">VENTAS GRAVADAS A TASA CERO</th>
                                                            <th class="align-middle">SUBTOTAL</th>
                                                            <th class="align-middle">DESCUENTOS /BONIFICACIONES /REBAJAS SUJETAS AL IVA</th>
                                                            <th class="align-middle">IMPORTE GIFT CARD</th>
                                                            <th class="align-middle">IMPORTE BASE DF</th>
                                                            <th class="align-middle">DEBITO FISCAL</th>
                                                            <th class="align-middle">ESTADO</th>
                                                            <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th>
                                                            <th class="align-middle">TIPO VENTA</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody style="font-size: 12px">
                                                        @php // declaramos la variable, no la imprimimos aun
                                                            $numero = 0;
                                                        @endphp
                                                        @foreach ($ventasEncontradas as $venta)
                                                            <tr id="fila{{$venta->id}}" id_validador="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}"
                                                                class="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}">

                                                                <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                                {{--! sucursal --}}
                                                                @if ($idSucursalBuscada == '-1')
                                                                    <td>{{ $venta->descripcion }}</td>
                                                                @endif

                                                                {{--! fecha --}}
                                                                @php
                                                                    $f = explode('-',$venta->fecha);
                                                                    $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                                @endphp
                                                                <td class="text-right">{{$f2}}</td>

                                                                <td>{{ $venta->numeroFactura }}</td>
                                                                <td>{{ $venta->codigoAutorizacion }}</td>
                                                                <td>{{ $venta->ciNitCliente }}</td>
                                                                <td>{{ $venta->complemento }}</td>
                                                                <td>{{ $venta->razonSocialCliente }}</td>

                                                                @php
                                                                    $aux = number_format($venta->importeTotal,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux}}</td>

                                                                @php
                                                                    $aux = number_format($venta->ice,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->iehd,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->ipj,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->tasas,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->otrosNoSujetosaIva ,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->exportacionesyExentos,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->tasaCero,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                                                                    $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $subtotal_mostrar }}</td>

                                                                @php
                                                                    $aux = number_format($venta->descuentos,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $aux = number_format($venta->gifCard,2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $aux }}</td>

                                                                @php
                                                                    $baseParaCF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                                                    $baseParaCF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $baseParaCF_mostar }}</td>

                                                                @php
                                                                    $auxDf = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                                                @endphp
                                                                <td style="text-align: end">{{ $auxDf }}</td>

                                                                <td style="text-align: center">{{ $venta->estado }}</td>

                                                                <td>{{ $venta->codigoControl }}</td>

                                                                <td style="text-align: center">{{ $venta->tipoVenta }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

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
                                                            @if ($idSucursalBuscada == '-1')
                                                                <td colspan="8" class="text-center"><b>TOTALES</b></td>                                                                
                                                            @else
                                                                <td colspan="7" class="text-center"><b>TOTALES</b></td>
                                                            @endif
                                                            <th class="text-right"><p>{{ number_format($suma_importeTotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_ice,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_iehd,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_ipj,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_tasas,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_otros,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_exportaciones,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_tasaCero,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_subtotal,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_descuentos,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_gifCard,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_baseDF,2,'.',',') }}</p></th>
                                                            <th class="text-right"><p>{{ number_format($suma_df,2,'.',',') }}</p></th>
                                                            <th colspan="3"></th>
                                                        </tr>
                                                    </tfoot>
                                                @endif
                                            </table>
                                        </div>
                                    @endif
                                    {{-- ! fin ventas --}}

                                    {{--? doby --}}
                                </div>
                            </div>
                        </div>
                        {{-- ! Fin DataTable --}}
                    @endisset
                </div>
            </section>
            {{-- ! Fin Contenido --}}
        </div>
        <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuComprasConsultas').addClass('active');
    </script>

    {{--! libreria numeral --}}
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    


    {{-- ! <!-- DataTables  & Plugins --> --}}
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    {{--! DATATABLE --}}
    <script>
        $(function () {
            $("#tablaResultados").DataTable({
                "responsive": false,
                "lengthChange": true,
                // "autoWidth": false,
                "ordering": false,
                "language":
                {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "lengthMenu": "Mostrar " +
                    `<select class='form-control input-sm'>
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
                    <option value='-1'>Todos</option>
                    </select>`+
                    " registros por página",

                    "zeroRecords": "No tiene información",
                    /* "info": "Página _PAGE_ de _PAGES_", */
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search':'Buscar:',
                    'paginate':{
                        'next':'>>',
                        'previous':'<<'
                    },
                    "loadingRecords": "Cargando...",
                },
                "scrollCollapse": true,
                "scrollY": 300,
                "scrollX": true,

            }).buttons().container().appendTo('#tablaResultados_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
