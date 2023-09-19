@extends('plantilla.adminlte')

@section('titulo')
    Historial de Depreciación
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <a href="{{ route('historial-depreciaciones') }}">Historial de Depreciaciones - Activo Fijo</a>
                            </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item"><a href="/activoFijo">Activo Fijo</a></li>
                            <li class="breadcrumb-item active">Historial</li>
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
                <form method="GET" action="{{ route('historial-depreciaciones') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{--* select rubros --}}
                                <label>Categoría/Rubro:</label>
                                <select name="id_rubro_buscado" id="id_rubro_buscado" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach ($rubros as $rubro)
                                        @if ($rubro_buscado != "" and $rubroSeleccionado != '-1')
                                            @if ($rubro->id == $rubro_buscado->id)
                                                <option value="{{ $rubro->id }}" selected>
                                                    {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                                </option>
                                            @else
                                                <option value="{{ $rubro->id }}">
                                                    {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $rubro->id }}">
                                                {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                {{--* select rubros --}}
                            </div>
                        </div>
                        {{-- * Botones busqueda --}}
                        <div class="col-md-3">
                            <label></label>
                            {{-- btn-outline-primary --}}
                            <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                                Buscar
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label></label>
                            @if ($rubroSeleccionado != "")
                            <a href="{{route('nueva-depreciacion.create')}}" target="_blank" class="btn btn-block btn-outline-success mt-2">
                                <i class="fas fa-plus"></i>
                                Nueva Depreciación
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
                {{--* Fin Buscador --}}
                <br>
                {{-- ! DataTable de ACTIVO FIJO--}}
                @if ($rubroSeleccionado != "")
                    @if (isset($activosFijosEncontrados))
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Historial de depreciaciones</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- ./card-header -->
                                    <div class="card-body table-responsive p-2">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="align-middle text-center">Item</th>
                                                    <th class="align-middle text-center">Descripción</th>
                                                    <th class="align-middle text-center">Cantidad</th>
                                                    <th class="align-middle text-center">Medida</th>
                                                    <th class="align-middle text-center">Situación de Ingreso</th>
                                                    <th class="align-middle text-center">Estado Actual</th>
                                                    <th class="align-middle text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($activosFijosEncontrados as $activo)
                                                    <tr data-widget="expandable-table" aria-expanded="false">
                                                        <td>{{ $activo->id }}</td>
                                                        <td>{{ $activo->activoFijo }}</td>
                                                        <td style="text-align: center">
                                                            <span class="badge bg-cyan p-2">
                                                                {{$activo->cantidad}}
                                                            </span>
                                                        </td>
                                                        <td>{{ $activo->medida }}</td>
                                                        <td>{{ $activo->situacion}}</td>
                                                        <td style="text-align: center">
                                                            @if ($activo->estadoAF == "ALTA")
                                                            <span class="badge bg-cyan p-2">
                                                                {{$activo->estadoAF}}
                                                            </span>
                                                            @else
                                                            <span class="badge bg-lightblue p-2">
                                                                {{$activo->estadoAF}}
                                                            </span>
                                                            @endif
                                                        </td>
                                                        {{-- botones --}}
                                                        <td style="text-align: center">
                                                            <a href="{{route('pdf-historial-activo-fijo',['id_rubro_buscado'=>$rubroSeleccionado,'id_activo_seleccionado'=>$activo->id])}}" target="_blank" class="btn btn-outline-info btn-sm">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    {{--! historial de depresiaciones --}}
                                                    <tr class="expandable-body">
                                                        <td colspan="7">
                                                            <p class="p-0 pt-1">
                                                                <table class="table-striped table-bordered" style="width: 100%">
                                                                    <thead class="text-red">
                                                                        <th class="align-middle text-center">Ejercicio</th>
                                                                        <th class="align-middle text-center">Reexpresión</th>
                                                                        <th class="align-middle text-center">Valor Inicial del Bien</th>
                                                                        <th class="align-middle text-center">Dep. Acum. Inicial</th>
                                                                        <th class="align-middle text-center">Meses de depreciación</th>
                                                                        <th class="align-middle text-center">Valor Final del Bien</th>
                                                                        <th class="align-middle text-center">Dep. Acum. Final</th>
                                                                        <th class="align-middle text-center">Valor Neto del Bien</th>
                                                                    </thead>
                                                                    @foreach ($depreciaciones as $depreciacion )
                                                                        @if ($activo->id == $depreciacion->activoFijo_id)
                                                                        <tr>
                                                                            <td>{{$depreciacion->ejercicioFiscal}}</td>
                                                                            @if ($depreciacion->reexpresar == 1)
                                                                                <td>Reexpresado</td>
                                                                                @else
                                                                                <td>No Reexpresado</td>
                                                                            @endif
                                                                            <td class="text-right">
                                                                                {{number_format($depreciacion->valorInicial_depr,2,'.',',')}}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{number_format($depreciacion->depAcumInicial_depr,2,'.',',')}}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{number_format($depreciacion->meses,2,'.',',')}}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{number_format($depreciacion->valorFinal_depr,2,'.',',')}}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{number_format($depreciacion->depAcumFinal_depr,2,'.',',')}}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{number_format($depreciacion->valorFinal_depr-$depreciacion->depAcumFinal_depr,2,'.',',')}}
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </table>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {{-- ! Fin DataTable de ACTIVO FIJO--}}

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="https://www.impuestos.gob.bo/pdf/D.S.%2024051%20de%2029-06-1995%20-%20Reglamento%20del%20Impuesto%20sobre%20las%20Utilidades%20de%20las%20Empresas%20(IUE)%20(1).pdf" target="_blank">
                                            Ver Decreto Supremo 24051 de 29-06-1995
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ! Fin Contenido --}}
    </div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuHistorialDeprec').addClass('active');
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    @if (Session('guardadas_depreciaciones')=='error')
    <script>
        toastr.error('Ocurrió un error, vuelva a intentarlo.')
    </script>
    @endif
@endsection
