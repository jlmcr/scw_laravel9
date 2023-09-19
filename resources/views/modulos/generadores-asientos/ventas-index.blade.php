@extends('plantilla.adminlte')

@section('titulo')
    Ventas - Generador Asientos Contables
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{--! fixed columns --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/custom-code/FixedColumns-4.1.0/css/fixedColumns.dataTables.css') }}">
@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <a href="{{route('generador-asientos-de-ventas.index',['process'=>'menu'])}}">
                            <h1 class="m-0">Generador de Asientos Contables </h1>
                            <h1 class="m-0 text-red">Ventas</h1>
                        </a>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Herramientas</li>
                            <li class="breadcrumb-item active">Generar Asientos</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--* Buscador --}}
                <form method="GET" action="{{ route('generador-asientos-de-ventas.index') }}">
                    <div class="row">
                        {{--! Criterios de busqueda --}}

                        <input type="hidden" name="process" value="search">
                        {{--* fechas referencias --}}
                        @php
                            $fi = date('d/m/Y', strtotime($datosEjercicioActivo->fechaInicio));
                            $ff = date('d/m/Y', strtotime($datosEjercicioActivo->fechaCierre));
                        @endphp
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="p-0 m-0">Periodo habilitado</label>
                                <p class="p-0 m-0">
                                    <small>Fecha Mímina: {{$fi}}</small>
                                </p>
                                <p class="p-0 m-0">
                                    <small>Fecha Máxima: {{$ff}}</small>
                                </p>
                            </div>
                        </div>

                        {{--* gestion --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Gestión</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mes</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sucursal</label>
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
                            <button type="submit" class="btn btn-block btn-outline-info mt-2">
                                <i class="fas fa-search"> </i>
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
                {{--* Fin Buscador --}}

                <br>

                @isset($ventasEncontradas)
                    {{-- ! DataTable --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-danger card-outline overflow-auto">
                                <form id="frmCrear-Registros" class="frmCrear-Registros" action="{{route('generador-asientos-de-ventas.store')}}" method="POST">
                                    @csrf
                                    @isset($gestionBuscada)
                                        <input type="hidden" name="gestionBuscada" value="{{$gestionBuscada}}">
                                        <input type="hidden" name="mesBuscado" value="{{$mesBuscado}}">
                                        <input type="hidden" name="idSucursalBuscada" value="{{$idSucursalBuscada}}">
                                    @endisset

                                    {{--! botones generar seleccionar card-header --}}
                                    <div class="card-header">
                                        <div class="row justify-content-end">
                                            <!-- /.card-tools -->
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <!-- /.fin card-tools -->
                                        </div>

                                        <div class="row">
                                            <div class="col-12 mb-0 pb-0">
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
                                                    <p class="p-0 m-0">Periodo: {{$mesBuscado}} - {{$gestionBuscada}}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-md-12 mt-2 w-100">
                                                <select id="codigo-general-debe" class="form-control select2 w-100">
                                                    <option value="vacio" selected>Cuenta Debe...</option>
                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                        <option value="{{$sub_cuenta->id}}">
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-2 col-md-12 mt-2 w-100">
                                                <select id="codigo-general-haber" class="form-control select2 w-100">
                                                    <option value="vacio" selected>Cuenta Haber...</option>
                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                        <option value="{{$sub_cuenta->id}}">
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-12 mt-2">
                                                <select id="tipo_factura_general" class="form-control w-100">
                                                    <option value="vacio">Factura...</option>
                                                    <option value="resto">Resto</option>
                                                    <option value="descuento">Con Descuento</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-12">
                                                <a id="btn-seleccionar-todos" class="btn btn-block btn-outline-dark mt-2">
                                                    <i class="fas fa-check-square"></i>
                                                    Seleccionar todos
                                                </a>
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <a id="btn-quitar-seleccion" class="btn btn-block btn-outline-dark mt-2">
                                                    <i class="far fa-square"></i>
                                                    Quitar selección
                                                </a>
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <button type="submit" name="btn-generar-asientos" class="btn btn-block btn-outline-success mt-2">
                                                    <i class="fas fa-laptop-medical"></i>
                                                    Generar Asientos
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                    {{--! botones generar seleccionar card-header --}}

                                    <div class="card-body">
                                        <table id="tablaVentas" data-page-length='-1' class="table table-bordered table-striped display" style="width:100%">
                                            <thead>
                                                <tr style="font-size:10px" class="text-center">

                                                    <th class="align-middle"></th>

                                                    <th class="align-middle">
                                                        <p style="padding: 0 60px 0 60px">CUENTA_(DEBE) ACTIVO+</p>
                                                    </th>
                                                    <th class="align-middle">
                                                        <p style="padding: 0 60px 0 60px">CUENTA_(HABER) INGRESO+</p>
                                                    </th>
                                                    <th class="align-middle">
                                                        <p style="padding: 0 40px 0 40px">TIPO FACTURA</p>
                                                    </th>
                                                    <th class="align-middle">REGISTRO</th>

                                                    <th class="align-middle">Nº</th>
                                                    @if ($idSucursalBuscada == "-1")
                                                        <th class="align-middle">SUCURSAL</th>
                                                    @endif
                                                    <th class="align-middle">FECHA DE LA FACTURA</th>
                                                    <th class="align-middle">NUMERO FACTURA</th>
                                                    <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                    <th class="align-middle">NIT/CI CLIENTE</th>
                                                    {{-- <th class="align-middle">COMPLEMENTO</th> --}}
                                                    <th class="align-middle"><p style="padding: 0 70px 0 70px">CLIENTE</p></th>
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
                                                    {{-- <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th> --}}
                                                    <th class="align-middle">TIPO VENTA</th>
                                                    {{-- <th class="align-middle">ACCIONES</th> --}}
                                                </tr>
                                            </thead>

                                            <tbody style="font-size: 12px">
                                                @php // declaramos la variable, no la imprimimos aun
                                                    $numero = 0;
                                                @endphp
                                                @foreach ($ventasEncontradas as $venta)
                                                    @if ($venta->estado == "V" )
                                                        <tr id="fila{{$venta->id}}" id_validador="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}"
                                                            class="{{$venta->ciNitCliente.$venta->codigoAutorizacion.$venta->numeroFactura.$venta->fecha}}">

                                                            {{--! SELECCIÓN --}}
                                                            <td>
                                                                <input type="checkbox" class="select_checkbox">
                                                            </td>

                                                            <td style="font-size: 14px; background-color:rgb(254, 254, 170)">
                                                                <select name="codigo_debe[]" class="codigo_debe form-control select2 w-100">
                                                                    <option value="vacio" ></option>
                                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                                        <option value="{{$sub_cuenta->id}}">
                                                                            {{$sub_cuenta->descripcion}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td style="font-size: 14px; background-color:rgb(254, 254, 170)">
                                                                <select name="codigo_haber[]" class="codigo_haber form-control select2 w-100">
                                                                    <option value="vacio" ></option>
                                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                                        <option value="{{$sub_cuenta->id}}">
                                                                            {{$sub_cuenta->descripcion}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td style="font-size: 14px; background-color:rgb(254, 254, 170)">
                                                                <select name="tipo_factura[]" class="tipo_factura form-control w-100">
                                                                    @php
                                                                        $conceptos_no_permitidos = 0;
                                                                        $conceptos_no_permitidos = $venta->ice + $venta->iehd + $venta->ipj + $venta->tasas + $venta->otrosNoSujetosaIva + $venta->exportacionesyExentos + $venta->tasaCero + $venta->descuentos + $venta->gifCard;
                                                                    @endphp

                                                                    @if ($conceptos_no_permitidos == 0)
                                                                        <option value="vacio"></option>
                                                                        <option value="resto" selected>Resto</option>
                                                                        <option value="descuento">Con Descuento</option>
                                                                    @else
                                                                        <option value="vacio"></option>
                                                                        <option value="resto">Resto</option>
                                                                        <option value="descuento">Con Descuento</option>
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if ($venta->registroContable != "")
                                                                @php
                                                                    $rcontable = explode("*",$venta->registroContable);
                                                                @endphp
                                                                <a class="btn-xs btn-success form-control h-50 text-center">{{$rcontable[0]}}</a>
                                                                @endif
                                                            </td>

                                                            {{--! ****************************** --}}

                                                            <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                            {{--! sucursal --}}
                                                            @if ($idSucursalBuscada == "-1")
                                                                <td>{{$venta->descripcion}}</td>
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
                                                            {{-- <td>{{ $venta->complemento }}</td> --}}
                                                            <td>{{ $venta->razonSocialCliente }}</td>

                                                            @php
                                                                $aux = number_format($venta->importeTotal,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end;">{{ $aux}}</td>

                                                            @php
                                                                $aux = number_format($venta->ice,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->ice != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->iehd,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->iehd != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->ipj,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->ipj != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->tasas,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->tasas != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->otrosNoSujetosaIva ,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->otrosNoSujetosaIva != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->exportacionesyExentos,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->exportacionesyExentos != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->tasaCero,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->tasaCero != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $subtotal = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.','');

                                                                $subtotal_mostrar = number_format(round($venta->importeTotal - $venta->ice - $venta->iehd - $venta->ipj - $venta->tasas - $venta->otrosNoSujetosaIva - $venta->exportacionesyExentos - $venta->tasaCero, 2),2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end">{{ $subtotal_mostrar }}</td>

                                                            @php
                                                                $aux = number_format($venta->descuentos,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->descuentos != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $aux = number_format($venta->gifCard,2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end; @if ($venta->gifCard != 0) color:#c9184a;font-weight:bold; @endif">{{ $aux }}</td>

                                                            @php
                                                                $baseParaDF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                                                $baseParaDF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                                            @endphp
                                                            <td style="text-align: end">{{ $baseParaDF_mostar }}</td>

                                                            @php
                                                                $debitoFiscal = round($baseParaDF * 0.13, 2);
                                                                $debitoFiscal_mostrar = number_format(round($baseParaDF * 0.13, 2),2,'.',',');

                                                            @endphp
                                                            <td style="text-align: end">{{ $debitoFiscal_mostrar }}</td>

                                                            <input type="hidden" name="id_venta[]" value="{{$venta->id}}">
                                                            <input type="hidden" name="baseParaDF[]" value="{{$baseParaDF}}">
                                                            <input type="hidden" name="debitoFiscal[]" value="{{$debitoFiscal}}">

                                                            <td style="text-align: center">{{ $venta->estado }}</td>

                                                            {{-- <td>{{ $venta->codigoControl }}</td> --}}

                                                            <td style="text-align: center">{{ $venta->tipoVenta }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>

                                            <tfoot>
                                                <tr style="font-size:10px" class="text-center">

                                                    <th class="align-middle"></th>

                                                    <th class="align-middle">
                                                        <p style="padding: 0 60px 0 60px">CUENTA_(DEBE) ACTIVO+</p>
                                                    </th>
                                                    <th class="align-middle">
                                                        <p style="padding: 0 60px 0 60px">CUENTA_(HABER) INGRESO+</p>
                                                    </th>
                                                    <th class="align-middle">
                                                        <p style="padding: 0 40px 0 40px">TIPO FACTURA</p>
                                                    </th>
                                                    <th class="align-middle">REGISTRO</th>

                                                    <th class="align-middle">Nº</th>
                                                    @if ($idSucursalBuscada == "-1")
                                                        <th class="align-middle">SUCURSAL</th>
                                                    @endif
                                                    <th class="align-middle">FECHA DE LA FACTURA</th>
                                                    <th class="align-middle">NUMERO FACTURA</th>
                                                    <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                    <th class="align-middle">NIT/CI CLIENTE</th>
                                                    {{-- <th class="align-middle">COMPLEMENTO</th> --}}
                                                    <th class="align-middle"><p style="padding: 0 70px 0 70px">CLIENTE</p></th>
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
                                                    {{-- <th class="align-middle"><p style="padding: 0 30px 0 30px">CODIGO DE CONTROL</p></th> --}}
                                                    <th class="align-middle">TIPO VENTA</th>
                                                    {{-- <th class="align-middle">ACCIONES</th> --}}
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </form>
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
        $('#menuGeneradores').addClass('menu-open');
        $('#menuGeneradores2').addClass('active');
        $('#submenuGeneradorVentas').addClass('active');
    </script>
    {{--! colapsar menu --}}
    <script>
        // document.getElementById("body").classList.remove('')
        document.getElementById("body").classList.add('sidebar-collapse');
    </script>

    {{--! duplicados --}}
    <script src="{{ asset('/custom-code/modulos/ventas/ventas-duplicadas.js') }}"></script>


    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>


    {{--! Pregunta desea GENERAR REGISTROS--}}
    <script>
        $('.frmCrear-Registros').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea generar Registros/Asientos Contables?',
            text: "¡Generará registros contables de las facturas de Ventas!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Generar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

    {{--! mensaje de generacion exitosa --}}
    @isset($_GET["cantVentGen"])
        @if ($_GET["cantVentGen"] == 0)
            <script>
                toastr.warning("No se generó ningún registro contable, asegúrese de haber asignado los datos necesarios para su registro.");
            </script>
        @endif
        @if ($_GET["cantVentGen"] > 0)
            <script>
                toastr.success( "{{$_GET['cantVentGen']}} registro(s) contable(s) generado(s) exitosamente.");
            </script>
        @endif
    @endisset

    {{--! error por el try cath --}}
    @if (Session('generar-asientos')=='error')
        <script>
                toastr.error('Por favor vuelve a intentarlo, antes revise el Libro Diario.')
        </script>
    @endif


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
    {{--! fixed columns --}}
    <script src="{{ asset('/custom-code/FixedColumns-4.1.0/js/dataTables.fixedColumns.js') }}"></script>

    {{--! DATATABLE --}}
    <script>
        $(function () {
            $("#tablaVentas").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,

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
                "scrollY": '380px',
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns":
                {
                    "left": 1,
                }

            }).buttons().container().appendTo('#tablaVentas_wrapper .col-md-6:eq(0)');
        });
    </script>

    {{--! SELECCION Y DESSELECCION --}}
    <script>
        $('document').ready(function () {
            /* seleccion */
            $('#btn-seleccionar-todos').click(function () {
                $('.select_checkbox').prop("checked", true)
            });
            /* quitar seleccion */
            $('#btn-quitar-seleccion').click(function () {
                $('.select_checkbox').prop("checked", false);
            });

            /* ---- CODIGO GENERAL PARA DEBE ---- */
            $('#codigo-general-debe').change(function () {
                var codigo_subcuenta = $('#codigo-general-debe option:selected').val();
                //console.log(codigo_subcuenta);
                //recorremos los checkbox
                $("input:checkbox[class=select_checkbox]:checked").each(function () {
                    //buscamos el option con el value
                    $(this).parent().parent().find(".codigo_debe option[value="+ codigo_subcuenta +"]").attr("selected",true)
                    //mostramos la option en la vista
                    $(this).parent().parent().find(".codigo_debe").val(codigo_subcuenta).trigger('change.select2');
                });
            });

            /* ---- CODIGO GENERAL PARA HABER  ----*/
            $('#codigo-general-haber').change(function () {
                var codigo_subcuenta = $('#codigo-general-haber option:selected').val();
                //console.log(codigo_subcuenta);
                //recorremos los checkbox
                $("input:checkbox[class=select_checkbox]:checked").each(function () {
                    //buscamos el option con el value
                    $(this).parent().parent().find(".codigo_haber option[value="+ codigo_subcuenta +"]").attr("selected",true)
                    //mostramos la option en la vista
                    $(this).parent().parent().find(".codigo_haber").val(codigo_subcuenta).trigger('change.select2');
                });
            });

            /* ---- TIPO FACTURA GENERAL  ----*/
            $('#tipo_factura_general').change(function () {
                var tipo_factura = $('#tipo_factura_general option:selected').val();
                //console.log(tipo_factura);
                //recorremos los checkbox
                $("input:checkbox[class=select_checkbox]:checked").each(function () {
                    //buscamos el option con el value
                    $(this).parent().parent().find(".tipo_factura option[value="+ tipo_factura +"]").attr("selected",true)
                    //mostramos la option en la vista
                    $(this).parent().parent().find(".tipo_factura").val(tipo_factura).trigger('change.select2');
                });
            });
        });

    </script>

    <script>
        funcionDuplicados();
    </script>
@endsection


