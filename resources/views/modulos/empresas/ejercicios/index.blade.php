@extends('plantilla.adminlte')

@section('titulo')
Ejercicios Contables
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{--! DataTables --}}
    {{--<link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}

    {{--! /* Quitamos flechas del imput number */ --}}
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
        input[type=number] { -moz-appearance:textfield; }
    </style>
@endsection

@section('contenido')
        <div class="content-wrapper">
            {{-- ! Encabezado --}}
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><a href="/ejercicios">Ejercicios Contables</a></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                                <li class="breadcrumb-item"><a href="/empresas">Empresas</a></li>
                                <li class="breadcrumb-item active">Ejercicios Contables</li>
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
                    <form method="GET" action="{{ route('ejercicios.index') }}">
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{--* input denominacion social --}}
                                    <label>Denominación de la Empresa</label>
                                    <select name="id_denominacionSocial" id="id_denominacionSocial" class="form-control select2" style="width: 100%;">
                                        <option></option>

                                        @if (Auth::user()->mostrarBajas == 0)
                                            {{--! no mostrar bajas solo activos--}}
                                            @foreach ($empresas as $empresa)
                                                @if ($empresa->estado == 1)
                                                    @if ($empresaBuscada != "")
                                                        @if ($empresa->id == $empresaBuscada->id)
                                                            <option value="{{ $empresa->id }}" selected>{{ $empresa->denominacionSocial }}</option>
                                                        @else
                                                            <option value="{{ $empresa->id }}">{{ $empresa->denominacionSocial }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $empresa->id }}">{{ $empresa->denominacionSocial }}</option>
                                                    @endif
                                                @endif
                                            @endforeach

                                        @else

                                            {{--! mostramos bajas --}}
                                            @foreach ($empresas as $empresa)
                                                @php
                                                    if ($empresa->estado == 1) {
                                                        $estado = "(Alta)";
                                                    }
                                                    else {
                                                        $estado = "(Baja)";
                                                    }
                                                @endphp

                                                @if ($empresaBuscada != "")
                                                    @if ($empresa->id == $empresaBuscada->id)
                                                        <option value="{{ $empresa->id }}" selected>{{$estado.' - '.$empresa->denominacionSocial }}</option>
                                                    @else
                                                        <option value="{{ $empresa->id }}">{{$estado.' - '.$empresa->denominacionSocial }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $empresa->id }}" class="text-red">{{$estado.' - '.$empresa->denominacionSocial }}</option>
                                                @endif
                                            @endforeach

                                        @endif
                                    </select>
                                    {{--* Fin input denominacion social --}}
                                </div>
                            </div>

                            {{-- * Botones busqueda --}}
                            <div class="col-md-2" {{-- style="display: flex; align-items: flex-end" --}}>
                                <label></label>
                                {{-- btn-outline-primary --}}
                                <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                                    Buscar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-ejercicio">
                                    <i class="fas fa-plus"></i> Nuevo Ejercicio
                                </button>
                            </div>

                        </div>
                    </form>
                    {{--* Fin Buscador --}}
                    {{-- ! DataTable --}}
                    @if ($empresaBuscada != "")
                        {{--? count($ejerciciosEncontrados) > 0 --}}
                        @if (isset($ejerciciosEncontrados) )
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dark">
                                        <div class="card-header">
                                            @if(isset($empresaBuscada))
                                            <h3 class="card-title">EMPRESA: {{ $empresaBuscada->denominacionSocial }}</h3>
                                            <br> {{-- salto de linea --}}
                                            <h3 class="card-title">NIT: {{ $empresaBuscada->nit }}</h3>
                                            @endif
                                        </div>
                                        <!-- /.card-header -->

                                        <div class="card-body table-responsive p-2" >
                                            <table id="tablaEjercicios" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                                <thead>
                                                        <tr>
                                                            <th class="text-center">Nro.</th>
                                                            <th class="text-center">Ejercicio</th>
                                                            <th class="text-center">Fecha Inicio</th>
                                                            <th class="text-center">Fecha Cierre</th>
                                                            <th class="text-center">Trabajo</th>
                                                            <th class="text-center">Acciones</th>
                                                            @if (Auth::user()->mostrarBajas == 1)
                                                                <th>Alta/Baja</th>
                                                            @endif
                                                        </tr>
                                                </thead>

                                                <tbody>
                                                    @php // declaramos la variable, no la imprimimos aun
                                                        $numero = 0;
                                                    @endphp

                                                    @foreach ($ejerciciosEncontrados as $ejercicio)

                                                        <tr>
                                                            <td class="text-center">{{ $numero = $numero + 1 }}</td>
                                                            <td class="text-center">{{ $ejercicio->ejercicioFiscal}}</td>
                                                            <td class="text-center">
                                                                @php
                                                                $fi = explode('-',$ejercicio->fechaInicio);
                                                                $fi2 = $fi[2]."/".$fi[1]."/".$fi[0];
                                                                @endphp

                                                                {{$fi2}}
                                                            </td>
                                                            <td class="text-center">
                                                                @php
                                                                $ff = explode('-',$ejercicio->fechaCierre);
                                                                $ff2 = $ff[2]."/".$ff[1]."/".$ff[0];
                                                                @endphp
                                                                {{$ff2}}
                                                            </td>

                                                            {{-- Trabajo --}}
                                                            @if ($ejercicio->estado == 1)
                                                                {{-- muestra botones solo si estan con alta --}}
                                                                @if ($ejercicio->id == Auth::user()->idEjercicioActivo)
                                                                <td style="text-align: center">
                                                                    <div class=".btn-group btn-group-sm">
                                                                        <a role="button" class="btn btn-success">
                                                                            <i class="fas fa-toggle-on"></i> Activo
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                @else
                                                                    <form method="POST" action="{{route('usuarios.update',Auth::user()->id)}}" class="frmActivar-EmpresaEjercicio">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <td style="text-align: center">
                                                                            <div class=".btn-group btn-group-sm">
                                                                                {{-- readonly-> es solo lectura- este se envia
                                                                                desabled-> desactivado . no envia --}}
                                                                                <input type="text" name="validador" hidden readonly value="EmpresaEjercicio">
                                                                                <input type="text" name="empresaNueva" hidden readonly value="{{$empresaBuscada->id}}">
                                                                                <input type="text" name="ejercicioNuevo" hidden readonly value="{{$ejercicio->id}}">
                                                                                <button type="submit" class="btn btn-warning">
                                                                                    <i class="fas fa-toggle-off"></i> Inactivo
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                <td></td>
                                                            @endif

                                                            {{-- Botones --}}
                                                            <td style="text-align: center">
                                                                @if ($ejercicio->estado == 1)
                                                                    {{-- muestra botones solo si estan con alta --}}
                                                                    <form  action="{{route ('ejercicios.destroy',$ejercicio->id)}}" method="POST" class="frmEliminar-Ejercicio">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a role="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modal-editar-ejercicio{{$ejercicio->id}}">
                                                                            <i class="fas fa-pen"></i>
                                                                            </a>
                                                                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            </td>

                                                            {{-- Alta Baja --}}
                                                            @if (Auth::user()->mostrarBajas == 1)
                                                                @if ($ejercicio->estado == 1)
                                                                    <td style="text-align: center">
                                                                        <div class=".btn-group btn-group-sm">
                                                                            <a role="button" class="btn btn-success">
                                                                                Alta
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                @else
                                                                    <form method="POST" action="{{ route('ejercicios.update',$ejercicio->id) }}" class="frmEjericio-AltaBaja">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <td style="text-align: center">
                                                                            <div class=".btn-group btn-group-sm">
                                                                                <input type="hidden" name="validador" value="DarDeAlta">
                                                                                <button role="submit" class="btn btn-danger">
                                                                                    Baja
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <br>
                                            <div class="d-flex justify-content-end p-2">
                                                {!!$ejerciciosEncontrados->links()!!}
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    {{-- ! Fin DataTable --}}
                </div>

                {{--! modal Crear ejercicio--}}
                <div class="modal fade" id="modal-crear-ejercicio">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Crear Ejercicio</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="/ejercicios" class="frmCrear-Ejercicio" >
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">

                                        {{--! Denominacion Social --}}
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <label>Denominación de la Empresa</label>
                                                <select name="id_denominacionSocial"  class="form-control select2" style="width: 100%;" required>

                                                    <option value=""></option>
                                                    @foreach ($empresas as $empresa)
                                                        {{--? mostramos solo activos --}}
                                                        @if ($empresa->estado == 1)

                                                            @if ($empresaBuscada != "")
                                                                @if ($empresa->id == $empresaBuscada->id)
                                                                    <option value="{{ $empresa->id }}" selected>{{ $empresa->denominacionSocial }}</option>
                                                                @else
                                                                    <option value="{{ $empresa->id }}">{{ $empresa->denominacionSocial }}</option>
                                                                @endif
                                                            @else
                                                                <option value="{{ $empresa->id }}">{{ $empresa->denominacionSocial }}</option>
                                                            @endif

                                                        @endif

                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="ejercicioFiscal">Ejercicio Contable</label>
                                        {{--<input type="number" name="ejercicioFiscal" id="ejercicioFiscal" class="form-control" placeholder="Ejemplo: 20XX" autocomplete="off" required> --}}
                                        <select name="ejercicioFiscal" id="ejercicioFiscal" class="form-control" style="width: 100%;" required>
                                            @php
                                                $anio =  date("Y");
                                                $anioReferencia = $anioMinimo;//año minimo
                                            @endphp
                                            @while ($anio >= $anioReferencia)
                                                <option value="{{$anio}}">{{$anio}}</option>
                                                @php
                                                    $anio = $anio-1;
                                                @endphp
                                            @endwhile
                                        </select>
                                    </div>

                                    <div class="row">
                                        <!-- fechas dd/mm/yyyy -->
                                        <div class="form-group col-sm-6">
                                            <label class="form-label" for="date-mask-input-a">Fecha de Inicio</label>
                                            <input type="text" class="form-control fecha" id="date-mask-input-a" name="fechaInicio" required autocomplete="off">
                                            <small class="text-muted">Formato: dd/mm/aaaa</small>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label" for="date-mask-input-b">Fecha de Cierre</label>
                                            <input type="text" class="form-control fecha" id="date-mask-input-b" name="fechaCierre" required autocomplete="off">
                                            <small class="text-muted">Formato: dd/mm/aaaa</small>
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
                {{--! Fin modal Crear ejercicio--}}

                {{-- ! modals de Editar --}}
                @foreach ($ejerciciosEncontrados as $ejercicio)
                    <div class="modal fade" id="modal-editar-ejercicio{{$ejercicio->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Editar Ejercicio</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('ejercicios.update',$ejercicio->id) }}" class="frmEditar-Ejercicio" >
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            {{--! if Denominacion Social --}}
                                            @if ($empresaBuscada != "" )
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{ $empresaBuscada->denominacionSocial }}</label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Denominación de la Empresa</label>
                                                    </div>
                                                </div>
                                            @endif
                                            {{--! fin Denominacion Social --}}
                                        </div>

                                        <div class="form-group">
                                            <label for="ejercicioFiscal">Ejercicio Contable</label>
                                            {{-- <input type="number" name="ejercicioFiscal" id="ejercicioFiscal2" class="form-control"
                                            placeholder="Ejemplo: 2022" autocomplete="off" value="{{ $ejercicio->ejercicioFiscal}}" required> --}}

                                            <select name="ejercicioFiscal" id="ejercicioFiscal" class="form-control" style="width: 100%;" required>

                                                @php
                                                    $anio =  date("Y");
                                                    $anioReferencia = $anioMinimo;//añp minimo
                                                @endphp
                                                @while ($anio >= $anioReferencia)

                                                        @if ($ejercicio->ejercicioFiscal == $anio)
                                                            <option value="{{$anio}}" selected>{{$anio}}</option>
                                                        @else
                                                            <option value="{{$anio}}">{{$anio}}</option>
                                                        @endif

                                                    @php
                                                        $anio = $anio-1;
                                                    @endphp
                                                @endwhile
                                            </select>
                                        </div>

                                        <div class="row">
                                            <!-- fechas dd/mm/yyyy -->
                                            @php
                                                $fi = explode('-',$ejercicio->fechaInicio);
                                                $fi2 = $fi[2]."/".$fi[1]."/".$fi[0];

                                                $ff = explode('-',$ejercicio->fechaCierre);
                                                $ff2 = $ff[2]."/".$ff[1]."/".$ff[0];
                                            @endphp

                                            <div class="form-group col-sm-6">
                                                <label class="form-label" for="date-mask-input-a">Fecha de Inicio</label>
                                                <input type="text" class="form-control fecha" id="date-mask-input-a" name="fechaInicio" required autocomplete="off"
                                                value="{{$fi2}}">
                                                <small class="text-muted">Formato: dd/mm/aaaa</small>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="form-label" for="date-mask-input-b">Fecha de Cierre</label>
                                                <input type="text" class="form-control fecha" id="date-mask-input-b" name="fechaCierre" required autocomplete="off"
                                                value="{{$ff2}}">
                                                <small class="text-muted">Formato: dd/mm/aaaa</small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="submit" class="btn btn-primary col-md-3">Actualizar</button>
                                        <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                @endforeach
                {{-- ! Fin modals de Editar --}}

            </section>
            {{-- ! Fin Contenido --}}
        </div>
        <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuGestionarEmpresas').addClass('menu-open');
        $('#menuEmpresas').addClass('active');
        $('#submenuEjercicios').addClass('active');
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! este mensaje es recibido al CREAR NUEVO EJERCICIO --}}
    @if (Session('crear')=='ok')
        <script>
                toastr.success('Ejercicio creado exitosamente.')
        </script>
    @endif

    @if (Session('errorFecha')=='error')
        <script>
                toastr.error('Fecha erronea.')
        </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR EJERCICIO --}}
    @if (Session('actualizar')=='ok')
        <script>
                toastr.success('Datos actualizados con éxito.')
        </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR EJERCICIO --}}
    @if (Session('eliminar')=='ok')
        <script>
                toastr.success('Ejercicio Contable eliminado exitosamente.')
        </script>
    @endif

    {{--! Pregunta desea CREAR EJERCICIO--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Ejercicio').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear Ejercicio Contable?',
                text: "¡Creará un nuevo Ejercicio Contable!",
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
    @else
        <script>
            $('.frmCrear-Ejercicio').submit(function(e){
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

    {{--! Pregunta desea EDITAR EJERCICIO--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Ejercicio').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Ejercicio Contable?',
                text: "¡Actualizará el Ejercicio Contable!",
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
    @else
        <script>
            $('.frmEditar-Ejercicio').submit(function(e){
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

    {{--! Pregunta desea DAR DE ALTA EJERCICIO--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEjericio-AltaBaja').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de Alta al Ejercicio Contable?',
                text: "¡El estado actual de Ejercicio Contable es de Baja o Eliminado! ¿Desea recuperarlo?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, dar de Recuperar',
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
            $('.frmEjericio-AltaBaja').submit(function(e){
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

    {{--! Pregunta Eliminar EJERCICIO --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-Ejercicio').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea Eliminar el Ejercicio Contable?',
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
    @else
        <script>
            $('.frmEliminar-Ejercicio').submit(function(e){
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

    {{--! Pregunta ACTIVAR EJERCICIO --}}
    <script>
        $('.frmActivar-EmpresaEjercicio').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Activar este Ejercicio Contable?',
            text: "¡Tendrá activa esta empresa y este ejercicio contable para trabajar con ella!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Activar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

    {{--! mascara fecha start ui--}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/custom-code/input-mask/input-mask-init.js') }}"></script>

    {{--! limite de los caracteres --}}
    <script>
        //oninput //! al cambiar algo dentro del input
        //onchange //! al cambiar de input
        //el final de la extracción. slice extrae hasta, pero sin incluir el final, cuenta desde el cero

        //PARA CREAR
        // limite caracteres de ejercicioFiscal
        ejercicioFiscal.oninput = function(){
            if (this.value.length > 4) {
                this.value = this.value.slice(0,4);
            }
        }
        //minimo de caracteres de ejercicioFiscal
        ejercicioFiscal.onchange = function(){
            if (this.value.length < 4) {
                alert("Revisa el Ejercicio Contable, tiene menos de 4 carácteres.");
                this.select();
            }
        }

        //PARA ACTUALIZAR
        // limite caracteres de ejercicioFiscal
        document.getElementById("ejercicioFiscal2").oninput = function(){
            if (this.value.length > 4) {
                this.value = this.value.slice(0,4);
            }
        }
        //minimo de caracteres de ejercicioFiscal
        document.getElementById("ejercicioFiscal2").onchange = function(){
            if (this.value.length < 4) {
                alert("Revisa el Ejercicio Contable, tiene menos de 4 carácteres.");
                this.select();
            }
        }
    </script>

    {{--! validacion de año ingresado en inputs --}}
    <script>
        $(".fecha").change(function () {

            //valor del input
            let str = $(this).val();
            let arr = str.split('/');
            //alert(arr);

            //año actual
            let today = new Date();
            let year = today.getFullYear();
            //alert(today);

            if(arr[2] > year){
                toastr.warning("Tome en cuenta que acaba de ingresar una fecha que supera el año actual");
            }
            if(arr[2] < year){
                toastr.warning("Tome en cuenta que acaba de ingresar una anterior al año actual");
            }
        });
    </script>
@endsection
