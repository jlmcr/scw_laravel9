@extends('plantilla.adminlte')

@section('titulo')
Ventas
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
    <link rel="stylesheet" type="text/css" href="{{ asset('/custom-code/jquery-ui-1.13.2/jquery-ui.min.css') }}">
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
                            <a href="/ventas/?process=menu&idEmpresaActiva={{Auth::user()->idEmpresaActiva}}">
                            Registrar Ventas
                            </a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Ventas</li>
                            <li class="breadcrumb-item active">Registro de Ventas</li>
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
                <form method="GET" action="{{ route('ventas.index') }}">
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
                            <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-importar-ventas" name="btnImportarVenta" id="btnImportarVenta" >
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

                @isset($ventasEncontradas)
                    {{-- ! DataTable --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
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
                                        <div class="col-md-9 pt-0 pb-0">
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
                                                {{-- menu de eliminacion multiple--}}
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
                                                                    Seleccionar todos
                                                                </a>
                                                            </li>
                                                            <li class="ml-3 mr-2">
                                                                <a id="btn-quitar-seleccion" class="mt-3" style="cursor: pointer">
                                                                    <i class="far fa-square"></i>
                                                                    Des-seleccionar
                                                                </a>
                                                            </li>
                                                            <li class="ml-3 mr-2">
                                                                {{--! data-url="{{ rout('') }}"  como atributo podria poner la ruta o url de eliminacion, esto en caso de usar un archivo js externo ya que ahí no se acepta las llaves--}}
                                                                {{-- tambien se hizo la eliminacion con ajax para no tener que envolver todo en un form --}}
                                                                <a class="borrarAll mt-3 pr-8 text-red" data-ruta-url="{{ route('eliminar-multiples-ventas') }}" style="cursor: pointer">
                                                                    <i class="fas fa-trash-alt"></i> Eliminar seleccionados
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                {{-- boton agegar --}}
                                                <div class="col-9 ml-2">
                                                    <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-venta" name="btnAgregarVenta" id="btnAgregarVenta" >
                                                        <i class="fas fa-plus"></i> Agregar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- /. fin card-header -->

                                <div class="card-body">
                                    <table id="tablaVentas" data-page-length='10' class="table table-bordered table-striped display" style="width:100%">
                                        <thead>
                                            <tr style="font-size:10px">
                                                <th class="align-middle">Nº</th>
                                                <th class="align-middle">ELIMINAR</th>
                                                @if ($idSucursalBuscada == "-1")
                                                    <th class="align-middle">SUCURSAL</th>
                                                @endif
                                                <th class="align-middle">FECHA DE LA FACTURA</th>
                                                <th class="align-middle">NUMERO FACTURA</th>
                                                <th class="align-middle">CODIGO DE AUTORIZACION</th>
                                                <th class="align-middle">NIT/CI CLIENTE</th>
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
                                                <th class="align-middle">ACCIONES</th>
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

                                                    {{--! Eliminar --}}
                                                    <td>
                                                        <input type="checkbox" class="delete_checkbox" data-id="{{$venta->id}}">
                                                    </td>

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
                                                        $baseParaDF = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.','');

                                                        $baseParaDF_mostar = number_format(round($subtotal - $venta->descuentos - $venta->gifCard, 2),2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end">{{ $baseParaDF_mostar }}</td>

                                                    @php
                                                        $auxDf = number_format(round($baseParaDF * 0.13, 2),2,'.',',');
                                                    @endphp
                                                    <td style="text-align: end">{{ $auxDf }}</td>

                                                    <td style="text-align: center">{{ $venta->estado }}</td>

                                                    <td>{{ $venta->codigoControl }}</td>

                                                    <td style="text-align: center">{{ $venta->tipoVenta }}</td>


                                                    {{-- botones --}}
                                                    <td style="text-align: center">
                                                        <form  action="{{route ('ventas.destroy',$venta->id)}}" method="POST" class="frmEliminar-Venta">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="btn-group btn-group-xs">
                                                                <a role="button" class="btn btn-outline-info btnEditarVenta btn-xs"
                                                                data-toggle="modal" data-target="#modal-editar-venta" idVenta="{{$venta->id}}">
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
                                                <th class="bg-white"></th>
                                                <th></th>
                                                {{--! sucursal --}}
                                                @if ($idSucursalBuscada == "-1")
                                                    <th></th>
                                                @endif
                                                <th colspan="6" class="centro"><b>TOTALES</b></th>
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
                                                <th></th>
                                                <th></th>
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

        {{-- modales --}}

        {{--! modal IMPORTAR VENTA--}}
        @if (isset($mesBuscado))
            <div class="modal fade" id="modal-importar-ventas">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="cursor: move;">
                            <h4 class="modal-title" style="cursor: text;"><b>Importar</b> registros de facturas de ventas</h4>
                        </div>

                        <form action="{{route('importar-ventas-excel')}}" method="post" enctype="multipart/form-data" class="frmImportar-Ventas">
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
                                        <label for="archivo">Seleccione el archivo Excel que contiene los registros de ventas</label>
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
                                        <a href="{{ asset('storage/plantillas')."/plantilla-para-importar-ventas-SCW.xlsx"}}">
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
        {{--! Fin modal IMPORTAR VENTA--}}

        {{--! modal Crear VENTA--}}
        @if (isset($mesBuscado))
            <div class="modal fade" id="modal-crear-venta">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="/ventas" class="frmCrear-Venta" id="frmCrear-Venta" >
                            @csrf

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-9">
                                        <h4 class="modal-title">Registro de Ventas</h4>
                                    </div>
                                    <div class="form-group col-md-3">
                                        {{-- sucursal del formulario de agregar venta --}}
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

                                        {{-- año Venta --}}
                                        <input type="hidden" value="{{$gestionBuscada}}" name="gestionVenta" id="gestionVenta">
                                        {{-- mes Venta --}}
                                        <input type="hidden" value="{{$mesBuscado}}" name="mesVenta" id="mesVenta">
                                        {{-- empresa activa --}}
                                        <input type="hidden" value="{{Auth::user()->idEmpresaActiva}}" name="empresaActivaVenta" id="empresaActivaVenta">
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- fechas dd/mm/yyyy -->
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="form-label mb-0" for="fechaDia">Fecha</label>
                                        <div class="row">

                                            {{--? dia --}}
                                            <div class="col-sm-6">

                                                <input type="text" class="form-control form-control-sm" id="fechaDia" name="fechaDia" maxlength="2" autocomplete="off" title="Día de la fecha de la factura | numérico | 2 dígitos" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>

                                            </div>

                                            {{--? mes y año de la venta --}}
                                            <div class="col-sm-6">
                                                {{--* readonly-> es solo lectura- este se envia, desabled-> desactivado . no envia --}}
                                                <input value="/{{$mesBuscado}}/{{$gestionBuscada}}" type="text" class="form-control form-control-sm" id="fechaMA" name="fechaMA" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #ffccd5; @endif" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="numeroFactura">Número Factura</label>

                                        <input name="numeroFactura" id="numeroFactura" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="Número de la factura | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    @if (isset($ultimoCodigoAutorizacion[0]->codigoAutorizacion))
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="codigoAutorizacion">Código de Autorización</label>

                                            <input name="codigoAutorizacion" id="codigoAutorizacion" type="text" maxlength="100" class="form-control form-control-sm text-uppercase" autocomplete="off"
                                            value="{{$ultimoCodigoAutorizacion[0]->codigoAutorizacion}}" title="Código de Autorización de  la factura | numérico | máximo 100 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    @else
                                        <div class="form-group col-lg-3 mb-1">
                                            <label class="mb-0" for="codigoAutorizacion">Codigo de Autorización</label>
                                            <input name="codigoAutorizacion" id="codigoAutorizacion" type="text" maxlength="100" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Código de Autorización de  la factura | numérico | máximo 100 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                        </div>
                                    @endif
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="ciNitCliente">NIT/CI cliente</label>
                                        <input name="ciNitCliente" id="ciNitCliente" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="NIT o CI del cliente | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="complemento">Complemento</label>
                                        <input name="complemento" id="complemento" type="text" maxlength="3" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Complemento del CI del cliente | texto | máximo 3 carácteres">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="razonSocialCliente">Nombre/Razón Social Cliente</label>
                                        <input name="razonSocialCliente" id="razonSocialCliente" type="text" maxlength="150" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Nombre/Razón Social Cliente | texto | máximo 150 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="importeTotal">Importe Total</label>
                                        <input maxlength="10" name="importeTotal" id="importeTotal" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Total de la factura | númerico | con punto como separador decimal" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif"  required>
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
                                        <input maxlength="10" name="tasas" id="tasas" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Tasas incluidas en la factura de venta | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="otrosNoSujetosaIva">Otros no sujetos al IVA</label>
                                        <input maxlength="10" name="otrosNoSujetosaIva" id="otrosNoSujetosaIva" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Conceptos que no sujetas al Impuestos al Valor Agregado | numérico" required>
                                        {{-- step="any" --}}
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="exportacionesyExentos">Importe Exportaciones y Exentos</label>
                                        <input maxlength="10" name="exportacionesyExentos" id="exportacionesyExentos" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de Exportaciones y Excentos | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="tasaCero">Importe Ventas a Tasa Cero</label>
                                        <input maxlength="10" name="tasaCero" id="tasaCero" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de Ventas a Tasa Cero | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="subtotal">Subtotal</label>
                                        <input name="subtotal" id="subtotal" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="descuentos">Descuentos</label>
                                        <input maxlength="10" name="descuentos" id="descuentos" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Descuentos que figuran en la factura de venta | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="gifCard">Importe GifCard</label>
                                        <input maxlength="10" name="gifCard" id="gifCard" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de venta con GifCard | numérico | Un sistema de gift cards te garantiza dinero por adelantado para cualquier producto o servicio. En algún momento tendrás que entregar ese monto en productos o servicios, pero esto podría suceder dentro de una semana, un mes o un año. En algunos casos, hasta puede que no tengas que entregar nada" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="baseParaDF">Importe Base DF</label>
                                        <input name="baseParaDF" id="baseParaDF" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="debitoFiscal">Débito Fiscal</label>
                                        <input name="debitoFiscal" id="debitoFiscal" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="estado">Estado Factura</label>

                                        <select name="estado" id="estado" class="form-control form-control-sm" required>
                                            <option value="A">A - ANULADA</option>
                                            <option value="V" selected>V - VALIDA</option>
                                            <option value="C">C - EMITIDA EN CONTINGENCIA</option>
                                            <option value="L">L - LIBRE CONSIGNACION</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="codigoControl">Codigo de Control</label>
                                        <input name="codigoControl" id="codigoControl" type="text" maxlength="20" class="form-control form-control-sm text-uppercase" autocomplete="off">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="tipoVenta">Tipo de Venta</label>
                                        <select name="tipoVenta" id="tipoVenta" class="form-control form-control-sm" required>
                                            <option value="0">0 - OTROS</option>
                                            <option value="1">1 - GIFT CARD (Venta de Gift Card)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
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
        {{--! Fin modal Crear VENTA--}}

        {{--! modal editar VENTA--}}
        @if (isset($mesBuscado))
            <div class="modal fade" id="modal-editar-venta">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="modificado" class="frmEditar-Venta" id="frmEditar-Venta" >
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-9">
                                        <h4 class="modal-title">Edición de Registro de Ventas</h4>
                                    </div>
                                    <div class="form-group col-md-3">
                                        {{-- sucursal del formulario de agregar venta --}}
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

                                        {{-- año Venta --}}
                                        <input type="hidden" value="{{$gestionBuscada}}" name="gestionVenta" id="gestionVenta">
                                        {{-- mes Venta --}}
                                        <input type="hidden" value="{{$mesBuscado}}" name="mesVenta" id="mesVenta">
                                        {{-- empresa activa --}}
                                        <input type="hidden" value="{{Auth::user()->idEmpresaActiva}}" name="empresaActivaVenta" id="empresaActivaVenta">
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- fechas dd/mm/yyyy -->
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="form-label mb-0" for="fechaDia_editar">Fecha</label>
                                        <div class="row">

                                            {{--? dia --}}
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="fechaDia_editar" name="fechaDia_editar" maxlength="2" autocomplete="off" title="Día de la fecha de la factura | numérico | 2 dígitos" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                            </div>

                                            {{--? mes y año de la venta --}}
                                            <div class="col-sm-6">
                                                {{--* readonly-> es solo lectura- este se envia, desabled-> desactivado . no envia --}}
                                                <input value="/{{$mesBuscado}}/{{$gestionBuscada}}" type="text" class="form-control form-control-sm" id="fechaMA_editar" name="fechaMA_editar" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #ffccd5; @endif" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="numeroFactura_editar">Número Factura</label>
                                        <input name="numeroFactura_editar" id="numeroFactura_editar" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="Número de la factura | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="codigoAutorizacion_editar">Código de Autorización</label>

                                        <input name="codigoAutorizacion_editar" id="codigoAutorizacion_editar" type="text" maxlength="100" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Código de Autorización de  la factura | numérico | máximo 100 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="ciNitCliente_editar">NIT/CI cliente</label>
                                        <input name="ciNitCliente_editar" id="ciNitCliente_editar" type="text" maxlength="15" class="form-control form-control-sm" autocomplete="off" title="NIT o CI del cliente | numérico | máximo 15 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="complemento_editar">Complemento</label>
                                        <input name="complemento_editar" id="complemento_editar" type="text" maxlength="3" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Complemento del CI del cliente | texto | máximo 3 carácteres">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="razonSocialCliente_editar">Nombre/Razón Social Cliente</label>

                                        <input name="razonSocialCliente_editar" id="razonSocialCliente_editar" type="text" maxlength="150" class="form-control form-control-sm text-uppercase" autocomplete="off" title="Nombre/Razón Social Cliente | texto | máximo 150 carácteres" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="importeTotal_editar">Importe Total</label>
                                        <input maxlength="10" name="importeTotal_editar" id="importeTotal_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Total de factura | númerico | con punto como separador decimal" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="ice_editar">Importe ICE</label>
                                        <input maxlength="10" name="ice_editar" id="ice_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto al Consumo Específico | numérico" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="iehd_editar">Importe IEHD</label>
                                        <input maxlength="10" name="iehd_editar" id="iehd_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto Especial a los Hidrocarburos y sus Derivados | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="ipj_editar">Importe IPJ</label>
                                        <input maxlength="10" name="ipj_editar" id="ipj_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Impuesto a la Participación en Juegos | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="tasas_editar">Tasas</label>
                                        <input maxlength="10" name="tasas_editar" id="tasas_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Tasas incluidas en la factura de venta | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="otrosNoSujetosaIva_editar">Otros no sujetos al IVA</label>
                                        <input maxlength="10" name="otrosNoSujetosaIva_editar" id="otrosNoSujetosaIva_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Conceptos que no sujetas al Impuestos al Valor Agregado | numérico" required>
                                        {{-- step="any" --}}
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="exportacionesyExentos_editar">Importe Exportaciones y Exentos</label>
                                        <input maxlength="10" name="exportacionesyExentos_editar" id="exportacionesyExentos_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de Exportaciones y Excentos | numérico"  required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="tasaCero_editar">Importe Ventas a Tasa Cero</label>
                                        <input maxlength="10" name="tasaCero_editar" id="tasaCero_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de Ventas a Tasa Cero | numérico" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="subtotal_editar">Subtotal</label>
                                        <input name="subtotal_editar" id="subtotal_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="descuentos_editar">Descuentos</label>
                                        <input maxlength="10" name="descuentos_editar" id="descuentos_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Descuentos que figuran en la factura de venta | numérico" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: rgba(94, 255, 121, 0.301) @endif" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="gifCard_editar">Importe GifCard</label>
                                        <input maxlength="10" name="gifCard_editar" id="gifCard_editar" type="text" class="form-control form-control-sm" value="0.00" autocomplete="off" title="Importe de venta con GifCard | numérico | Un sistema de gift cards te garantiza dinero por adelantado para cualquier producto o servicio. En algún momento tendrás que entregar ese monto en productos o servicios, pero esto podría suceder dentro de una semana, un mes o un año. En algunos casos, hasta puede que no tengas que entregar nada" required>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="baseParaDF_editar">Importe Base DF</label>
                                        <input name="baseParaDF_editar" id="baseParaDF_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="debitoFiscal_editar">Débito Fiscal</label>
                                        <input name="debitoFiscal_editar" id="debitoFiscal_editar" type="text" class="form-control form-control-sm" value="0.00" style="@if (Auth::user()->resaltar_inputs_rcv == 1) background-color: #edede9 @endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="estado_editar">Estado Factura</label>

                                        <select name="estado_editar" id="estado_editar" class="form-control form-control-sm" required>
                                            <option value="A">A - ANULADA</option>
                                            <option value="V" selected>V - VALIDA</option>
                                            <option value="C">C - EMITIDA EN CONTINGENCIA</option>
                                            <option value="L">L - LIBRE CONSIGNACION</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="codigoControl_editar">Codigo de Control</label>
                                        <input name="codigoControl_editar" id="codigoControl_editar" type="text" maxlength="20" class="form-control form-control-sm text-uppercase" autocomplete="off">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                        <label class="mb-0" for="tipoVenta_editar">Tipo de Venta</label>
                                        <select name="tipoVenta_editar" id="tipoVenta_editar" class="form-control form-control-sm" required>
                                            <option value="0">0 - OTROS</option>
                                            <option value="1">1 - GIFT CARD (Venta de Gift Card)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                    </div>
                                    <div class="form-group col-lg-3 mb-1">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-info col-md-2">Actualizar</button>
                                <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        @endif
        {{--! Fin modal ditar VENTA--}}

        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuVentas').addClass('active');
    </script>

    {{--! calculos en los campos--}}
    <script src="{{ asset('/custom-code/modulos/ventas/ventas.js') }}"></script>
    <script src="{{ asset('/custom-code/modulos/ventas/editar-venta.js') }}"></script>
    <script src="{{ asset('/custom-code/modulos/ventas/ventas-duplicadas.js') }}"></script>

    {{--! libreria numeral --}}
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{--! jquery UI para buscador con ajax--}}
    <script src="{{ asset('custom-code/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>

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

    {{--! este mensaje es recibido al CREAR NUEVA Venta --}}
    @if (Session('crear')=='ok')
        <script>
                toastr.success('Venta añadida exitosamente.')
        </script>
    @endif

    @if (Session('errorFecha')=='error')
        <script>
                toastr.error('Fecha inexistente.')
        </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR Venta --}}
    @if (Session('actualizar')=='ok')
        <script>
                toastr.success('Datos actualizados con éxito.')
        </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR Venta --}}
    @if (Session('eliminar')=='ok')
        <script>
                toastr.success('Venta eliminada exitosamente.')
        </script>
    @endif

    {{--! este mensaje es recibido al IMPORTAR VentaS DESDE EL EXCEL --}}
    @if (Session('importarExcel')=='ok')
    <script>
            toastr.success('Ventas importadas exitosamente.')
    </script>
    @endif

    @error('archivo')
    <script>
        toastr.error('Verifique el archivo de Ventas seleccionado.')
    </script>
    @enderror

    {{--! Pregunta desea CREAR Venta--}}
    <script>
        $('.frmCrear-Venta').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea añadir Venta?',
            text: "¡Creará una nueva Venta para el pediodo consultado!",
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

    {{--! Pregunta desea EDITAR Venta--}}
    <script>
        $('.frmEditar-Venta').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea guardar cambios en el Registro de Venta?',
            text: "¡Actualizará el registro de Venta!",
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

    {{--! Pregunta Eliminar Venta --}}
    <script>
        $('.frmEliminar-Venta').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Eliminar el Registro de Venta?',
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

    {{--! Pregunta impotar Venta --}}
    @if (Auth::user()->crear == 1)
    <script>
        $('.frmImportar-Ventas').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Importar Ventas?',
            text: "Importando Ventas desde un archivo excel externo",
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
        $('.frmImportar-Ventas').submit(function(e){
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
            $("#tablaVentas").DataTable({
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

            }).buttons().container().appendTo('#tablaVentas_wrapper .col-md-6:eq(0)');
        });
    </script>

    {{--! mascara fecha start ui--}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/custom-code/input-mask/input-mask-init.js') }}"></script>


    {{--! busqueda de ciNitCliente --}}
    <script>
        // utiliza tambien jquery ui
        $('#ciNitCliente').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.ciNitCliente')}}",
                    datatype:'json',
                    data:{termino:request.term},// no olvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    }
                });
            },
            appendTo: "#modal-crear-venta",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta

            //****al seleccionar de la lista*****
            select:function(event,ui)
            {
                $('#razonSocialCliente').val(ui.item.razonSocialCliente);
            },
            // request es un objeto definido al inicio de la funcion
            /*PETICION AJAX
            necesita una url que procese la informacion
            que tipo de datos espero recibir con: dataType
            datos que enviare desde el formulario con: data
            que se hara con los datos con: success */
        });
    </script>

    {{--! busqueda de cod de razonSocialCliente --}}
    <script>
        $('#razonSocialCliente').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.razonSocialCliente')}}",
                    datatype:'json',
                    data:{termino:request.term}, // no olvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    },
                });
            },
            appendTo: "#modal-crear-venta",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta
        });
    </script>

    {{--! busqueda de cod de autorizacion --}}
    <script>
        $('#codigoAutorizacion').autocomplete({
            source: function(request,response){
                $.ajax({
                    url:"{{route('search.autorizacionVenta')}}",
                    datatype:'json',
                    data:{termino:request.term}, // no colvides que es con dos puntos, y siempre será .term
                    success:function(data){
                        response(data);
                    },
                });
            },
            appendTo: "#modal-crear-venta",    // agregamos los labels a modal - solo para modals
            minLength:2,    //cada cuantos caracteres se ejecuta
        });
    </script>

    {{-- formatos en el modal editar --}}
    <script>
        $(".btnEditarVenta").click(function() {
            $("#fechaDia_editar").val("");
            $("#numeroFactura_editar").val("");
            $("#codigoAutorizacion_editar").val("");
            $("#ciNitCliente_editar").val("");
            $("#complemento_editar").val("");
            $("#razonSocialCliente_editar").val("");
            $("#importeTotal_editar").val(numeral(0).format('0.00'));
            $("#ice_editar").val(numeral(0).format('0.00'));
            $("#iehd_editar").val(numeral(0).format('0.00'));
            $("#ipj_editar").val(numeral(0).format('0.00'));
            $("#tasas_editar").val(numeral(0).format('0.00'));
            $("#otrosNoSujetosaIva_editar").val(numeral(0).format('0.00'));
            $("#exportacionesyExentos_editar").val(numeral(0).format('0.00'));

            $("#tasaCero_editar").val(numeral(0).format('0.00'));
            $("#subtotal_editar").val(numeral(0).format('0.00'));
            $("#descuentos_editar").val(numeral(0).format('0.00'));
            $("#gifCard_editar").val(numeral(0).format('0.00'));

            $("#baseParaDF_editar").val(numeral(0).format('0.00'));
            $("#debitoFiscal_editar").val(numeral(0).format('0.00'));

            $("#estado_editar").val("");
            $("#codigoControl_editar").val("");
            $("#tipoVenta_editar").val("");
            //atrr para atributos personalizados y nativos
            //prop para atributos boolean
        });
    </script>

    {{--! ajax editar venta--}}
    <script>
        /* editar venta */
        /* CON JQUERY */

        $(".btnEditarVenta").click(function() {
            let idVenta = $(this).attr("idVenta");
            $("#frmEditar-Venta").prop("action","/ventas/"+idVenta); //modificacmos action del formulario

            $.ajax({
                url: "{{ route('search.editarVenta') }}",
                type: "GET",
                datatype: 'json',
                data: { idVenta: idVenta },
                success: function(response) {
                    // console.log(response);
                    let fecha = (response.fecha).split("-");//a-m-d
                    //console.log(fecha);//a-m-d
                    $("#fechaDia_editar").val(fecha[2]);//mes y año no es necesario por que lo hacemos con php
                    $("#numeroFactura_editar").val(response.numeroFactura);
                    $("#codigoAutorizacion_editar").val(response.codigoAutorizacion);
                    $("#ciNitCliente_editar").val(response.ciNitCliente);
                    $("#complemento_editar").val(response.complemento);
                    $("#razonSocialCliente_editar").val(response.razonSocialCliente);

                    $("#importeTotal_editar").val(numeral(response.importeTotal).format('0.00'));
                    $("#ice_editar").val(numeral(response.ice).format('0.00'));
                    $("#iehd_editar").val(numeral(response.iehd).format('0.00'));
                    $("#ipj_editar").val(numeral(response.ipj).format('0.00'));
                    $("#tasas_editar").val(numeral(response.tasas).format('0.00'));
                    $("#otrosNoSujetosaIva_editar").val(numeral(response.otrosNoSujetosaIva).format('0.00'));
                    $("#exportacionesyExentos_editar").val(numeral(response.exportacionesyExentos).format('0.00'));

                    $("#tasaCero_editar").val(numeral(response.tasaCero).format('0.00'));
                    $("#subtotal_editar").val(numeral(response.subtotal).format('0.00'));
                    $("#descuentos_editar").val(numeral(response.descuentos).format('0.00'));
                    $("#gifCard_editar").val(numeral(response.gifCard).format('0.00'));
                    $("#baseParaDF_editar").val(numeral(response.baseParaDF).format('0.00'));
                    $("#debitoFiscal_editar").val(numeral(response.debitoFiscal).format('0.00'));

                    $("#estado_editar").val(response.estado);
                    $("#codigoControl_editar").val(response.codigoControl);
                    $("#tipoVenta_editar").val(response.tipoVenta);

                    /* llamamos una funcion de los archivos externos de js */
                    /* para mostrar los campos autocalculados despues de cargar por ajax */
                    calculo_SubtotalVenta_editar();
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
                                                var fila = $("tr#fila" + id).remove(); //Oculto las filas eliminadas
                                                // console.log('indice: ' + indice + ' - - ' + 'id:' + id); //filas eliminadas

                                                //recargamos a causa de la suma de totales
                                                location.reload();

                                                /* resaltamos duplicados */
                                                funcionDuplicados();
                                            });
                                            //alert(data['mensaje']);
                                        }else {
                                            // alert('Error, no se Eliminaron las ventas ... ' + data['error']);
                                            alert("Error, recarga y vuelve a intentarlo");
                                        }

                                    },
                                    error: function (data) {
                                        //alert(data.responseText);
                                        alert("No se pudo eliminar las ventas, recarga y vuelve a intentarlo");
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
            toastr.success('Ventas seleccionadas eliminadas exitosamente.');
        }
        function mensajeNoSeleccionado(){
            toastr.warning('Ninguna venta seleccionada.');
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
        $("#modal-importar-ventas").draggable({
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
