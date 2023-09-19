@extends('plantilla.adminlte')

@section('titulo')
    Empresas
@endsection

@section('css')
        <!-- DataTables -->
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
    /*            width: 90%;
                margin: 0 auto;*/
            }
        </style>
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{--! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active"> <a href="/empresas">Empresas</a> </li>
                            <li class="breadcrumb-item active">Informacón</li>
                        </ol>
                    </div>
                </div>
                {{-- datos de la empresa --}}
                <div class="card">
                    <div class="card-header">
                        <div class="row ml-3">
                            <h2 >
                                <a href="/empresas/{{ $empresa->id }}/edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <b>{{ $empresa->denominacionSocial }}</b>
                            </h2>
                        </div>
                        <div class="row ml-3" style="margin-top:0">
                            <h2 > <b>NIT: {{ $empresa->nit }}</b></h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 ml-3">
                            <div class="col-sm-7">
                                <p style="margin:0">
                                    Actividad:
                                    <b>
                                        {{ $empresa->actividad }}
                                    </b>
                                </p>

                                <p style="margin:0">
                                    Clasificacion:
                                    <b>
                                        {{ $empresa->clasificacion }}
                                    </b>
                                </p>

                                <p style="margin:0">Representante Legal:
                                    <b>
                                        {{ $empresa->representanteLegal }} - CI: {{ $empresa->ci}}-{{$empresa->complemento}} {{ $empresa->extension}}
                                    </b>
                                </p>

                                <p style="margin:0">Celular:
                                    <b>
                                        {{ $empresa->celular}}
                                        <a href="https://wa.me/591{{$empresa->celular}}?text={{$whatsaap}}" target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </b>
                                </p>

                                <p style="margin:0">Correo electrónico:
                                    <b>
                                        {{ $empresa->correo }}
                                        <a href="mailto:{{$empresa->correo}}" target="_blank">
                                            <i class="far fa-envelope"></i>
                                        </a>
                                    </b>
                                </p>
                            </div>
                            {{-- ********** ARCHIVOS --}}
                            <div class="col-sm-5">
                                <p style="margin:0">
                                    @if ($empresa->rutaNit <> "")
                                    NIT:
                                    <a href="{{ asset('storage/empresas/nit')."/".$empresa->rutaNit}}" target="_blank">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                    @endif
                                </p>

                                <p style="margin:0">
                                    @if ($empresa->rutaCertInscripcion <> "")
                                    Cert. de Inscripción:
                                    <a href="{{ asset('storage/empresas/cert')."/".$empresa->rutaCertInscripcion}}" target="_blank">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                    @endif
                                </p>

                                <p style="margin:0">
                                    @if ($empresa->rutaMatricula <> "")
                                    Matrícula de Comercio:
                                    <a href="{{ asset('storage/empresas/matr')."/".$empresa->rutaMatricula}}" target="_blank">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                    @endif
                                </p>

                                <p style="margin:0">
                                    @if ($empresa->rutaRoe <> "")
                                    ROE:
                                    <a href="{{ asset('storage/empresas/roe')."/".$empresa->rutaRoe}}" target="_blank">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                    @endif
                                </p>
                            </div>
                        </div><!-- /.row -->
                    </div>
                </div>
                {{-- Fin datos de la empresa --}}

            </div><!-- /.container-fluid -->
        </div>
        {{--! Encabezado --}}

        {{--! Contenido --}}
        <section class="content">
            <div class="container-fluid">

                {{-- ! DataTable --}}
                <div class="row">
                    {{-- * Sucursales card-outline --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sucursales</h3>
                                <br>
                                <a class="btn btn-info" href="/sucursales?id_denominacionSocial={{$empresa->id}}" target="_blank" role="button">
                                    <i class="fas fa-store-alt"></i> Gestionar Sucursales</a>
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
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tablaSucursales" data-page-length='5' class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <td>Nro</td>
                                            <td>Sucursal</td>
                                            <td>Dirección</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php // declaramos la variable, no la imprimimos aun
                                        $numero = 0;
                                        @endphp
                                        @if ($sucursalesEncontradas->count() == null)
                                        <tr>
                                                <td></td>
                                                <td style="text-align: center">No se encontraron datos</td>
                                                <td></td>
                                        </tr>
                                        @else
                                            @foreach ($sucursalesEncontradas as $sucursal)
                                                <tr>
                                                    <td>{{$numero = $numero + 1}}</td>
                                                    <td>{{ $sucursal->descripcion }}</td>
                                                    <td>{{ $sucursal->direccion }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    {{-- * Fin Sucursales --}}

                    {{-- * Ejercicios --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ejercicios Contables</h3>
                                <br>
                                <a class="btn btn-info" href="/ejercicios?id_denominacionSocial={{$empresa->id}}" target="_blank" role="button">
                                    <i class="fas fa-file-invoice-dollar"></i> Gestionar Ejercicios Contables</a>
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
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tablaEjercicios" data-page-length='5' class="table table-bordered table-striped" style="width:100%" >
                                    <thead>
                                        <tr>
                                            <td>Estado</td>
                                            <td>Nro</td>
                                            <td>Ejercicio</td>
                                            <td>Inicio</td>
                                            <td>Cierre</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($ejerciciosEncontrados->count() == null)
                                            <tr>
                                                <td></td>
                                                <td {{-- colspan="5" --}} style="text-align: center">No se encontraron datos</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @else
                                            @php // declaramos la variable, no la imprimimos aun
                                            $numero=0;
                                            @endphp
                                            {{-- mostramos ejercicios cuando existen --}}
                                            @foreach ( $ejerciciosEncontrados as $ejercicio )
                                                <tr>
                                                    {{-- Estado --}}
                                                    @if ($ejercicio->id == Auth::user()->idEjercicioActivo)
                                                        <td style="text-align: center">
                                                            <div class=".btn-group btn-group-sm">
                                                                <a role="button" href="#" class="btn btn-success">
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
                                                                    <input type="text" name="empresaNueva" hidden readonly value="{{$empresa->id}}">
                                                                    <input type="text" name="ejercicioNuevo" hidden readonly value="{{$ejercicio->id}}">
                                                                    <button type="submit" class="btn btn-warning">
                                                                        <i class="fas fa-toggle-off"></i> Inactivo
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </form>
                                                    @endif
                                                    <td>{{$numero=$numero + 1}}</td>
                                                    <td>{{$ejercicio->ejercicioFiscal}}</td>
                                                    <td>
                                                        @php
                                                        $fi = explode('-',$ejercicio->fechaInicio);
                                                        $fi2 = $fi[2]."/".$fi[1]."/".$fi[0];
                                                        @endphp

                                                        {{$fi2}}
                                                    </td>
                                                    <td>
                                                        @php
                                                        $ff = explode('-',$ejercicio->fechaCierre);
                                                        $ff2 = $ff[2]."/".$ff[1]."/".$ff[0];
                                                        @endphp
                                                        {{$ff2}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    {{-- * Fin Ejercicios --}}
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

    {{-- !     file --}}
    <script src="{{ asset('/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>

    {{-- ! mascara --}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/custom-code/input-mask/input-mask-init.js') }}"></script>

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

    {{-- ! DATATABLE --}}
    <script>
        $(function()
        {
            $("#tablaSucursales").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "searching": false,
                "language": {
                    "zeroRecords": "No tiene información",
                    /* "info": "Página _PAGE_ de _PAGES_", */
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty": "No se encontraron datos",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': '>>',
                        'previous': '<<'
                    },
                },
                /* "scrollCollapse": true,
                "scrollY": 200,
                "scrollX": true, */
            }).buttons().container().appendTo('#tablaSucursales_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(function()
        {
            $("#tablaEjercicios").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "searching": false,
                "aaSorting": [],//desabilitamos el orden automatico
                "language": {
                    "zeroRecords": "No tiene información",
                    /* "info": "Página _PAGE_ de _PAGES_", */
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty": "No se encontraron datos",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': '>>',
                        'previous': '<<'
                    },
                },
                /* "scrollCollapse": true,
                "scrollY": 200,
                "scrollX": true, */
            }).buttons().container().appendTo('#tablaEjercicios_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
