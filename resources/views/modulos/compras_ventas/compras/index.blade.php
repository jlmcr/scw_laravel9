@extends('plantilla.adminlte')

@section('titulo')
Compras
@endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!----Creando mi Token para eliminar multiples ventas--->


    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{--! Jquery UI --}}
    <link rel="stylesheet" href="{{ asset('custom-code/jquery-ui-1.13.2/jquery-ui.min.css') }}">
    {{--! fixed columns --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/custom-code/FixedColumns-4.1.0/css/fixedColumns.dataTables.css') }}">


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
            text-align: center;v
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

@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header ">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <a href="/compras/?process=menu&idEmpresaActiva={{Auth::user()->idEmpresaActiva}}">
                            Registrar Compras
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

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--* Buscador --}}
                <form method="GET" action="{{ route('compras.index') }}">
                    <div class="row">
                        {{--! Criterios de busqueda --}}

                        <input type="hidden" name="process" value="search">
                        {{--* empresa activa --}}
                        <input type="hidden" name="idEmpresaActiva" value="{{Auth::user()->idEmpresaActiva}}">

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
                        <div class="col-md-2">
                            <label for=""></label>
                            @if (isset($mesBuscado))
                            <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-importar-compras" name="btnImportarCompra" id="btnImportarCompra" >
                                <i class="fas fa-file-excel"></i>
                                Importar
                            </button>
                            @endif
                        </div>
                    </div>

                </form>
                {{--* Fin Buscador --}}

                <br>
                @if (isset($errors) && $errors->any())
                    <div class="row">
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error )
                                {{$error}}
                            @endforeach
                        </div>
                    </div>
                @endif

                @isset($comprasEncontradas)
                    {{-- ! DataTable --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <!-- /.card-header -->
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
                                        <div class="col-md-9 mb-0 pb-0">
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

                                        <div class="col-lg-3 mb-0 pb-0">
                                            <div class="row">
                                                {{-- menu de eliminacion multiple --}}
                                                <div class="col-2 m-0 p-0">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-block btn-outline-danger mt-2 dropdown-toggle"
                                                                data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i><span class="caret"></span>
                                                        </button>

                                                        <ul class="dropdown-menu" role="menu">
                                                            <li class="ml-3 mr-2 text-center"><a>Eliminar muliples registros</a></li>

                                                            <li class="ml-3 mr-2">
                                                                <a id="btn-seleccionar-todos" class="mt-3" style="cursor: pointer">
                                                                    <i class="fas fa-check-square"></i>
                                                                    Seleccionar Todos
                                                                </a>
                                                            </li>
                                                            <li class="ml-3 mr-2">
                                                                <a id="btn-quitar-seleccion" class="mt-3" style="cursor: pointer">
                                                                    <i class="far fa-square"></i>
                                                                    Des-Seleccionar
                                                                </a>
                                                            </li>
                                                            <li class="ml-3 mr-2">
                                                                {{--! data-url="{{ rout('') }}"  como atributo podria poner la ruta o url de eliminacion, esto en caso de usar un archivo js externo ya que ahí no se acepta las llaves--}}
                                                                {{-- tambien se hizo la eliminacion con ajax para no tener que envolver todo en un form --}}
                                                                <a class="borrarAll mt-3 pr-8 text-red" data-ruta-url="{{ route('eliminar-multiples-compras') }}" style="cursor: pointer">
                                                                    <i class="fas fa-trash-alt"></i>Eliminar Seleccionados
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                {{-- boton agegar --}}
                                                <div class="col-9 ml-2">
                                                    <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-compra" name="btnAgregarCompra" id="btnAgregarCompra" >
                                                        <i class="fas fa-plus"></i> Agregar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /. fin card-header -->

                                <div class="card-body">
                                    <table id="tablaCompras" data-page-length='10' class="table table-bordered table-striped display" style="width:100%">
                                        <thead>
                                            <tr style="font-size:10px">
                                                <th class="align-middle">Nº</th>
                                                {{-- <th class="align-middle">ESPECI FICACION</th> --}}
                                                <th class="align-middle">ELIMINAR</th>
                                                @if ($idSucursalBuscada == "-1")
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
                                                <th class="align-middle">ACCIONES</th>
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

                                                    {{--! Eliminar --}}
                                                    <td>
                                                        <input type="checkbox" class="delete_checkbox" data-id="{{$compra->id}}">
                                                    </td>

                                                    {{--! sucursal --}}
                                                    @if ($idSucursalBuscada == "-1")
                                                        <td>{{ $compra->descripcion }}</td>
                                                    @endif

                                                    <td class="nit">{{ $compra->nitProveedor }}</td>
                                                    <td>{{ $compra->razonSocialProveedor }}</td>
                                                    <td>{{ $compra->codigoAutorizacion }}</td>
                                                    <td class="text-right">{{ $compra->numeroFactura }}</td>
                                                    <td>{{ $compra->dim }}</td>

                                                    @php
                                                        $f = explode('-',$compra->fecha);
                                                        $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                    @endphp
                                                    <td class="text-right">{{$f2}}</td>

                                                    @php
                                                        $aux = number_format($compra->importeTotal,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end">{{ $aux}}</td>

                                                    @php
                                                        $aux = number_format($compra->ice,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->ice != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->iehd,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->iehd != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->ipj,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->ipj != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->tasas,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->tasas != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->otrosNoSujetosaCF,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->otrosNoSujetosaCF != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->exentos,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->exentos != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->tasaCero,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->tasaCero != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    {{-- subtotal --}}
                                                    @php
                                                        $subtotal = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.','');

                                                        $subtotal_mostrar = number_format(round($compra->importeTotal - $compra->ice - $compra->iehd - $compra->ipj - $compra->tasas - $compra->otrosNoSujetosaCF - $compra->exentos - $compra->tasaCero, 2),2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end;">{{ $subtotal_mostrar }}</td>

                                                    @php
                                                        $aux = number_format($compra->descuentos,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->descuentos != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $aux = number_format($compra->gifCard,2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end; @if ($compra->gifCard != 0) color:#c9184a; @endif">{{ $aux }}</td>

                                                    @php
                                                        $baseParaCF = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.','');

                                                        $baseParaCF_mostar = number_format(round($subtotal - $compra->descuentos - $compra->gifCard, 2),2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end;">{{ $baseParaCF_mostar }}</td>

                                                    @php
                                                        $aux = number_format(round($baseParaCF * 0.13, 2),2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end;">{{ $aux }}</td>

                                                    <td style="text-align: end;">{{ $compra->tipoCompra }}</td>


                                                    @if ($compra->codigoControl != 0 && $compra->codigoControl != "")
                                                        <td>{{ $compra->codigoControl }}</td>
                                                        @else
                                                        <td></td>
                                                    @endif

                                                    {{-- botones --}}
                                                    <td style="text-align: center">
                                                        <form  action="{{route ('compras.destroy',$compra->id)}}" method="POST" class="frmEliminar-Compra">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="btn-group btn-group-xs">
                                                                <a role="button" class="btn btn-outline-info btnEditarCompra btn-xs"
                                                                data-toggle="modal" data-target="#modal-editar-compra" idCompra="{{$compra->id}}">
                                                                <i class="fas fa-pen"></i>
                                                                </a>
                                                                <button type="submit" class="btn btn-outline-danger btn-xs"><i class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </form>
                                                    </td>
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
                                                <th class="bg-white"></th>
                                                <th class="bg-white"></th>
                                                @if ($idSucursalBuscada == "-1")
                                                    <th></th>
                                                @endif
                                                <th colspan="6" class="text-center"><b>TOTALES</b></th>
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
                                                <th class="bg-white"></th>
                                                <th class="bg-white"></th>
                                                <th class="bg-white"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    {{-- ! Fin DataTable --}}
                @endisset
            </div>

            {{--! modal IMPORTAR compra--}}
            @if (isset($mesBuscado))
                <div class="modal fade" id="modal-importar-compras">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;"><b>Importar</b> registros de facturas de compras</h4>
                            </div>

                            <form action="{{route('importar-compras-excel')}}" method="post" enctype="multipart/form-data" class="frmImportar-Compras">
                                @csrf
                                <div class="modal-body">

                                    <div class="row">
                                        {{--* gestionBuscada PARA IMPORTAR  --}}
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Gestión</label>
                                                <select name="gestionBuscada" id="gestionBuscada" class="form-control" style="width: 100%;" required>
                                                    @php
                                                        $anio =  date("Y");
                                                        $anioReferencia = $anioMinimo;//año minimo
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
                                        </div> --}}

                                        {{--* mesBuscado --}}
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mes</label>
                                                <select name="mesBuscado" id="mesBuscado" class="form-control" style="width: 100%;" required>
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
                                        </div> --}}

                                        {{--* idSucursalBuscada --}}
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Sucursal</label>
                                                <select name="idSucursalBuscada" id="idSucursalBuscada" class="form-control" style="width: 100%;" required>
                                                    @if(isset($idSucursalBuscada))
                                                            <option value=""></option>
                                                        @foreach ($sucursalesDeLaEmpresa as $sucursal )
                                                            @if ($sucursal->id == $idSucursalBuscada)
                                                                <option value="{{$sucursal->id}}" selected >{{$sucursal->descripcion}}</option>
                                                            @else
                                                                <option value="{{$sucursal->id}}">{{$sucursal->descripcion}}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <option value=""></option>
                                                        {{-- mostramos sucursales - opciones predeterminadas --}}
                                                        @foreach ($sucursalesDeLaEmpresa as $sucursal)
                                                            <option value=""></option>
                                                            <option value="{{ $sucursal->id }}">{{ $sucursal->descripcion }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="archivo">Seleccione el archivo Excel que contiene los registros de compras</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" accept=".xlsx, .xls" id="archivo" name="archivo" class="custom-file-input" required>
                                                    <label class="custom-file-label" for="archivo"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="{{ asset('storage/plantillas')."/plantilla-para-importar-compras-SCW.xlsx"}}">
                                                Descargar Plantilla...
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info col-md-2">Importar</button>
                                    <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            {{--! Fin modal IMPORTAR compra--}}

            {{--! modal Crear compra--}}
            @if (isset($mesBuscado))
                <div class="modal fade" id="modal-crear-compra">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="POST" action="/compras" class="frmCrear-Compra" id="frmCrear-Compra" >
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-9">
                                            <h4 class="modal-title">Registro de compras</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            {{-- sucursal del formulario de agregar compra --}}
                                            <select name="sucursal_id" id="sucursal_id" class="form-control" style="width: 100%;" required>
                                                @if(isset($idSucursalBuscada))
                                                    @if ($idSucursalBuscada == '-1')
                                                        <option value=""></option>
                                                    @endif
                                                    @foreach ($sucursalesDeLaEmpresa as $sucursal )
                                                        @if ($sucursal->id == $idSucursalBuscada)
                                                            <option value="{{$sucursal->id}}" selected >{{$sucursal->descripcion}}</option>
                                                        @else
                                                            <option value="{{$sucursal->id}}">{{$sucursal->descripcion}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>

                                            {{-- año compra --}}
                                            <input type="hidden" value="{{$gestionBuscada}}" name="gestionCompra" id="gestionCompra">
                                            {{-- mes compra --}}
                                            <input type="hidden" value="{{$mesBuscado}}" name="mesCompra" id="mesCompra">
                                            {{-- empresa activa --}}
                                            <input type="hidden" value="{{Auth::user()->idEmpresaActiva}}" name="empresaActivaCompra" id="empresaActivaCompra">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="nitProveedor">NIT proveedor</label>
                                            <input name="nitProveedor" id="nitProveedor" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="NIT del proveedor de la factura de compra | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1)
                                            background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="razonSocialProveedor">Razon Social proveedor</label>
                                            {{-- style="text-transform: uppercase --}}
                                            <input name="razonSocialProveedor" id="razonSocialProveedor" type="text" maxlength="150" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Razón Social del proveedor de la factura de compra | texto | máximo 150 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top"  title="Casos especiales: Boleto aéreo = 1, DUI/DIM = 3 | se permite letras para casos especiales"
                                            for="codigoAutorizacion" style="cursor: pointer">
                                                Código de Autorización
                                                <i class="fas fa-info-circle"></i>
                                            </label>

                                            <input name="codigoAutorizacion" id="codigoAutorizacion" type="text" maxlength="100" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Código de Autorización | texto | máximo 100 carácteres (debido a las caracterísiticas del nuevo sistema de facuración)" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top" title="Para Boletos Aéreos = número de ticket electrónico (e-ticket), obviando los guiones o cualquier otro carácter especial. Cuando se registre una DUI/DIM = 0"
                                            for="numeroFactura" style="cursor: pointer">
                                                Número Factura
                                                <i class="fas fa-info-circle"></i>
                                            </label>

                                            <input name="numeroFactura" id="numeroFactura" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="Número Factura de la compra | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top" title="Número de la Declaración Única de Importación/Declaración de Mercaderias de Importación"
                                            for="dim" style="cursor: pointer">
                                                DUI/DIM
                                                <i class="fas fa-info-circle"></i>
                                            </label>
                                            <input name="dim" id="dim" type="text" maxlength="20" class="form-control form-control-sm" style="text-transform: uppercase" autocomplete="off" title="DUI o DIM de la póliza de importación | texto | máximo 20 carácteres">
                                        </div>
                                        <!-- fechas dd/mm/yyyy -->
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="form-label mb-0" for="fechaDia">Fecha</label>
                                            <div class="row">
                                                {{--! dia --}}
                                                <div class="col-sm-6">

                                                    <input type="text" maxlength="2" class="form-control form-control-sm" id="fechaDia" name="fechaDia" autocomplete="off" title="Día de la fecha de la factura | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>

                                                </div>
                                                {{--! mes y año de la compra --}}
                                                <div class="col-sm-6">
                                                    {{--* readonly-> es solo lectura- este se envia, desabled-> desactivado . no envia --}}
                                                    <input value="/{{$mesBuscado}}/{{$gestionBuscada}}" type="text" class="form-control form-control-sm" id="fechaMA" name="fechaMA" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #ffccd5; @endif" readonly>
                                                </div>
                                            </div>
                                            {{--* <input type="text" class="form-control form-control-sm" id="date-mask-input-a" name="fecha" required autocomplete="off"> --}}
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="importeTotal">Importe Total</label>
                                            <input maxlength="10" name="importeTotal" id="importeTotal" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Total de la facura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="ice">Importe ICE</label>
                                            <input maxlength="10" name="ice" id="ice" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto al Consumo Específico | numérico" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="iehd">Importe IEHD</label>
                                            <input maxlength="10" name="iehd" id="iehd" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto Especial a los Hidrocarburos y sus Derivados | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="ipj">Importe IPJ</label>
                                            <input maxlength="10" name="ipj" id="ipj" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto a la Participación en Juegos | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tasas">Tasas</label>
                                            <input maxlength="10" name="tasas" id="tasas" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Tasas incluidas en la factura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="otrosNoSujetosaCF">Otros no sujetos a CF</label>
                                            <input maxlength="10" name="otrosNoSujetosaCF" id="otrosNoSujetosaCF" type="text" step="0.01" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Conceptos que no generan Credito Fiscal | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                            {{-- step="any" --}}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="exentos">Importe exento</label>
                                            <input maxlength="10" name="exentos" id="exentos" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importes exentos | numérico | Libros, folletos, diarios, revistas y publicaciones" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tasaCero">Importe Compras a Tasa Cero</label>
                                            <input maxlength="10" name="tasaCero" id="tasaCero" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Compras gravadas a tasa cero | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="subtotal">Subtotal</label>
                                            <input name="subtotal" id="subtotal" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="descuentos">Descuentos</label>
                                            <input maxlength="10" name="descuentos" id="descuentos" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Descuentos que figuran en la factura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="gifCard">Importe GifCard</label>
                                            <input maxlength="10" name="gifCard" id="gifCard" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de compra con GifCard | numérico | Una Gift Card es una “tarjeta de regalo” pre-cargada que se utiliza como medio de pago para realizar compras en una determinada empresa" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="baseParaCF">Importe Base CF</label>
                                            <input name="baseParaCF" id="baseParaCF" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="creditoFiscal">Credito Fiscal</label>
                                            <input name="creditoFiscal" id="creditoFiscal" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tipoCompra">Tipo Compra</label>
                                            <select name="tipoCompra" id="tipoCompra" class="form-control form-control-sm" required>
                                                <option value="1">1 - Compras para mercado interno con destino a actividades gravadas</option>
                                                <option value="2">2 - Compras para mercado interno con destino a actividades no gravadas</option>
                                                <option value="3">3 - Compras sujetas a proporcionalidad</option>
                                                <option value="4">4 - Compras para exportaciones</option>
                                                <option value="5">5 - Compras tanto para el mercado interno como para exportaciones</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="codigoControl">Codigo de Control</label>
                                            <input name="codigoControl" id="codigoControl" type="text" maxlength="20" class="form-control form-control-sm text-uppercase" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" autocomplete="off">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for=""></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="checkboxCombustible" id="checkboxCombustible" class="custom-control-input cursor-pointer">
                                                <label class="custom-control-label" for="checkboxCombustible">Proveedor de combustible</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info col-md-2">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            @endif
            {{--! Fin modal Crear compra--}}

            {{--! modal Editar compra--}}
            @if (isset($mesBuscado))
                <div class="modal fade" id="modal-editar-compra">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="POST" action="jquery" class="frmEditar-Compra" id="frmEditar-Compra" >
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-9">
                                            <h4 class="modal-title">Edición de registro de compras</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            {{--? sucursal del formulario de editar compra --}}
                                            <select name="sucursal_id" id="sucursal_id" class="form-control " style="width: 100%;" required>
                                                @if(isset($idSucursalBuscada))
                                                    @if ($idSucursalBuscada == '-1')
                                                        <option value=""></option>
                                                    @endif
                                                    @foreach ($sucursalesDeLaEmpresa as $sucursal )
                                                        @if ($sucursal->id == $idSucursalBuscada)
                                                            <option value="{{$sucursal->id}}" selected >{{$sucursal->descripcion}}</option>
                                                        @else
                                                            <option value="{{$sucursal->id}}">{{$sucursal->descripcion}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>

                                            {{-- año compra --}}
                                            <input type="hidden" value="{{$gestionBuscada}}" name="gestionCompra" id="gestionCompra">
                                            {{-- mes compra --}}
                                            <input type="hidden" value="{{$mesBuscado}}" name="mesCompra" id="mesCompra">
                                            {{-- empresa activa --}}
                                            <input type="hidden" value="{{Auth::user()->idEmpresaActiva}}" name="empresaActivaCompra" id="empresaActivaCompra">
                                        </div>
                                    </div>

                                        <hr>

                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="nitProveedor_editar">NIT proveedor</label>
                                            <input name="nitProveedor_editar" id="nitProveedor_editar" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="NIT del proveedor de la factura de compra | numerico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="razonSocialProveedor_editar">Razon Social proveedor</label>
                                            <input  name="razonSocialProveedor_editar" id="razonSocialProveedor_editar" type="text" maxlength="150" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Razón Social del proveedor de la factura de compra | texto | máximo 150 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top"  title="Casos especiales: Boleto aéreo = 1, DUI/DIM = 3 | se permite letras para casos especiales"
                                            for="codigoAutorizacion_editar" style="cursor: pointer">
                                                Código de Autorización
                                                <i class="fas fa-info-circle"></i>
                                            </label>
                                            <input name="codigoAutorizacion_editar" id="codigoAutorizacion_editar" type="text" maxlength="100" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Código de Autorización | texto | máximo 100 carácteres (debido a las caracterísiticas del nuevo sistema de facuración)" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top" title="Para Boletos Aéreos = número de ticket electrónico (e-ticket), obviando los guiones o cualquier otro carácter especial. Cuando se registre una DUI/DIM = 0"
                                            for="numeroFactura_editar" style="cursor: pointer">
                                                Número Factura
                                                <i class="fas fa-info-circle"></i>
                                            </label>
                                            <input name="numeroFactura_editar" id="numeroFactura_editar" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="Número Factura de la compra | texto | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" data-toggle="tooltip" data-placement="top" title="Número de la Declaración Única de Importación/Declaración de Mercaderias de Importación"
                                            for="dim_editar" style="cursor: pointer">
                                                DUI/DIM
                                                <i class="fas fa-info-circle"></i>
                                            </label>
                                            <input name="dim_editar" id="dim_editar" type="text" maxlength="20" class="form-control form-control-sm" style="text-transform: uppercase" autocomplete="off" title="DUI o DIM de la póliza de importación | texto | máximo 20 carácteres">
                                        </div>
                                        <!-- fechas dd/mm/yyyy -->
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="form-label mb-0" for="fechaDia_editar">Fecha</label>
                                            <div class="row">
                                                {{--! dia --}}
                                                <div class="col-sm-6">

                                                    <input type="text" maxlength="2" class="form-control form-control-sm" id="fechaDia_editar" name="fechaDia_editar" autocomplete="off" title="Día de la fecha de la factura | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>

                                                </div>
                                                {{--! mes y año de la compra --}}
                                                <div class="col-sm-6">
                                                    {{--* readonly-> es solo lectura- este se envia, desabled-> desactivado . no envia --}}
                                                    <input value="/{{$mesBuscado}}/{{$gestionBuscada}}" type="text" class="form-control form-control-sm" id="fechaMA_editar" name="fechaMA_editar" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #ffccd5; @endif" readonly>
                                                </div>
                                            </div>
                                            {{--* <input type="text" class="form-control form-control-sm" id="date-mask-input-a" name="fecha" required autocomplete="off"> --}}
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="importeTotal_editar">Importe Total</label>
                                            <input maxlength="10" name="importeTotal_editar" id="importeTotal_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Total de la facura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="ice_editar">Importe ICE</label>
                                            <input maxlength="10" name="ice_editar" id="ice_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto al Consumo Específico | numérico" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="iehd_editar">Importe IEHD</label>
                                            <input maxlength="10" name="iehd_editar" id="iehd_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto Especial a los Hidrocarburos y sus derivados | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="ipj_editar">Importe IPJ</label>
                                            <input maxlength="10" name="ipj_editar" id="ipj_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto a la Participación en Juegos | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tasas_editar">Tasas</label>
                                            <input maxlength="10" name="tasas_editar" id="tasas_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Tasas incluidas en la factura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="otrosNoSujetosaCF_editar">Otros no sujetos a CF</label>
                                            <input maxlength="10" name="otrosNoSujetosaCF_editar" id="otrosNoSujetosaCF_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Conceptos que no generan Credito Fiscal | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="exentos_editar">Importe exento</label>
                                            <input maxlength="10" name="exentos_editar" id="exentos_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importes exentos | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tasaCero_editar">Importe Compras a Tasa Cero</label>
                                            <input maxlength="10" name="tasaCero_editar" id="tasaCero_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Compras gravadas a tasa cero | numérico" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="subtotal_editar">Subtotal</label>
                                            <input name="subtotal_editar" id="subtotal_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="descuentos_editar">Descuentos</label>
                                            <input maxlength="10" name="descuentos_editar" id="descuentos_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Descuentos que figuran en la factura de compra | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="gifCard_editar">Importe GifCard</label>
                                            <input maxlength="10" name="gifCard_editar" id="gifCard_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe GifCard | numérico | Una Gift Card es una “tarjeta de regalo” pre-cargada que se utiliza como medio de pago para realizar compras en una determinada empresa" required>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="baseParaCF_editar">Importe Base CF</label>
                                            <input name="baseParaCF_editar" id="baseParaCF_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="creditoFiscal_editar">Credito Fiscal</label>
                                            <input name="creditoFiscal_editar" id="creditoFiscal_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="tipoCompra_editar">Tipo Compra</label>
                                            <select name="tipoCompra_editar" id="tipoCompra_editar" class="form-control form-control-sm" required>
                                                <option value="1">1 - Compras para mercado interno con destino a actividades gravadas</option>
                                                <option value="2">2 - Compras para mercado interno con destino a actividades no gravadas</option>
                                                <option value="3">3 - Compras sujetas a proporcionalidad</option>
                                                <option value="4">4 - Compras para exportaciones</option>
                                                <option value="5">5 - Compras tanto para el mercado interno como para exportaciones</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="codigoControl_editar">Codigo de Control</label>
                                            <input name="codigoControl_editar" id="codigoControl_editar" type="text" maxlength="20" class="form-control form-control-sm text-uppercase" autocomplete="off" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                        </div>
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for=""></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="checkboxCombustible_editar" id="checkboxCombustible_editar" class="custom-control-input cursor-pointer">
                                                <label class="custom-control-label" for="checkboxCombustible_editar">Proveedor de combustible</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info col-md-2">Guardar Cambios</button>
                                    <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            @endif
            {{--! Fin modal Editar compra--}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuCompras').addClass('active');
    </script>

    {{--! calculos en los campos--}}
    <script src="{{ asset('/custom-code/modulos/compras/compras.js') }}"></script>
    <script src="{{ asset('/custom-code/modulos/compras/editar-compra.js') }}"></script>
    <script src="{{ asset('/custom-code/modulos/compras/compras-duplicadas.js') }}"></script>

    {{--! libreria numeral --}}
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{--! jquery UI para buscador con ajax--}}
    <script src="{{ asset('custom-code/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
    {{--     https://jqueryui.com/   https://jquery.com/ --}}

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! file --}}
    <script src="{{asset('/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

    {{--! este mensaje es recibido al CREAR NUEVA COMPRA --}}
    @if (Session('crear')=='ok')
        <script>
                toastr.success('Compra añadida exitosamente.')
        </script>
    @endif

    @if (Session('errorFecha')=='error')
        <script>
                toastr.error('Fecha inexistente.')
        </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR COMPRA --}}
    @if (Session('actualizar')=='ok')
        <script>
                toastr.success('Datos actualizados con éxito.')
        </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR COMPRA --}}
    @if (Session('eliminar')=='ok')
        <script>
                toastr.success('Compra eliminada exitosamente.')
        </script>
    @endif

    {{--! este mensaje es recibido al IMPORTAR COMPRAS DESDE EL EXCEL --}}
    @if (Session('importarExcel')=='ok')
    <script>
            toastr.success('Compras importadas exitosamente.')
    </script>
    @endif

    @error('archivo')
    <script>
        toastr.error('Verifique el archivo de compras seleccionado.')
    </script>
    @enderror

    {{--! Pregunta desea CREAR COMPRA--}}
    <script>
        $('.frmCrear-Compra').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea añadir Compra?',
            text: "¡Creará una nueva compra para el pediodo consultado!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Crear',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

    {{--! Pregunta desea EDITAR COMPRA--}}
    <script>
        $('.frmEditar-Compra').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea guardar cambios en el Registro de Compra?',
            text: "¡Actualizará el registro de compra!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Actualizar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

    {{--! Pregunta Eliminar COMPRA --}}
    <script>
        $('.frmEliminar-Compra').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Eliminar el Registro de Compra?',
            text: "¡No podrá recuperar datos!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

    {{--! Pregunta importar COMPRA --}}
    @if (Auth::user()->crear == 1)
    <script>
        $('.frmImportar-Compras').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Importar Compras?',
            text: "Importando compras desde un archivo excel externo",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Importar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>
    @else
    <script>
        $('.frmImportar-Compras').submit(function(e){
            e.preventDefault();

            Swal.fire({
            /*position: 'top-end', */
            title: 'No tiene permiso para la acción',
            text: "Comuníquese con el administrador",
            icon: 'error',
            showConfirmButton: false,
            timer: 2500
            })
        })
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
            $("#tablaCompras").DataTable({
                "responsive": false,
                "lengthChange": true,
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
                "scrollY": '400px',
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns":
                {
                    "left": 1,
                    "right": 1,
                }

            }).buttons().container().appendTo('#tablaCompras_wrapper .col-md-6:eq(0)');
        });
    </script>

    {{--! mascara fecha start ui--}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/custom-code/input-mask/input-mask-init.js') }}"></script>


    {{--! busqueda de nitProveedor --}}
    <script>
        /* llamamos al input  nitProveedor con el metodo autocomplete */
        // utiliza tambien jquery ui
        $('#nitProveedor').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.nitProveedor')}}",
                    datatype:'json',
                    data:{termino:request.term},// no olvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    }
                });
            },
            appendTo: "#modal-crear-compra",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta
            //al seleccionar de la lista
            select:function(event,ui)
            {
                /* alert("selecciono: " + ui.item.nit);
                utilizamos jquery para modificar imputs y checkbos*/
                $('#razonSocialProveedor').val(ui.item.razonSocialProveedor);
                $('#codigoAutorizacion').val(ui.item.ultimoCodigoAutorizacion);
                if(ui.item.combustible=='1')
                {
                    $('#checkboxCombustible').prop("checked", true);
                }
                else
                {
                    $('#checkboxCombustible').prop("checked", false);
                }
                $('#dim').val('0');

            },
            // request es un objeto definido al inicio de la funcion
            /*PETICION AJAX
            necesita una url que procese la informacion
            que tipo de datos espero recibir con: dataType
            datos que enviare desde el formulario con: data
            que se hara con los datos con: success */
        });
    </script>

    {{--! busqueda de cod de razonSocialProveedor --}}
    <script>
        $('#razonSocialProveedor').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.razonSocialProveedor')}}",
                    datatype:'json',
                    data:{termino:request.term}, // no olvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    },
                });
            },
            appendTo: "#modal-crear-compra",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta
        });
    </script>

    {{--! busqueda de cod de autorizacion --}}
    <script>
        $('#codigoAutorizacion').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.autorizacionCompra')}}",
                    datatype:'json',
                    data:{termino:request.term}, // no colvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    },
                });
            },
            appendTo: "#modal-crear-compra",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta
        });
    </script>

    {{-- formatos en el modal editar --}}
    <script>
        $(".btnEditarCompra").click(function() {
            $("#nitProveedor_editar").val("");
            $("#razonSocialProveedor_editar").val("");
            $("#codigoAutorizacion_editar").val("");
            $("#numeroFactura_editar").val("");
            $("#dim_editar").val("");
            $("#fechaDia_editar").val("");
            $("#importeTotal_editar").val(numeral(0).format('0.00'));
            $("#ice_editar").val(numeral(0).format('0.00'));
            $("#iehd_editar").val(numeral(0).format('0.00'));
            $("#ipj_editar").val(numeral(0).format('0.00'));
            $("#tasas_editar").val(numeral(0).format('0.00'));
            $("#otrosNoSujetosaCF_editar").val(numeral(0).format('0.00'));
            $("#exentos_editar").val(numeral(0).format('0.00'));
            $("#tasaCero_editar").val(numeral(0).format('0.00'));
            $("#subtotal_editar").val(numeral(0).format('0.00'));
            $("#descuentos_editar").val(numeral(0).format('0.00'));
            $("#gifCard_editar").val(numeral(0).format('0.00'));
            $("#baseParaCF_editar").val(numeral(0).format('0.00'));
            $("#creditoFiscal_editar").val(numeral(0).format('0.00'));

            $("#tipoCompra_editar").val("");
            $("#codigoControl_editar").val("");
            $("#checkboxCombustible_editar").prop("checked",false);
            //atrr para atributos personalizados y nativos
            //prop para atributos boolean
        });
    </script>

    {{--! ajax editar compra--}}
    <script>
        /* editar compra */
        /* CON JQUERY */

        $(".btnEditarCompra").click(function() {
            let idCompra = $(this).attr("idCompra");

            $("#frmEditar-Compra").prop("action","/compras/"+idCompra); //modificacmos action del formulario

            $.ajax({
                url: "{{ route('search.editarCompra') }}",
                type: "GET",
                datatype: 'json',
                data: { idCompra: idCompra },
                success: function(response) {
                    // console.log(response);
                    $("#nitProveedor_editar").val(response.nitProveedor);
                    $("#razonSocialProveedor_editar").val(response.razonSocialProveedor);
                    $("#codigoAutorizacion_editar").val(response.codigoAutorizacion);
                    $("#numeroFactura_editar").val(response.numeroFactura);
                    $("#dim_editar").val(response.dim);
                    let fecha = (response.fecha).split("-");//a-m-d
                    //console.log(fecha);//a-m-d
                    $("#fechaDia_editar").val(fecha[2]);//mes y año no es necesario por que lo hacemos con php

                    $("#importeTotal_editar").val(numeral(response.importeTotal).format('0.00'));
                    $("#ice_editar").val(numeral(response.ice).format('0.00'));
                    $("#iehd_editar").val(numeral(response.iehd).format('0.00'));
                    $("#ipj_editar").val(numeral(response.ipj).format('0.00'));
                    $("#tasas_editar").val(numeral(response.tasas).format('0.00'));
                    $("#otrosNoSujetosaCF_editar").val(numeral(response.otrosNoSujetosaCF).format('0.00'));
                    $("#exentos_editar").val(numeral(response.exentos).format('0.00'));
                    $("#tasaCero_editar").val(numeral(response.tasaCero).format('0.00'));
                    $("#subtotal_editar").val(numeral(response.subtotal).format('0.00'));
                    $("#descuentos_editar").val(numeral(response.descuentos).format('0.00'));
                    $("#gifCard_editar").val(numeral(response.gifCard).format('0.00'));
                    $("#baseParaCF_editar").val(numeral(response.baseParaCF).format('0.00'));
                    $("#creditoFiscal_editar").val(numeral(response.creditoFiscal).format('0.00'));

                    $("#tipoCompra_editar").val(response.tipoCompra);
                    $("#codigoControl_editar").val(response.codigoControl);
                    if(response.combustible==1)
                    {
                        $('#checkboxCombustible_editar').prop("checked", true);
                    }
                    else
                    {
                        $('#checkboxCombustible_editar').prop("checked", false);
                    }
                    /* llamamos una funcion de los archivos externos de js */
                    /* para mostrar los campos autocalculados despues de cargar por ajax */
                    calculo_SubtotalCompra_editar();
                }

            })
        });
    </script>

    {{-- ! eliminacion multiple --}}
    <script type="text/javascript">
        /* https://morioh.com/p/3afa70ca3665 */
        /* https://www.youtube.com/watch?v=P66cDBjeqzw */

        $(document).ready(function () {
            $('.borrarAll').on('click', function(e) {

                Swal.fire({
                    title: '¿Desea Eliminar los Registros seleccionados?',
                    text: "¡No podrá recuperar datos!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#11151c',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // CONFIRMADA LA PREGNTA eliminamos

                            var idsArray = []; //Variable tipo array
                            //selecciones todos los inputs tipo checkbox que tenga la clase delete_checkbox y que este seleccionado, es decir que tengan

                            //el atributo checked
                            $("input:checkbox[class=delete_checkbox]:checked").each(function () {
                                idsArray.push($(this).attr('data-id'));
                            });

                            // console.log(idsArray);//mostramos ids seleccionados

                            var unir_arrays_seleccionados = idsArray.join(",");
                            // console.log(unir_arrays_seleccionados);//mostramos ids seleccionados separados con coma


                            if(idsArray.length > 0){
                                $.ajax({

                                    url: $(this).attr('data-ruta-url'), /* $(this).data('url') -> esto en caso de tener un atributo con la ruta */
                                    type: 'DELETE',
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data: 'ids=' + unir_arrays_seleccionados,

                                    success: function (data) {
                                        mensajeExito(); //alerta

                                        if (data['respuesta_eliminados'] ) {

                                            $.each(idsArray,function(indice,id) {

                                                /* eliminarmos filas */
                                                //buscamos dentro del tr->el nombre id: 'fila?'
                                                var fila = $("tr#fila" + id).remove(); //Oculto las filas eliminadas
                                                // console.log('indice: ' + indice + ' - - ' + 'id:' + id); //filas eliminadas

                                                //recargamos a causa de la suma de totales
                                                location.reload();

                                                /* resaltamos duplicados */
                                                funcionDuplicados();
                                            });
                                            //alert(data['mensaje']);
                                        }else {
                                            // alert('Error, no se Eliminaron las compras ... ' + data['error']);
                                            alert("Error, recarga y vuelve a intentarlo");

                                        }

                                    },
                                    error: function (data) {
                                        //alert(data.responseText);
                                        alert("No se pudo eliminar las compras, recarga y vuelve a intentarlo");
                                    }

                                }); //fin ajax
                            } //fin if(idsArray)
                            else{
                                mensajeNoSeleccionado();
                            }

                        }
                })

            });
        });

        function mensajeExito(){
            toastr.success('Compras seleccionadas eliminadas exitosamente.');
        }
        function mensajeNoSeleccionado(){
            toastr.warning('Ninguna compra seleccionada.');
        }
    </script>

    {{--! SELECCION Y DESSELECCION --}}
    <script>
        $('document').ready(function () {
            /* seleccion */
            $('#btn-seleccionar-todos').click(function () {
                $('.delete_checkbox').prop("checked", true)
            });
            /* quitar seleccion */
            $('#btn-quitar-seleccion').click(function () {
                $('.delete_checkbox').prop("checked", false);
            });
        });
    </script>

    {{--! draggable de los modals --}}
    <script>
        $("#modal-importar-compras").draggable({
            handle: ".modal-header"
        });
    </script>


    <script>
        $("#fechaDia_editar").change(function (e) { 
            e.preventDefault();
            if($(this).val() > 31){
                $(this).val("");
            }
            if($(this).val() == 0){
                $(this).val("");
            }
        });

        $("#fechaDia").change(function (e) { 
            e.preventDefault();
            if($(this).val() > 31){
                $(this).val("");
            }
            if($(this).val() == 0){
                $(this).val("");
            }
        });
    </script>
@endsection
