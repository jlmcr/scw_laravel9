@extends('plantilla.adminlte')

@section('titulo')
    Plan de Cuentas
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
                            <a href="{{route('plan-de-cuentas')}}">Plan de Cuentas</a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Plan de Cuentas</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card card-dark card-outline">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6 align-middle">
                                        <h4>Plan de Cuentas del Sistema</h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{{route('pdf-plan-de-cuentas')}}" target="_blank" class="btn btn-outline-info float-right m-2">
                                            <i class="fas fa-print"></i>Imprimir
                                        </a>
                                        {{-- <button type="button" class="btn btn-outline-success float-right m-2 pl-3 pr-4">
                                            <i class="fas fa-file-excel"></i>
                                            ExCel
                                        </button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0 table-responsive">
                                {{-- tabla nivel 1 --}}
                                <table class="table table-hover">
                                    <thead>
                                        <th>
                                            <div class="row text-sm">
                                                <div class="col-lg-1 col-md-1 col-sm-1">
                                                    CÓDIGO
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 text-center">
                                                    CUENTA
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 text-center">
                                                    CORRELATVO
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                    NIVEL
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1 text-center">

                                                </div>
                                            </div>
                                        </th>
                                    </thead>
                                    <tbody>

                                        @foreach ($tipos as $tipo )
                                            <tr data-widget="expandable-table" aria-expanded="false">
                                                <td>
                                                    <div class="row text-red">
                                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                                            <i class="expandable-table-caret fas fa-caret-right fa-fw text-red"></i>
                                                            {{$tipo->id}} {{-- NIVEL 1 codigo--}}
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                                            {{$tipo->descripcion}} {{-- NIVEL 1 nombre--}}
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-1 text-red">
                                                            {{$tipo->id}} {{-- NIVEL 1 correlativo--}}
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                            1 {{-- NIVEL 1 nivel--}}
                                                        </div>

                                                        {{--! opciones desplegables en tipo--}}
                                                        <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-light btn-sm dropdown-toggle text-red" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <form action="">
                                                                        {{-- btnEditarTipo utilizado para mostrar datos en el modal al editar --}}
                                                                        <a class="dropdown-item btnEditarTipo"  role="button"
                                                                        data-toggle="modal" data-target="#modal-editar-tipo" idCodigoTipo="{{$tipo->id}}" descripcionTipo="{{$tipo->descripcion}}">
                                                                            <i class="fas fa-pen text-sm"></i> Editar Tipo
                                                                        </a>

                                                                        <a class="dropdown-item disabled" href="#">
                                                                            <i class="fas fa-times text-sm"></i> Eliminar Tipo
                                                                        </a>

                                                                        <a class="dropdown-item btnCrearGrupo"  role="button"
                                                                        data-toggle="modal" data-target="#modal-crear-grupo" idCodigoTipo="{{$tipo->id}}" descripcionTipo="{{$tipo->descripcion}}">
                                                                            <i class="fas fa-plus text-sm"></i> Agregar Grupo
                                                                        </a>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="expandable-body">
                                                <td>
                                                <div class="p-0">
                                                    {{-- tabla nivel 2 --}}
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            @foreach ( $grupos as $grupo )
                                                            @if ($grupo->tipo_id == $tipo->id)
                                                                <tr data-widget="expandable-table" aria-expanded="false">
                                                                    <td>
                                                                        <div class="row text-blue">
                                                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                                                <i class="expandable-table-caret fas fa-caret-right fa-fw text-blue"></i>
                                                                                {{$grupo->id}} {{-- NIVEL 2 codigo--}}
                                                                            </div>
                                                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                                                {{$grupo->descripcion}} {{-- NIVEL 2 nombre--}}
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-blue">
                                                                                {{$grupo->correlativo}} {{-- NIVEL 2 correlativo--}}
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                {{$grupo->nivel}} {{-- NIVEL 2 nivel--}}
                                                                            </div>

                                                                            {{--! opciones desplegables en grupo --}}
                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-light btn-sm dropdown-toggle text-blue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <form action="">
                                                                                            {{-- btnEditarGrupo utilizado para mostrar datos en el modal al editar --}}
                                                                                            <a class="dropdown-item btnEditarGrupo"  role="button"
                                                                                            data-toggle="modal" data-target="#modal-editar-grupo" idCodigoTipo="{{$tipo->id}}" descripcionTipo="{{$tipo->descripcion}}"
                                                                                            idCodigoGrupo="{{$grupo->id}}" descripcionGrupo="{{$grupo->descripcion}}">
                                                                                                <i class="fas fa-pen text-sm"></i> Editar Grupo
                                                                                            </a>

                                                                                            <a class="dropdown-item disabled" href="#">
                                                                                                <i class="fas fa-times text-sm"></i> Eliminar Grupo
                                                                                            </a>

                                                                                            <a class="dropdown-item btnCrearSubGrupo"  role="button"
                                                                                            data-toggle="modal" data-target="#modal-crear-subgrupo" idCodigoTipo="{{$tipo->id}}"
                                                                                            idCodigoGrupo="{{$grupo->id}}" descripcionGrupo="{{$grupo->descripcion}}">
                                                                                                <i class="fas fa-plus text-sm"></i> Agregar SubGrupo
                                                                                            </a>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="expandable-body">
                                                                    <td>
                                                                        <div class="p-0">
                                                                            {{-- tabla nivel 3 --}}
                                                                            <table class="table table-hover">
                                                                                <tbody>
                                                                                    @foreach ( $sub_grupos as $sub_grupo )
                                                                                    @if ($sub_grupo->tipo_id == $tipo->id && $sub_grupo->grupo_id == $grupo->id)
                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                            <td>
                                                                                                <div class="row text-success">
                                                                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                                                                        <i class="expandable-table-caret fas fa-caret-right fa-fw text-success"></i>
                                                                                                        {{$sub_grupo->id}} {{-- NIVEL 3 codigo--}}
                                                                                                    </div>
                                                                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                                                                        {{$sub_grupo->descripcion}} {{-- NIVEL 3 nombre--}}
                                                                                                    </div>
                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 text-success">
                                                                                                        {{$sub_grupo->correlativo}} {{-- NIVEL 3 correlativo--}}
                                                                                                    </div>
                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                        {{$sub_grupo->nivel}} {{-- NIVEL 3 nivel--}}
                                                                                                    </div>

                                                                                                    {{--! opciones desplegables en sub-grupo --}}
                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                        <div class="btn-group">
                                                                                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle text-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                                            </button>
                                                                                                            <div class="dropdown-menu">
                                                                                                                <form action="">
                                                                                                                    <a class="dropdown-item btnEditarSubGrupo"  role="button"
                                                                                                                    data-toggle="modal" data-target="#modal-editar-subgrupo"
                                                                                                                    idCodigoGrupo="{{$grupo->id}}" descripcionGrupo="{{$grupo->descripcion}}"
                                                                                                                    idCodigoSubGrupo="{{$sub_grupo->id}}" descripcionSubGrupo="{{$sub_grupo->descripcion}}">
                                                                                                                        <i class="fas fa-pen text-sm"></i> Editar Sub-Grupo
                                                                                                                    </a>

                                                                                                                    <a class="dropdown-item disabled" href="#">
                                                                                                                        <i class="fas fa-times text-sm"></i> Eliminar Sub-Grupo
                                                                                                                    </a>

                                                                                                                    <a class="dropdown-item btnCrearCuenta"  role="button"
                                                                                                                    data-toggle="modal" data-target="#modal-crear-cuenta" idCodigoTipo="{{$tipo->id}}"
                                                                                                                    idCodigoSubGrupo="{{$sub_grupo->id}}" descripcionSubGrupo="{{$sub_grupo->descripcion}}">
                                                                                                                        <i class="fas fa-plus text-sm"></i> Agregar Cuenta
                                                                                                                    </a>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr class="expandable-body">
                                                                                            <td>
                                                                                                <div class="p-0">
                                                                                                    {{-- tabla nivel 4 --}}
                                                                                                    <table class="table table-hover">
                                                                                                        <tbody>
                                                                                                            @foreach ( $cuentas as $cuenta )
                                                                                                            @if ($cuenta->tipo_id == $tipo->id && $cuenta->subGrupo_id == $sub_grupo->id)
                                                                                                                <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                                    <td>
                                                                                                                        <div class="row text-red">
                                                                                                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                                                                                                <i class="expandable-table-caret fas fa-caret-right fa-fw text-red"></i>
                                                                                                                                {{$cuenta->id}} {{-- NIVEL 4 codigo--}}
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                                                                                                {{$cuenta->descripcion}} {{-- NIVEL 4 nombre--}}
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-red">
                                                                                                                                {{$cuenta->correlativo}} {{-- NIVEL 4 correlativo--}}
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                                                {{$cuenta->nivel}} {{-- NIVEL 4 nivel--}}
                                                                                                                            </div>

                                                                                                                            {{--! opciones desplegables en cuenta --}}
                                                                                                                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                                                <div class="btn-group">
                                                                                                                                    <button type="button" class="btn btn-light btn-sm dropdown-toggle text-red" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                                                                                    </button>
                                                                                                                                    <div class="dropdown-menu">
                                                                                                                                        <form action="">

                                                                                                                                            <a class="dropdown-item btnEditarCuenta"  role="button"
                                                                                                                                            data-toggle="modal" data-target="#modal-editar-cuenta"
                                                                                                                                            idCodigoSubGrupo="{{$sub_grupo->id}}" descripcionSubGrupo="{{$sub_grupo->descripcion}}"
                                                                                                                                            idCodigoCuenta="{{$cuenta->id}}" descripcionCuenta="{{$cuenta->descripcion}}">
                                                                                                                                                <i class="fas fa-pen text-sm"></i> Editar Cuenta
                                                                                                                                            </a>

                                                                                                                                            <a class="dropdown-item disabled" href="#">
                                                                                                                                                <i class="fas fa-times text-sm"></i> Eliminar Cuenta
                                                                                                                                            </a>

                                                                                                                                            <a class="dropdown-item btnCrearSubCuenta"  role="button"
                                                                                                                                            data-toggle="modal" data-target="#modal-crear-subcuenta" idCodigoTipo="{{$tipo->id}}"
                                                                                                                                            idCodigoCuenta="{{$cuenta->id}}" descripcionCuenta="{{$cuenta->descripcion}}">
                                                                                                                                                <i class="fas fa-plus text-sm"></i> Agregar Sub-Cuenta
                                                                                                                                            </a>
                                                                                                                                        </form>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr class="expandable-body">
                                                                                                                    <td>
                                                                                                                        <div class="p-0">
                                                                                                                            {{-- tabla nivel 5 --}}
                                                                                                                            <table class="table table-hover">
                                                                                                                                <tbody>
                                                                                                                                    @foreach ( $sub_cuentas as $sub_cuenta )
                                                                                                                                    @if ($sub_cuenta->tipo_id == $tipo->id && $sub_cuenta->cuenta_id == $cuenta->id)
                                                                                                                                        <tr>
                                                                                                                                            <td>
                                                                                                                                                <div class="row">
                                                                                                                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                                                                                                                        {{$sub_cuenta->id}} {{-- NIVEL 5 codigo--}}
                                                                                                                                                    </div>
                                                                                                                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                                                                                                                        {{$sub_cuenta->descripcion}} {{-- NIVEL 5 nombre--}}
                                                                                                                                                    </div>
                                                                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                                                                                                                                        {{$sub_cuenta->correlativo}} {{-- NIVEL 5 correlativo--}}
                                                                                                                                                    </div>
                                                                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                                                                        {{$sub_cuenta->nivel}} {{-- NIVEL 5 nivel--}}
                                                                                                                                                    </div>

                                                                                                                                                    {{--! opciones desplegables en sub-cuenta --}}
                                                                                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                                                                                                                                        <div class="btn-group">
                                                                                                                                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                                                                                            </button>
                                                                                                                                                            <div class="dropdown-menu">
                                                                                                                                                                <form action="">
                                                                                                                                                                    <a class="dropdown-item btnEditarSubCuenta"  role="button"
                                                                                                                                                                    data-toggle="modal" data-target="#modal-editar-subcuenta"
                                                                                                                                                                    idCodigoCuenta="{{$cuenta->id}}" descripcionCuenta="{{$cuenta->descripcion}}"
                                                                                                                                                                    idCodigoSubCuenta="{{$sub_cuenta->id}}" descripcionSubCuenta="{{$sub_cuenta->descripcion}}">
                                                                                                                                                                        <i class="fas fa-pen text-sm"></i> Editar Sub-Cuenta
                                                                                                                                                                    </a>

                                                                                                                                                                    <a class="dropdown-item disabled" href="#">
                                                                                                                                                                        <i class="fas fa-times text-sm"></i> Eliminar Sub-Cuenta
                                                                                                                                                                    </a>
                                                                                                                                                                </form>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    @endif
                                                                                                                                    @endforeach

                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            @endif
                                                                                                            @endforeach

                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    @endforeach

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                    {{-- derecha --}}
                    <div class="col-md-3">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Niveles del Plan de Cuentas</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body table-responsive p-2">
                                    <table class="table table-head-fixed text-nowrap" style="width:100%">
                                        <thead>
                                            <tr >
                                                <th>ID</th>
                                                <th>Nivel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-red">
                                                <td>1</td>
                                                <td>Tipo</td>
                                            </tr>
                                            <tr class="text-blue">
                                                <td>2</td>
                                                <td>Grupo</td>
                                            </tr>
                                            <tr class="text-success">
                                                <td>3</td>
                                                <td>Sub-Grupo</td>
                                            </tr>
                                            <tr class="text-red">
                                                <td>4</td>
                                                <td>Cuenta</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Sub-Cuenta</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

            {{-- id del modal es para abrirlo desde el boton "a"
            la clase del boton para abrir el modal sirve para cargar datos con jquery o ajax ejem: class(btnEditarGrupo)
            el nombre del formulario del modal, sirve para modifcar su ruta con jquery, controlar sus envio y preguntar si se desea enviar el formulario --}}

            {{--! modal editar TIPO = met--}}
                <div class="modal fade" id="modal-editar-tipo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Editar <b>Tipo</b> del Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="modificamos con jquery" id="frmEditar-Tipo" class="frmEditar-Tipo" >
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoTipo_met">Codigo:</label>
                                            <input name="idCodigoTipo_met" id="idCodigoTipo_met" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionTipo_met">Nombre/Descripción:</label>
                                            <input name="descripcionTipo_met" id="descripcionTipo_met" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal editar TIPO  --}}

            {{--! modal crear Grupo = mcg--}}
                <div class="modal fade" id="modal-crear-grupo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Agregar <b>Grupo</b> al Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('crear-grupo')}}" id="frmCrear-Grupo" class="frmCrear-Grupo" >
                                @csrf
                                {{-- mcg = modal crar grupo--}}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoTipo_mcg">Tipo:</label>
                                            <input name="idCodigoTipo_mcg" id="idCodigoTipo_mcg" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionTipo_mcg">Descripción del Tipo:</label>
                                            <input name="descripcionTipo_mcg" id="descripcionTipo_mcg" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="descripcionGrupo_mcg">Nombre/Descripción del Nuevo Grupo:</label>
                                            <input name="descripcionGrupo_mcg" id="descripcionGrupo_mcg" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal crear Grupo --}}

            {{--! modal editar Grupo = meg--}}
                <div class="modal fade" id="modal-editar-grupo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Editar <b>Grupo</b> del Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="modificamos con jquery" id="frmEditar-Grupo" class="frmEditar-Grupo" >
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoTipo_meg">Código Tipo:</label>
                                            <input name="idCodigoTipo_meg" id="idCodigoTipo_meg" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionTipo_meg">Descripción del Tipo:</label>
                                            <input name="descripcionTipo_meg" id="descripcionTipo_meg" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoGrupo_meg">Código Grupo:</label>
                                            <input name="idCodigoGrupo_meg" id="idCodigoGrupo_meg" type="text" class="form-control" readonly required>
                                        </div>
                                        <div class="form-group col-md-7">
                                            <label for="descripcionGrupo_meg">Nombre/Descripción del Grupo:</label>
                                            <input name="descripcionGrupo_meg" id="descripcionGrupo_meg" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal crear Grupo --}}

            {{--! modal crear Sub Grupo = mcsg--}}
                <div class="modal fade" id="modal-crear-subgrupo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Agregar <b>Sub-Grupo</b> al Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('crear-subgrupo')}}" id="frmCrear-SubGrupo" class="frmCrear-SubGrupo" >
                                @csrf
                                <div class="modal-body">
                                    <div class="row">

                                        <input type="hidden" name="idCodigoTipo_mcsg" id="idCodigoTipo_mcsg">

                                        <div class="form-group col-md-5">
                                            <label for="idCodigoGrupo_mcsg">Código Grupo:</label>
                                            <input name="idCodigoGrupo_mcsg" id="idCodigoGrupo_mcsg" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionGrupo_mcsg">Descripción Grupo:</label>
                                            <input name="descripcionGrupo_mcsg" id="descripcionGrupo_mcsg" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="descripcionSubGrupo_mcsg">Nombre/Descripción del Nuevo Sub-Grupo:</label>
                                            <input name="descripcionSubGrupo_mcsg" id="descripcionSubGrupo_mcsg" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal crear Sub Grupo --}}

            {{--! modal editar Sub Grupo = mesg--}}
                <div class="modal fade" id="modal-editar-subgrupo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Editar <b>Sub-Grupo</b> del Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="modificado con jq" id="frmEditar-SubGrupo" class="frmEditar-SubGrupo" >
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="form-group col-md-5">
                                            <label for="idCodigoGrupo_mesg">Código Grupo:</label>
                                            <input name="idCodigoGrupo_mesg" id="idCodigoGrupo_mesg" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionGrupo_mesg">Descripción Grupo:</label>
                                            <input name="descripcionGrupo_mesg" id="descripcionGrupo_mesg" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoSubGrupo_mesg">Código Sub-Grupo:</label>
                                            <input name="idCodigoSubGrupo_mesg" id="idCodigoSubGrupo_mesg" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionSubGrupo_mesg">Nombre/Descripción del Sub-Grupo:</label>
                                            <input name="descripcionSubGrupo_mesg" id="descripcionSubGrupo_mesg" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal editar Sub Grupo --}}

            {{--! modal crear cuenta = mcc--}}
                <div class="modal fade" id="modal-crear-cuenta">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Agregar <b>Cuenta</b> al Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('crear-cuenta')}}" id="frmCrear-Cuenta" class="frmCrear-Cuenta" >
                                @csrf
                                <div class="modal-body">
                                    <div class="row">

                                        <input type="hidden" name="idCodigoTipo_mcc" id="idCodigoTipo_mcc">

                                        <div class="form-group col-md-5">
                                            <label for="idCodigoSubGrupo_mcc">Código Sub-Grupo:</label>
                                            <input name="idCodigoSubGrupo_mcc" id="idCodigoSubGrupo_mcc" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionSubGrupo_mcc">Descripción Sub-Grupo:</label>
                                            <input name="descripcionSubGrupo_mcc" id="descripcionSubGrupo_mcc" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="descripcionCuenta_mcc">Nombre/Descripción de la nueva Cuenta:</label>
                                            <input name="descripcionCuenta_mcc" id="descripcionCuenta_mcc" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal crear Sub Grupo --}}

            {{--! modal editar Cuenta = mec--}}
                <div class="modal fade" id="modal-editar-cuenta">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Editar <b>Cuenta</b> del Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="modificado con jq" id="frmEditar-Cuenta" class="frmEditar-Cuenta" >
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="form-group col-md-5">
                                            <label for="idCodigoSubGrupo_mec">Código Sub-Grupo:</label>
                                            <input name="idCodigoSubGrupo_mec" id="idCodigoSubGrupo_mec" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionSubGrupo_mec">Descripción Sub-Grupo:</label>
                                            <input name="descripcionSubGrupo_mec" id="descripcionSubGrupo_mec" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoCuenta_mec">Código Cuenta:</label>
                                            <input name="idCodigoCuenta_mec" id="idCodigoCuenta_mec" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionCuenta_mec">Nombre/Descripción de la Cuenta:</label>
                                            <input name="descripcionCuenta_mec" id="descripcionCuenta_mec" type="text" maxlength="100" class="form-control text-uppercase"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal editar Cuenta --}}

            {{--! modal crear subcuenta = mcsc--}}
                <div class="modal fade" id="modal-crear-subcuenta">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Agregar <b>Sub-Cuenta</b> al Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('crear-subcuenta')}}" id="frmCrear-SubCuenta" class="frmCrear-SubCuenta" >
                                @csrf
                                <div class="modal-body">
                                    <div class="row">

                                        <input type="hidden" name="idCodigoTipo_mcsc" id="idCodigoTipo_mcsc">

                                        <div class="form-group col-md-5">
                                            <label for="idCodigoCuenta_mcsc">Código Cuenta:</label>
                                            <input name="idCodigoCuenta_mcsc" id="idCodigoCuenta_mcsc" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionCuenta_mcsc">Descripción Cuenta:</label>
                                            <input name="descripcionCuenta_mcsc" id="descripcionCuenta_mcsc" type="text" class="form-control" autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="descripcionSubCuenta_mcsc">Nombre/Descripción de la nueva Sub-Cuenta:</label>
                                            <input name="descripcionSubCuenta_mcsc" id="descripcionSubCuenta_mcsc" type="text" maxlength="100" class="form-control"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal crear Sub Grupo --}}

            {{--! modal editar SubCuenta = mesc--}}
                <div class="modal fade" id="modal-editar-subcuenta">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="cursor: move;">
                                <h4 class="modal-title" style="cursor: text;">Editar <b>Sub-Cuenta</b> del Plan de Cuentas</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="modificado con jq" id="frmEditar-SubCuenta" class="frmEditar-SubCuenta" >
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoCuenta_mesc">Código Cuenta:</label>
                                            <input name="idCodigoCuenta_mesc" id="idCodigoCuenta_mesc" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="descripcionCuenta_mesc">Nombre/Descripción de la Cuenta:</label>
                                            <input name="descripcionCuenta_mesc" id="descripcionCuenta_mesc" type="text" class="form-control text-uppercase"
                                            autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label for="idCodigoSubCuenta_mesc">Código Sub-Cuenta:</label>
                                            <input name="idCodigoSubCuenta_mesc" id="idCodigoSubCuenta_mesc" type="text" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group col-md-7">
                                            <label for="1">Nombre/Descripción de la Sub-Cuenta:</label>
                                            <input name="descripcionSubCuenta_mesc" id="descripcionSubCuenta_mesc" type="text" maxlength="100" class="form-control"
                                            autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                    <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            {{--! Fin modal editar Cuenta --}}

        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuPlanDeCuentas').addClass('active');
    </script>

    {{--! colapsar menu --}}
    <script>
        // document.getElementById("body").classList.remove('')
        document.getElementById("body").classList.add('sidebar-collapse');
    </script>

    {{--? PREGUNTAS --}}
        {{--! Pregunta desea EDITAR TIPO--}}
        <script>
            $('.frmEditar-Tipo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios en el Tipo?',
                text: "¡Actualizará el Tipo del plan de cuentas!",
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
        {{--! Pregunta desea CREAR GRUPO--}}
        <script>
            $('.frmCrear-Grupo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar el Grupo?',
                text: "¡Agregará un nuevo grupo al plan de cuentas!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //enviamos el formulario
                        this.submit();
                    }
                })
            })
        </script>
        {{--! Pregunta desea EDITAR GRUPO--}}
        <script>
            $('.frmEditar-Grupo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios en el Grupo?',
                text: "¡Actualizará el Grupo del plan de cuentas!",
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
        {{--! Pregunta desea CREAR SUBGRUPO--}}
        <script>
            $('.frmCrear-SubGrupo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar el Sub-Grupo?',
                text: "¡Agregará un nuevo sub-grupo al plan de cuentas!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //enviamos el formulario
                        this.submit();
                    }
                })
            })
        </script>
        {{--! Pregunta desea EDITAR SUBGRUPO--}}
        <script>
            $('.frmEditar-SubGrupo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Sub-Grupo?',
                text: "¡Actualizará el sub-grupo del plan de cuentas!",
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
        {{--! Pregunta desea CREAR CUENTA--}}
        <script>
            $('.frmCrear-Cuenta').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar la Cuenta?',
                text: "¡Agregará una nueva Cuenta al plan de cuentas!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //enviamos el formulario
                        this.submit();
                    }
                })
            })
        </script>
        {{--! Pregunta desea EDITAR CUENTA--}}
        <script>
            $('.frmEditar-Cuenta').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios de la Cuenta?',
                text: "¡Actualizará la cuenta del plan de cuentas!",
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
        {{--! Pregunta desea CREAR SUBCUENTA--}}
        <script>
            $('.frmCrear-SubCuenta').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar la Sub-Cuenta?',
                text: "¡Agregará una nueva sub-cuenta al plan de cuentas!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //enviamos el formulario
                        this.submit();
                    }
                })
            })
        </script>
        {{--! Pregunta desea EDITAR SUBCUENTA--}}
        <script>
            $('.frmEditar-SubCuenta').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios de la Sub-Cuenta?',
                text: "¡Actualizará la sub-cuenta del plan de cuentas!",
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
    {{--? PREGUNTAS --}}

    {{--? ERRORES--}}
        @if (Session('error_crear')=='error')
        <script>
                toastr.error('Ocurrió un error al intentar crear el item, recarga la página y vuelve a intentarlo.');
        </script>
        @endif

        @if (Session('error_actualizar')=='error')
        <script>
                toastr.error('Ocurrió un error al intentar actualizar el item, recarga la página y vuelve a intentarlo.');
        </script>
        @endif
    {{--? ERRORES--}}

    {{--? MENSAJES --}}
        {{--! este mensaje es recibido al EDITAR TIPO --}}
        @if (Session('actualizar_tipo')=='ok')
        <script>
                toastr.success('Tipo del plan contable actualizado exitosamente.');
        </script>
        @endif

        {{--! este mensaje es recibido al CREAR NUEVO GRUPO --}}
        @if (Session('crear_grupo')=='ok')
        <script>
                toastr.success('Grupo creado exitosamente.');
        </script>
        @endif
        {{--! este mensaje es recibido al ACTUALIZAR GRUPO --}}
        @if (Session('actualizar_grupo')=='ok')
        <script>
                toastr.success('Grupo del plan contable actualizado exitosamente.');
        </script>
        @endif

        {{--! este mensaje es recibido al CREAR NUEVO SUBGRUPO --}}
        @if (Session('crear_subgrupo')=='ok')
        <script>
                toastr.success('Sub-Grupo creado exitosamente.');
        </script>
        @endif
        {{--! este mensaje es recibido al ACTUALIZAR SUBGRUPO --}}
        @if (Session('actualizar_subgrupo')=='ok')
        <script>
                toastr.success('Sub-Grupo del plan contable actualizado exitosamente.');
        </script>
        @endif

        {{--! este mensaje es recibido al CREAR CUENTA --}}
        @if (Session('crear_cuenta')=='ok')
        <script>
                toastr.success('Cuenta creada exitosamente.');
        </script>
        @endif
        {{--! este mensaje es recibido al EDITAR CUENTA --}}
        @if (Session('actualizar_cuenta')=='ok')
        <script>
                toastr.success('Cuenta del plan de cuentas actualizada exitosamente.');
        </script>
        @endif

        {{--! este mensaje es recibido al CREAR SUBCUENTA --}}
        @if (Session('crear_subcuenta')=='ok')
        <script>
                toastr.success('Sub-Cuenta creada exitosamente.');
        </script>
        @endif
        {{--! este mensaje es recibido al EDITAR SUBCUENTA --}}
        @if (Session('actualizar_subcuenta')=='ok')
        <script>
                toastr.success('Sub-Cuenta del plan de cuentas actualizada exitosamente.');
        </script>
        @endif
    {{--? MENSAJES --}}

    {{--! datos para editar tipo--}}
    <script>
        /* mostramos datos del tipo en el modal de edicion*/

        $(".btnEditarTipo").click(function() {
            let idCodigoTipo = $(this).attr("idCodigoTipo");
            let descripcionTipo = $(this).attr("descripcionTipo");

            $("#idCodigoTipo_met").val(idCodigoTipo); //mostramos codigo
            $("#descripcionTipo_met").val(descripcionTipo); //mostramos nombre

            //redireccionamos action del formulario
            $("#frmEditar-Tipo").prop("action","/contabilidad/plan-de-cuentas/actualizar-tipo/"+idCodigoTipo);
        });
    </script>

    {{--! datos para crear grupo--}}
    <script>
        /* mostramos datos del tipo en el modal de creacion*/

        $(".btnCrearGrupo").click(function() {
            let idCodigoTipo = $(this).attr("idCodigoTipo"); //para la clave foranea
            let descripcionTipo = $(this).attr("descripcionTipo");

            $("#idCodigoTipo_mcg").val(idCodigoTipo); //mostramos codigo
            $("#descripcionTipo_mcg").val(descripcionTipo); //mostramos nombre
            //#descripcionGrupo_mcg //usado para poner el nuevo grupo
        });
    </script>

    {{--! datos para editar grupo--}}
    <script>
        $(".btnEditarGrupo").click(function() {
            /* atributos */
            let idCodigoTipo = $(this).attr("idCodigoTipo"); //para la clave foranea
            let descripcionTipo = $(this).attr("descripcionTipo");

            let idCodigoGrupo = $(this).attr("idCodigoGrupo"); //para los campos de edicion
            let descripcionGrupo = $(this).attr("descripcionGrupo");

            /* inputs en el modal de edicion */
            $("#idCodigoTipo_meg").val(idCodigoTipo); //mostramos codigo tipo
            $("#descripcionTipo_meg").val(descripcionTipo); //mostramos nombre del tipo

            $("#idCodigoGrupo_meg").val(idCodigoGrupo); //mostramos codigo grupo
            $("#descripcionGrupo_meg").val(descripcionGrupo); //mostramos nombre del grupo

            //redireccionamos action del formulario
            $("#frmEditar-Grupo").prop("action","/contabilidad/plan-de-cuentas/actualizar-grupo/"+idCodigoGrupo);
        });
    </script>

    {{--! datos para crear sub grupo--}}
    <script>
        $(".btnCrearSubGrupo").click(function() {
            /* atributos */
            let idCodigoTipo = $(this).attr("idCodigoTipo"); //para la clave foranea en plandecuentas
            let idCodigoGrupo = $(this).attr("idCodigoGrupo"); //para la clave foranea en subgrupo
            let descripcionGrupo = $(this).attr("descripcionGrupo");
            /* inputs del modal  de creacion*/
            $("#idCodigoGrupo_mcsg").val(idCodigoGrupo); //mostramos codigo
            $("#descripcionGrupo_mcsg").val(descripcionGrupo); //mostramos nombre
            $("#idCodigoTipo_mcsg").val(idCodigoTipo); //mostramos nombre
        });
    </script>

    {{--! datos para editar sub grupo--}}
    <script>
        $(".btnEditarSubGrupo").click(function() {
            /* atributos */
            let idCodigoGrupo = $(this).attr("idCodigoGrupo"); //para la clave foranea en subgrupo
            let descripcionGrupo = $(this).attr("descripcionGrupo");

            let idCodigoSubGrupo = $(this).attr("idCodigoSubGrupo"); //para los campos de edicion
            let descripcionSubGrupo = $(this).attr("descripcionSubGrupo");

            /* inputs en el modal de edicion */
            $("#idCodigoGrupo_mesg").val(idCodigoGrupo); //mostramos codigo
            $("#descripcionGrupo_mesg").val(descripcionGrupo); //mostramos nombre

            $("#idCodigoSubGrupo_mesg").val(idCodigoSubGrupo); //mostramos codigo
            $("#descripcionSubGrupo_mesg").val(descripcionSubGrupo); //mostramos nombre

            //redireccionamos action del formulario
            $("#frmEditar-SubGrupo").prop("action","/contabilidad/plan-de-cuentas/actualizar-subgrupo/"+idCodigoSubGrupo);
        });
    </script>

    {{--! datos para crear cuenta--}}
    <script>
        $(".btnCrearCuenta").click(function() {
            /* atributos */
            let idCodigoTipo = $(this).attr("idCodigoTipo"); //para la clave foranea en plandecuentas
            let idCodigoSubGrupo = $(this).attr("idCodigoSubGrupo"); //para la clave foranea en subgrupo
            let descripcionSubGrupo = $(this).attr("descripcionSubGrupo");

            /* inputs del modal  de creacion*/
            $("#idCodigoTipo_mcc").val(idCodigoTipo);
            $("#idCodigoSubGrupo_mcc").val(idCodigoSubGrupo); //mostramos codigo
            $("#descripcionSubGrupo_mcc").val(descripcionSubGrupo); //mostramos nombre
        });
    </script>

    {{--! datos para editar cuenta--}}
    <script>
        $(".btnEditarCuenta").click(function() {
            /* atributos */
            let idCodigoSubGrupo = $(this).attr("idCodigoSubGrupo"); //para los campos de edicion clave foranea en subgrupo
            let descripcionSubGrupo = $(this).attr("descripcionSubGrupo");

            let idCodigoCuenta = $(this).attr("idCodigoCuenta"); //para los campos de edicion
            let descripcionCuenta = $(this).attr("descripcionCuenta");


            /* inputs en el modal de edicion */
            $("#idCodigoSubGrupo_mec").val(idCodigoSubGrupo); //mostramos codigo
            $("#descripcionSubGrupo_mec").val(descripcionSubGrupo); //mostramos nombre

            $("#idCodigoCuenta_mec").val(idCodigoCuenta); //mostramos codigo
            $("#descripcionCuenta_mec").val(descripcionCuenta); //mostramos nombre

            //redireccionamos action del formulario
            $("#frmEditar-Cuenta").prop("action","/contabilidad/plan-de-cuentas/actualizar-cuenta/"+idCodigoCuenta);
        });
    </script>

    {{--! datos para crear subcuenta--}}
    <script>
        $(".btnCrearSubCuenta").click(function() {
            /* atributos */
            let idCodigoTipo = $(this).attr("idCodigoTipo"); //para la clave foranea en plandecuentas
            let idCodigoCuenta = $(this).attr("idCodigoCuenta"); //para la clave foranea en Cuenta
            let descripcionCuenta = $(this).attr("descripcionCuenta");

            /* inputs del modal  de creacion*/
            $("#idCodigoTipo_mcsc").val(idCodigoTipo);
            $("#idCodigoCuenta_mcsc").val(idCodigoCuenta); //mostramos codigo
            $("#descripcionCuenta_mcsc").val(descripcionCuenta); //mostramos nombre
        });
    </script>

    {{--! datos para editar subcuenta--}}
    <script>
        $(".btnEditarSubCuenta").click(function() {
            /* atributos */
            let idCodigoCuenta = $(this).attr("idCodigoCuenta"); //para los campos de edicion
            let descripcionCuenta = $(this).attr("descripcionCuenta");
            let idCodigoSubCuenta = $(this).attr("idCodigoSubCuenta"); //para los campos de edicion
            let descripcionSubCuenta = $(this).attr("descripcionSubCuenta");

            /* inputs en el modal de edicion */
            $("#idCodigoCuenta_mesc").val(idCodigoCuenta); //mostramos codigo
            $("#descripcionCuenta_mesc").val(descripcionCuenta); //mostramos nombre

            $("#idCodigoSubCuenta_mesc").val(idCodigoSubCuenta); //mostramos codigo
            $("#descripcionSubCuenta_mesc").val(descripcionSubCuenta); //mostramos nombre

            //redireccionamos action del formulario
            $("#frmEditar-SubCuenta").prop("action","/contabilidad/plan-de-cuentas/actualizar-subcuenta/"+idCodigoSubCuenta);
        });
    </script>

    {{--! draggable de los modals --}}
    <script>
        $("#modal-editar-tipo").draggable({
            handle: ".modal-header"
        });

        $("#modal-crear-grupo").draggable({
            handle: ".modal-header"
        });
        $("#modal-editar-grupo").draggable({
            handle: ".modal-header"
        });

        $("#modal-crear-subgrupo").draggable({
            handle: ".modal-header"
        });
        $("#modal-editar-subgrupo").draggable({
            handle: ".modal-header"
        });

        $("#modal-crear-cuenta").draggable({
            handle: ".modal-header"
        });
        $("#modal-editar-cuenta").draggable({
            handle: ".modal-header"
        });

        $("#modal-crear-subcuenta").draggable({
            handle: ".modal-header"
        });
        $("#modal-editar-subcuenta").draggable({
            handle: ".modal-header"
        });
    </script>
@endsection
