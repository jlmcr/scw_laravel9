@extends('plantilla.adminlte')

@section('titulo')
    Tipos
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><a href="{{route('tipo-comprobante.index')}}">Tipos de Comprobantes Contables</a></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Tipos de Comrpobantes</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-tipo">
                            <i class="fas fa-plus"></i>
                            Nuevo
                        </button>
                    </div>
                </div>
                <br>
                {{-- ! DataTable --}}
                    @if (isset($tiposComprobantes))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-dark">

                                    {{--! inicio tarjeta --}}
                                    <div class="card-header">
                                        <h3 class="card-title">Tipos de Comprobantes en Sistema</h3>
                                    </div>

                                    {{--! tabla --}}
                                    <div class="card-body table-responsive p-2">
                                        <table id="tablaTipos" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Color</th>
                                                    <th>Cantidad Total</th>
                                                    <th>Acciones</th>
                                                    @if (Auth::user()->mostrarBajas == 1)
                                                        <th>Alta/Baja</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tiposComprobantes as $tipo)
                                                    <tr>
                                                        <td>{{$tipo->id}}</td>
                                                        <td>{{ $tipo->nombre }}</td>
                                                        <td class="text-center">
                                                            <span class="{{$tipo->color}} badge p-2 pr-5">
                                                            </span>
                                                        </td>
                                                        <td>{{ $tipo->cantidad }} Comprobantes</td>
                                                        {{-- botones --}}
                                                        <td style="text-align: center">
                                                            @if ($tipo->estado == 1)
                                                                {{-- muestra botones solo si estan con alta --}}
                                                                <form  action="{{route ('tipo-comprobante.destroy',$tipo->id)}}" method="POST" class="frmEliminar-Tipo">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <div class="btn-group btn-group-sm">
                                                                        <a role="button" class="btn btn-info"
                                                                            data-toggle="modal" data-target="#modal-editar-tipo{{$tipo->id}}">
                                                                            <i class="fas fa-pen"></i>
                                                                        </a>
                                                                        @if ($tipo->cantidad ==  0)
                                                                            {{-- se puede eliminar --}}
                                                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                                            @else
                                                                            {{-- no se puede eliminar --}}
                                                                            <button type="submit" class="btn btn-danger disabled" disabled><i class="fas fa-trash-alt"></i></button>
                                                                        @endif
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        </td>
                                                        {{-- Alta Baja --}}
                                                        @if (Auth::user()->mostrarBajas == 1)
                                                            @if ($tipo->estado == 1)
                                                                <td style="text-align: center">
                                                                    <div class=".btn-group btn-group-sm">
                                                                        <a role="button" class="btn btn-success">
                                                                            Alta
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <form method="POST" action="{{ route('tipo-comprobante.update',$tipo->id) }}" class="frmTipo-AltaBaja">
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
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                {{-- ! Fin DataTable--}}
            </div>

            {{--! modal crear tipo comprobante --}}
            <div class="modal fade" id="modal-crear-tipo">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Tipo de Comprobante</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('tipo-comprobante.store')}}" class="frmCrear-Tipo" >
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-9 form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input name="nombre" id="nombre" type="text" class="form-control text-uppercase"
                                        autocomplete="off" maxlength="16" required>
                                    </div>

                                    <div class="col-sm-3 form-group">
                                        <label for="color">Color:</label>
                                        <select name="color" id="color" class="form-control" required>
                                            <option value=""></option>
                                            <option value="bg-lime" class="bg-lime" >Verde Claro</option>
                                            <option value="bg-fuchsia" class="bg-fuchsia" >Fucsia</option>
                                            <option value="bg-blue" class="bg-blue" >Azul</option>
                                            <option value="bg-gray" class="bg-gray" >Gris</option>
                                            <option value="bg-maroon" class="bg-maroon" >Marron</option>
                                            <option value="bg-white"" class="bg-white" >Blanco</option>
                                            <option value="bg-dark" class="bg-dark" >Del sistema</option>
                                        </select>
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
            {{--! Fin modal crear tipo comprobante --}}

            {{-- ! modal de Editar --}}
            @foreach ($tiposComprobantes as $tipo)
                <div class="modal fade" id="modal-editar-tipo{{$tipo->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Editar Tipo de Comprobante</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('tipo-comprobante.update',$tipo->id)}}" class="frmEditar-Tipo" >
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="validador" value="actualizar" >

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-8 form-group">
                                            <label for="nombre">Nombre:</label>
                                            <input name="nombre" id="nombre" type="text" class="form-control text-uppercase"
                                            autocomplete="off" maxlength="16" required value="{{$tipo->nombre}}">
                                        </div>

                                        <div class="col-sm-3 form-group">
                                            <label for="color">Color:</label>
                                            <select name="color" id="color" class="form-control" required>
                                                <option class="{{$tipo->color}}" value="{{$tipo->color}}"></option>
                                                <option value=""></option>
                                                <option value="bg-lime" class="bg-lime" >Verde Claro</option>
                                                <option value="bg-fuchsia" class="bg-fuchsia" >Fucsia</option>
                                                <option value="bg-blue" class="bg-blue" >Azul</option>
                                                <option value="bg-gray" class="bg-gray" >Gris</option>
                                                <option value="bg-maroon" class="bg-maroon" >Marron</option>
                                                <option value="bg-white"" class="bg-white" >Blanco</option>
                                                <option value="bg-dark" class="bg-dark" >Del sistema</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <label for=""></label>
                                            <span class="badge mt-2 p-2 {{$tipo->color}}">actual</span>
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
            {{-- ! Fin modal de Editar --}}


        </section>
        {{-- ! Fin Contenido --}}

    </div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuConfiguraciones').addClass('menu-open');
        $('#menuConfiguraciones_').addClass('active');
        $('#subMenuTipos').addClass('active');
    </script>

    {{--! este mensaje es recibido al CREAR NUEVA Tipo de Comprobante --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Tipo de Comprobante creado exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR Tipo de Comprobante --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.info('Cambios realizados con éxito.');
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR Tipo de Comprobante --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.warning('Tipo de Comprobante dado de baja exitosamente.');
    </script>
    @endif

    {{--! Pregunta desea CREAR Tipo de Comprobante--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Tipo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear el Tipo de Comprobante?',
                text: "¡Creará una nuevo Tipo de Comprobante!",
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
            $('.frmCrear-Tipo').submit(function(e){
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

    {{--! Pregunta desea EDITAR Tipo de Comprobante--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Tipo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Tipo de Comprobante?',
                text: "¡Actualizará el Tipo de Comprobante!",
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
            $('.frmEditar-Tipo').submit(function(e){
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
            $('.frmTipo-AltaBaja').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de Alta al Tipo de Comprobante?',
                text: "¡El estado actual del Tipo de Comprobante es de Baja! ¿Desea recuperarlo?",
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
            $('.frmTipo-AltaBaja').submit(function(e){
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

    {{--! Pregunta Eliminar Tipo de Comprobante --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-Tipo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de baja el Tipo de Comprobante?',
                text: "¡Dará de baja el item!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, dar de baja',
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
            $('.frmEliminar-Tipo').submit(function(e){
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

    {{--! DATATABLE --}}

    <script>
        $(function () {
            $("#tablaTipos").DataTable({
                "responsive": true,
                "lengthChange": false,//cambio de los items que se veran
                "autoWidth": false,
                //"aaSorting": [],//desabilitamos el orden automatico

                /* "dom": 'lrtip' quita el buscador*/

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
            }).buttons().container().appendTo('#tablaTipo_wrapper .col-md-6:eq(0)');
        });
    </script>




@endsection
