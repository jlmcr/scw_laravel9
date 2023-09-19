@extends('plantilla.adminlte')

@section('titulo')
    Empresas
@endsection

@section('css')
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
            table.dataTable thead {
    /*             background: linear-gradient(to left, #43cea2, #185a9d);
                color:white; */
            }
            /* Para usar solo con scrollX */
            div.dataTables_wrapper {
    /*             width: 100%;
                margin: 0 auto; */
            }
    </style>
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{--! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><a href="/empresas">Empresas Registradas en el Sistema</a></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Empresas</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{--! Fin Encabezado --}}

        {{--! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--! Fila de Tarjetas --}}
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">

                                <h3>{{$cantEmpr}}</h3>
                                <p>Empresas en Sistema</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <a href="#" class="small-box-footer">Ver Más <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">

                                <h3>{{$cantSuc}}</h3>
                                <p>Sucursales en total</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <a href="/sucursales" class="small-box-footer">Ver Más <i
                                class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">

                                <h3>{{$cantEjer}}</h3>
                                <p>Ejercicios Contables</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <a href="/ejercicios" class="small-box-footer">Ver Más <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                {{--! Fin Fila de Tarjetas --}}

                {{--! boton de crear nuevo --}}
                <div class="row">
                    <div class="col-sm-12 col-md-7 col-lg-4 p-3">
                        <a class="btn btn-outline-info" href="/empresas/create" role="button" style="width: 100%">
                            Crear Nueva Empresa
                        </a>
                    </div>
                </div>
                {{--! Fin boton de crear nuevo --}}

                {{-- ! DataTable --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Empresas</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-2">
                                <table id="tablaEmpresas" data-page-length='5' class="table table-bordered table-striped display nowrap" style="width:100%" >
                                    <thead>
                                        <tr>
                                            @if (Auth::user()->mostrarBajas == 1)
                                                <td>Alta/Baja</td>
                                            @endif
                                            <td>Acciones</td>
                                            {{-- <td>Nro</td> --}}
                                            <td>Denominación Social</td>
                                            <td>NIT</td>
                                            <td>Actividad</td>
                                            <td>Clasificación D.S. 24051</td>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 15px">
                                        @php // declaramos la variable, no la imprimimos aun
                                            $numero = 0;
                                        @endphp
                                        @foreach ( $empresasLista as $empresa )
                                            <tr>
                                                {{-- Alta Baja --}}
                                                @if (Auth::user()->mostrarBajas == 1)
                                                    @if ($empresa->estado == 1)
                                                        <td style="text-align: center">
                                                            <div class=".btn-group btn-group-sm">
                                                                <a role="button" class="btn btn-success">
                                                                    Alta
                                                                </a>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <form method="POST" action="{{ route('empresas.update',$empresa->id) }}" class="frmEmpresa-AltaBaja">
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

                                                {{-- botones --}}
                                                <td>
                                                    @if ($empresa->estado == 1)
                                                        <form action="{{ route('empresas.destroy',$empresa->id) }}" method="POST" class="frmEliminar">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="btn-group btn-group-sm">
                                                                <a class="btn btn-outline-success" href="{{route('empresas.show',$empresa->id)}}" role="button"><i class="fas fa-eye"></i></a>
                                                                <a class="btn btn-outline-info" href="/empresas/{{$empresa->id}}/edit" role="button"><i class="fas fa-pen"></i></a>
                                                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </td>

                                                {{-- <td>{{$numero = $numero + 1}}</td> --}}
                                                <td>{{$empresa->denominacionSocial}}</td>
                                                <td>{{$empresa->nit}}</td>
                                                <td>{{$empresa->actividad}}</td>
                                                <td>{{$empresa->clasificacion}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                {{-- ! Fin DataTable --}}
            </div>
        </section>
        {{--! Fin Contenido --}}
    </div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuGestionarEmpresas').addClass('menu-open');
        $('#menuEmpresas').addClass('active');
        $('#submenuEmpresas').addClass('active');
    </script>

    {{--! este mensaje es recibido al CREAR CASA MATRIZ  Y AVISO DE CREAR EJERCICIO--}}
    @if (Session('crearMatriz')=='okMatriz')
    <script>
            toastr.success('Casa Matriz creada con éxito.');
            toastr.warning('Recuerde que: debe crear manualmente los ejercicios contables de la empresa que acaba de crear.');
    </script>
    @endif

    {{--! este mensaje es recibido al CREAR NUEVA EMPRESA --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Empresa creada exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR EMPRESA --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.');
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR EMPRESA --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Empresa eliminada exitosamente.');
    </script>
    @endif

    {{--! Pregunta Eliminar empresa --}}
    <script>
        $('.frmEliminar').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea dar de Baja a la Empresa?',
            text: "¡Dará de baja a la Empresa, no tendrá a los datos de la Empresa!",
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

    {{--! Pregunta desea DAR DE ALTA EJERCICIO--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEmpresa-AltaBaja').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de Alta a la Empresa?',
                text: "¡El estado actual de la Empresa es de Baja o Eliminado! ¿Desea recuperarlo?",
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
            $('.frmEmpresa-AltaBaja').submit(function(e){
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
            $("#tablaEmpresas").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["excel"],
                "language":
                {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "lengthMenu": "Mostrar " +
                    `<select class='form-control input-sm'>
                    <option value='5'>5</option>
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
/*                 "scrollCollapse": true,
                "scrollY": 300,
                "scrollX": true, */
            }).buttons().container().appendTo('#tablaEmpresas_wrapper .col-md-6:eq(0)');
        });
    </script>

@endsection


