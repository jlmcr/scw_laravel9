@extends('plantilla.adminlte')

@section('titulo')
    Categorías A.F.
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
                        <h1 class="m-0">
                            <a href="/rubrosActivoFijo">
                                Categorías del Activo Fijo
                            </a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item"><a href="/activoFijo">Activo Fijo</a></li>
                            <li class="breadcrumb-item active">Categorías-Rubros</li>
                        </ol>
                    </div><!-- /.col -->
                </div>
            </div>
        </div>
        {{-- ! Fin Encabezado --}}

        {{--! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{-- ! DataTable --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            {{--! inicio tarjeta --}}
                            <div class="card-header row">
                                <div class="col-sm-9 align-middle">
                                    <h3>Listado de Categorías de Activo Fijo</h3>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" role="button" class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-rubro">
                                        <i class="fas fa-plus"></i>
                                        Nueva Categoría
                                    </button>
                                </div>
                            </div>

                            {{--! tabla --}}
                            <div class="card-body p-2">
                                <table id="tablaRubros" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Categoría</th>
                                            <th>Años de vida útil</th>
                                            <th>Cuenta Activo</th>
                                            <th>Cuenta Depreciación</th>
                                            <th>Cuenta Depreciación Acumulada</th>
                                            <th>Sujeto a depreciación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rubros as $rubro)
                                            <tr>
                                                <td>{{$rubro->id}}</td>
                                                <td>{{ $rubro->rubro }}</td>
                                                <td style="text-align: center">{{ $rubro->aniosVidaUtil }}</td>
                                                {{-- cuenta activo --}}
                                                <td>
                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                        @if ($rubro->codCntaActivo == $sub_cuenta->codigo)
                                                            {{$sub_cuenta->descripcion}}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                {{-- cuenta dereciacion --}}
                                                <td>
                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                        @if ($rubro->codCntaDepreciacion == $sub_cuenta->codigo)
                                                            {{$sub_cuenta->descripcion}}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                {{-- cuenta dereciacion acumulada--}}
                                                <td>
                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                        @if ($rubro->codCntaDepreciacionAcum == $sub_cuenta->codigo)
                                                            {{$sub_cuenta->descripcion}}
                                                        @endif
                                                    @endforeach
                                                </td>

                                                @if ($rubro->sujetoAdepreciacion == 1)
                                                    <td style="text-align: center">
                                                        <span class="badge bg-cyan">- SI -</span>
                                                    </td>
                                                    @else
                                                    <td style="text-align: center">
                                                        <span class="badge bg-red">- NO -</span>
                                                    </td>
                                                @endif

                                                {{-- botones --}}
                                                <td style="text-align: center">
                                                    <form  action="{{route ('rubrosActivoFijo.destroy',$rubro->id)}}" method="POST" class="frmEliminar-Rubro">
                                                        @csrf
                                                        @method('DELETE')

                                                        <div class="btn-group btn-group">
                                                            <a role="button" class="btn btn-outline-info btn-xs"
                                                                data-toggle="modal" data-target="#modal-editar-rubro{{$rubro->id}}">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            @if ($rubro->cantidad_activos_registrados == 0)
                                                                <button type="submit" class="btn btn-outline-danger btn-xs">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                                @else
                                                                <button disabled class="btn btn-outline-danger btn-xs">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- ! Fin DataTable --}}

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

            {{--! modal Crear rubro--}}
            <div class="modal fade" id="modal-crear-rubro">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Categoría/Rubro de Activo Fijo (DS 24051)</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form method="POST" action="/rubrosActivoFijo" class="frmCrear-Rubro" >
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-sm-8">
                                        <label class="form-label" for="rubro">Nombre de Categoría/Rubro</label>
                                        <input type="text" class="form-control text-uppercase" name="rubro" id="rubro" autocomplete="off" maxlength="100" title="Nombre de Categoría o Rubro | máximo 100 carácteres" required>
                                        <small class="text-muted">Puedes usar la descripción del D.S. 24051</small>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="form-label" for="aniosVidaUtil">Años de vida útil</label>
                                        <input type="text" name="aniosVidaUtil" id="aniosVidaUtil" maxlength="2" class="form-control aniosVida" autocomplete="off" title="mínimo 0 años de vida | máximo 50 años de vida" required>
                                        <small class="text-muted">Puedes tomar como referencia el D.S. 24051</small>
                                    </div>
                                </div>

                                <div class="row">
                                    {{--* select crear--}}
                                    <div class="form-group col-sm-6">
                                        <label>Cuenta de Activo</label>
                                        <select name="codCntaActivo" class="form-control select2">
                                            <option value=""></option>
                                            @foreach ($sub_cuentas as $sub_cuenta)
                                                <option value="{{$sub_cuenta->codigo}}">
                                                    {{$sub_cuenta->descripcion}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>
                                            Cuenta de Depreciación Acumulada
                                        </label>
                                        <select name="codCntaDepreciacionAcum" class="form-control select2">
                                            <option value=""></option>
                                            @foreach ($sub_cuentas as $sub_cuenta)
                                                <option value="{{$sub_cuenta->codigo}}">{{$sub_cuenta->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Cuenta de Depreciación</label>
                                        <select name="codCntaDepreciacion" class="form-control select2">
                                            <option value=""></option>
                                            @foreach ($sub_cuentas as $sub_cuenta)
                                                <option value="{{$sub_cuenta->codigo}}">{{$sub_cuenta->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="sujetoAdepreciacion">Sujeto a Depreciación</label>
                                        <input type="checkbox" name="sujetoAdepreciacion" id="sujetoAdepreciacion">
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
            {{--! Fin modal Crear rubro--}}

            {{--! modal Editar rubro--}}
            @foreach ($rubros as $rubro)
                <div class="modal fade" id="modal-editar-rubro{{$rubro->id}}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Editar Categoría/Rubro - ID:{{$rubro->id}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="/rubrosActivoFijo/{{$rubro->id}}" class="frmEditar-Rubro" >
                                @csrf
                                @method('put')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-sm-8">
                                            <label class="form-label" for="rubro">Nombre de Categoría/Rubro</label>
                                            <input type="text" class="form-control text-uppercase" name="rubro" id="rubro" autocomplete="off" maxlength="100" title="Nombre de Categoría o Rubro | máximo 100 carácteres" value="{{$rubro->rubro}}" required>
                                            <small class="text-muted">Puedes usar la descripción del D.S. 24051</small>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label" for="aniosVidaUtil">Años de vida útil</label>
                                            <input type="text" name="aniosVidaUtil" id="aniosVidaUtil" maxlength="2" class="form-control aniosVida" autocomplete="off"  title="mínimo 0 años de vida | máximo 50 años de vida" value="{{$rubro->aniosVidaUtil}}" required>
                                            <small class="text-muted">Puedes tomar como referencia el D.S. 24051</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{--* select editar--}}
                                        <div class="form-group col-sm-6">
                                            <label>Cuenta de Activo</label>
                                            <select name="codCntaActivo" class="form-control select2">
                                                <option value=""></option>
                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                    @if ($sub_cuenta->codigo == $rubro->codCntaActivo)
                                                        <option value="{{$sub_cuenta->codigo}}" selected>
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                        @else
                                                        <option value="{{$sub_cuenta->codigo}}">
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>
                                                Cuenta de Depreciación Acumulada
                                            </label>
                                            <select name="codCntaDepreciacionAcum" class="form-control select2">
                                                <option value=""></option>
                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                    @if ($sub_cuenta->codigo == $rubro->codCntaActivo)
                                                        <option value="{{$sub_cuenta->codigo}}" selected>
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                        @else
                                                        <option value="{{$sub_cuenta->codigo}}">
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label>Cuenta de Depreciación</label>
                                            <select name="codCntaDepreciacion" class="form-control select2">
                                                <option value=""></option>
                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                    @if ($sub_cuenta->codigo == $rubro->codCntaActivo)
                                                        <option value="{{$sub_cuenta->codigo}}" selected>
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                        @else
                                                        <option value="{{$sub_cuenta->codigo}}">
                                                            {{$sub_cuenta->descripcion}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="sujetoAdepreciacion">Sujeto a Depreciación</label>

                                            @if ($rubro->sujetoAdepreciacion == 1)
                                                <input type="checkbox" checked name="sujetoAdepreciacion" id="sujetoAdepreciacion">
                                                @else
                                                <input type="checkbox" name="sujetoAdepreciacion" id="sujetoAdepreciacion">
                                            @endif
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
            {{--! Fin modal Editar rubro--}}
        </section>
        {{--! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuRubro').addClass('active');
    </script>

    {{--! este mensaje es recibido al CREAR NUEVO RUBRO --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Categoría creado exitosamente.')
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR RUBRO --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.')
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR RUBRO --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Categoría eliminado exitosamente.')
    </script>
    @endif

    {{--! Pregunta desea CREAR RUBRO--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Rubro').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear el Categoría?',
                text: "¡Creará una nueva Categoría para el Activo Fijo!",
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
            $('.frmCrear-Rubro').submit(function(e){
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
            $('.frmEditar-Rubro').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Categoría?',
                text: "¡Actualizará el Categoría de Activo Fijo!",
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
            $('.frmEditar-Rubro').submit(function(e){
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


    {{--! Pregunta Eliminar RUBRO --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-Rubro').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea Eliminar la Categoría?',
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
            $('.frmEliminar-Rubro').submit(function(e){
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
            $("#tablaRubros").DataTable({
                "responsive": false,
                "lengthChange": true,//cambio de los items que se veran
                "autoWidth": false,
                 /* "dom": 'lrtip' quita el buscador https://datatables.net/reference/option/dom */
                "aaSorting": [],//desabilitamos el orden automatico

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
                "scrollY": '350px',
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns":
                {
                    "left": false,
                    "right": 1,
                }
            }).buttons().container().appendTo('#tablaRubros_wrapper .col-md-6:eq(0)');
        });
    </script>
    {{--! solo numeros en años de vida util --}}
    <script>
        $(".aniosVida").on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, '')); //reemplazamos digitos que no sean del solo de 0 al 9
        });
        $(".aniosVida").change(function () {
            if($(this).val() > 50){
                toastr.error("Los años de vida util no pueden superior a 50 años");
                $(this).val(0);
            }
        });
        $(".aniosVida").change(function () {
            if($(this).val() < 0){
                toastr.error("Los años de vida util no pueden inferior a 0 años");
                $(this).val(0);
            }
        });
    </script>
@endsection
