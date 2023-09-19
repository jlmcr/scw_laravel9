@extends('plantilla.adminlte')

@section('titulo')
    Sucursales
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
{{--     <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><a href="/sucursales">Sucursales</a></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item"><a href="/empresas">Empresas</a></li>
                            <li class="breadcrumb-item active">Sucursales</li>
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
                <form method="GET" action="{{ route('sucursales.index') }}">
                    {{-- @csrf --}}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {{--* input denominacion social --}}
                                <label>Denominación de la Empresa</label>
                                <select name="id_denominacionSocial" id="id_denominacionSocial" class="form-control select2" style="width: 100%;">

                                    <option value=""></option>

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
                            <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-sucursal">
                                <i class="fas fa-plus"></i>
                                Nueva Sucursal
                            </button>
                        </div>

                    </div>
                </form>
                {{--* Fin Buscador --}}

                {{-- ! DataTable de sucursales--}}
                @if ($empresaBuscada != "")
                    @if (isset($sucursalesEncontradas))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-dark">

                                    {{--! inicio tarjeta --}}
                                    <div class="card-header">
                                        @if(isset($empresaBuscada))
                                        <h3 class="card-title">EMPRESA: {{ $empresaBuscada->denominacionSocial }}</h3>
                                        <br> {{-- salto de linea --}}
                                        <h3 class="card-title">NIT: {{ $empresaBuscada->nit }}</h3>
                                        @endif
                                    </div>

                                    {{--! tabla --}}
                                    <div class="card-body table-responsive p-2">
                                        <table id="tablaSucursales" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nro</th>
                                                    <th>Sucursal</th>
                                                    <th>Dirección</th>
                                                    <th>Acciones</th>
                                                    @if (Auth::user()->mostrarBajas == 1)
                                                        <th>Alta/Baja</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php // declaramos la variable, no la imprimimos aun
                                                    $numero = 0;
                                                @endphp
                                                @foreach ($sucursalesEncontradas as $sucursal)
                                                    <tr>
                                                        <td>{{ $numero = $numero + 1 }}</td>
                                                        {{-- <td>{{$sucursal->id}}</td> --}}
                                                        <td>{{ $sucursal->descripcion }}</td>
                                                        <td>{{ $sucursal->direccion }}</td>
                                                        {{-- botones --}}
                                                        <td style="text-align: center">
                                                            @if ($sucursal->estado == 1)
                                                                {{-- muestra botones solo si estan con alta --}}
                                                                <form  action="{{route ('sucursales.destroy',$sucursal->id)}}" method="POST" class="frmEliminar-sucursal">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <div class="btn-group btn-group-sm">
                                                                        <a role="button" class="btn btn-outline-info"
                                                                            data-toggle="modal" data-target="#modal-editar-sucursal{{$sucursal->id}}">
                                                                            <i class="fas fa-pen"></i>
                                                                        </a>
                                                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        </td>
                                                        {{-- Alta Baja --}}
                                                        @if (Auth::user()->mostrarBajas == 1)
                                                            @if ($sucursal->estado == 1)
                                                                <td style="text-align: center">
                                                                    <div class=".btn-group btn-group-sm">
                                                                        <a role="button" class="btn btn-success">
                                                                            Alta
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <form method="POST" action="{{ route('sucursales.update',$sucursal->id) }}" class="frmSucursal-AltaBaja">
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
                                            {!!$sucursalesEncontradas->links()!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {{-- ! Fin DataTable de sucursales--}}
            </div>

            {{--! modal crear sucursal --}}
            <div class="modal fade" id="modal-crear-sucursal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Sucursal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="/sucursales" class="frmCrear-Sucursal" >
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
                                    <label for="nombre">Nombre Sucursal</label>
                                    <input name="nombre" id="nombre" type="text" class="form-control"
                                    placeholder="Ejemplo: Sucursal Nro. 1" autocomplete="off"  maxlength="50"
                                    title="Nombre o Número de la sucursal | máximo 50 carácteres">
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <textarea name="direccion" id="direccion" class="form-control" autocomplete="off" maxlength="150"
                                    title="Dirección de la sucursal | máximo 150 carácteres"></textarea>
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
            {{--! Fin modal crear sucursal --}}

            {{-- ! modal de Editar --}}
            @foreach ($sucursalesEncontradas as $sucursal)
                {{--* modal editar --}}
                <div class="modal fade" id="modal-editar-sucursal{{$sucursal->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Editar Sucursal</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" {{-- action="/sucursales/{{ $sucursal->id }}" --}}
                            action="{{ route('sucursales.update',$sucursal->id) }}" class="frmEditar-Sucursal" >
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
                                        <label for="nombre">Nombre Sucursal</label>
                                        <input name="nombre" id="nombre" type="text" class="form-control"
                                        placeholder="Ejemplo: Sucursal Nro. 1" autocomplete="off" required
                                        value="{{$sucursal->descripcion}}" maxlength="50"
                                        title="Nombre o Número de la sucursal | máximo 50 carácteres">
                                    </div>

                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <textarea name="direccion" id="direccion" class="form-control" autocomplete="off"
                                        maxlength="150" title="Dirección de la sucursal | máximo 150 carácteres">{{$sucursal->direccion}}</textarea>
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
                {{--* Fin modal editar --}}
            @endforeach
            {{-- ! Fin modal de Editar --}}

        </section>
        {{-- ! Fin Contenido --}}

    </div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuGestionarEmpresas').addClass('menu-open');
        $('#menuEmpresas').addClass('active');
        $('#submenuSucursales').addClass('active');
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! este mensaje es recibido al CREAR NUEVA SUCURSAL --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Sucursal creada exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR SUCURSAL --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.');
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR SUCURSAL --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Sucursal eliminada exitosamente.');
    </script>
    @endif

    {{--! Pregunta desea CREAR SUCURSAL--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Sucursal').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear la Sucursal?',
                text: "¡Creará una nueva Sucursal!",
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
            $('.frmCrear-Sucursal').submit(function(e){
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

    {{--! Pregunta desea EDITAR SUCURSAL--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Sucursal').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios de la Sucursal?',
                text: "¡Actualizará la Sucursal!",
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
            $('.frmEditar-Sucursal').submit(function(e){
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
            $('.frmSucursal-AltaBaja').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de Alta a la Sucursal?',
                text: "¡El estado actual de la Sucursal es de Baja o Eliminado! ¿Desea recuperarlo?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Recuperar',
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
            $('.frmSucursal-AltaBaja').submit(function(e){
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

    {{--! Pregunta Eliminar SUCURSAL --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-sucursal').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea Eliminar la Sucursal?',
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
            $('.frmEliminar-sucursal').submit(function(e){
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
{{--
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
--}}
    {{--! DATATABLE --}}
{{--
    <script>
        $(function () {
            $("#tablaSucursales").DataTable({
                "responsive": true,
                "lengthChange": false,//cambio de los items que se veran
                "autoWidth": false,
                "dom": 'lrtip',
                "aaSorting": [],//desabilitamos el orden automatico

                /* "dom": 'lrtip' quita el buscador
                https://datatables.net/reference/option/dom */
                "language":
                {
                    "zeroRecords": "No tiene información",
                    /* "info": "Página _PAGE_ de _PAGES_", */
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty": "No se encontraron datos",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search':'Buscar:',
                    'paginate':{
                        'next':'>>',
                        'previous':'<<'
                    },

                }
            }).buttons().container().appendTo('#tablaSucursales_wrapper .col-md-6:eq(0)');
        });
    </script>
--}}



@endsection
