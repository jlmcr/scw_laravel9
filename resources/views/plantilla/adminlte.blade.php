<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('titulo') | Contabilidad en la Nube </title>

    @yield('css')

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">

    {{--! Select2 --}}
    {{--! para usar select2 el id de seect deben ser distintos ids --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.css') }}">

    <style>
        *::selection {
            background: yellow;
            color: #000814;
        }
    </style>

</head>

{{-- * Aqui se modifica los fixes de los menus sidebar-collapse --}}

<body id="body" class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed
@if ( Auth::user()->colapsar_aside == 1)
    sidebar-collapse
@endif">
    @if (Auth::user()->acceso == 'Permitido')
        {{--! acceso permitido --}}
        <div class="wrapper">
            {{-- PRELOADER --}}
            @yield('preloader')

            <!-- NAVBAR -->
            @include('plantilla.navbar')
            <!-- ASIDEBAR -->
            @include('plantilla.asidebar')
            @yield('contenido')
            <!-- FOOTER -->
            <div class="fondoPersonalizado" >
                <br><br><br><br>
            </div>
            @include('plantilla.footer')

            {{-- ! Menu secundario --}}
            <aside class="control-sidebar control-sidebar-light ">
                <div class="p-4">
                    <hr>

                        Empresa activa:
                        {{-- ! EMPRESA ACTIVA --}}
                        {{-- lo utilizamos cuando exista . para el caso de edicion de perfil de usuario --}}

                        @isset($empresas)
                            {{--! Empresas --}}
                            @foreach ($empresas as $empr)
                                @if ($empr->id == Auth::user()->idEmpresaActiva)
                                    @if ($empr->estado == 1)
                                            <li class="nav-item d-none d-sm-inline-block">
                                                <a href="/empresas/{{$empr->id}}" class="nav-link pr-1">{{ $empr->denominacionSocial }}</a>
                                            </li>

                                            {{--! Ejercicios --}}
                                            @foreach ($ejercicios as $ejer)
                                                @if ($ejer->id == Auth::user()->idEjercicioActivo)
                                                    @if ($ejer->estado == 1)
                                                        <li class="nav-item d-none d-sm-inline-block">
                                                            <a href="/ejercicios?id_denominacionSocial={{Auth::user()->idEmpresaActiva}}" class="nav-link pl-0">
                                                                Contabilidad:{{ $ejer->ejercicioFiscal }}
                                                            </a>
                                                        </li>
                                                        @else
                                                        <li class="nav-item d-none d-sm-inline-block">
                                                            <a href="/empresas/{{$empr->id }}" class="nav-link pr-1">Seleccione Ejercicio</a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                            {{--* Fin Ejercicios --}}

                                            @else
                                            <li class="nav-item d-none d-sm-inline-block">
                                                <a href="/empresas" class="nav-link pr-1">Selecciones una Empresa</a>
                                            </li>
                                    @endif
                                @endif
                            @endforeach
                            {{--* Fin Empresas --}}
                        @endisset

                    <hr>

                    {{-- ! hr dibuja una linea
                    ! br saldo de linea --}}

                        <p class="text-bold text-center text-red p-0 m-0">
                            Configuraciones:
                        </p>
                        <p class="p-0 m-2">
                            Preferencias del Usuario:
                            <br>
                            @php
                                if (Auth::user()->mostrarBajas == 1)
                                {
                                    $mostrarBajas = " Mostrar";
                                }
                                else {
                                    $mostrarBajas = " Ocultar";
                                }

                            @endphp

                            Bajas:{{$mostrarBajas}}
                        </p>
                        <div>
                            <a href="{{route('preferencias-usuario.index')}}" target="_blank"
                                class="btn btn-primary" style="width: 100%; align-content: center">
                                Preferencias
                            </a>
                        </div>

                        <br>
                        <!--  Switch Dark -->
                        {{-- <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="switchModo">
                            <label class="custom-control-label" for="switchModo">modo dark</label>
                        </div> --}}
                    <hr>


                </div>
            </aside>
            {{-- ! Fin Menu secundario --}}
            
            {{--! modal mayor --}}
            <div class="modal fade" id="modal-mayor-analitico">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Generar Mayor Analítico de la Sub-Cuenta</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            @isset($sub_cuentas)
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label>Nombre:</label>
                                    <select id="codigo_subcuenta_modal_mayor" class="form-control select2">
                                        <option value=""></option>
                                        @foreach ($sub_cuentas as $sub_cuenta)
                                            <option value="{{$sub_cuenta->id}}">
                                                {{$sub_cuenta->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Del:</label>
                                    <input id="fecha_inicio_modal_mayor" type="date" class="form-control">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Al:</label>
                                    <input id="fecha_fin_modal_mayor" type="date" class="form-control">
                                </div>
                            </div>
                            @endisset
                        </div>

                        <div class="modal-footer justify-content-between">
                            <a href="" id="generar-modal-mayor" target="_blank" class="btn btn-primary col-md-3">Generar</a>
                            <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            {{--! Fin modal mayor --}}

        </div>
    @else
        {{--! acceso denegado --}}
        <div class="wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <ol class="float-sm-right">
                                    <li class="list-unstyled">{{Auth::user()->name}}</li>
                                    <li class="list-unstyled">
                                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item p-0">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                                            </button>
                                        </form>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="error-page">
                        <h2 class="headline text-danger">Denegado</h2>

                        <div class="error-content">
                            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Uups! No tiene acceso al Sistema.</h3>

                            <p>
                                Comuniquese con el administrador.
                            </p>
                        </div>
                        <p>
                            Si considera que se trata de un error comuníquese con el desarrollador +591 68087958
                            <a href="https://api.whatsapp.com/send/?phone=59168087958&text=Hola,+le+escribo+por+un+inconveniente+en+el+Sistema+Web+de+Contabilidad+2022.+Me+muestra+una+pantalla+indicando+Acceso+Denegado,+al+iniciar+sesi%C3%B3n.+Gracias.&type=phone_number&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        </p>
                    </div>
                    <!-- /.error-page -->

                </section>
                <!-- /.content -->
        </div>
    @endif


    {{-- <script>
        function mododark() {
            document.getElementById("body").classList.toggle("dark-mode");
            document.getElementById("navbar").classList.toggle("dark-mode");
            document.getElementById("main-sidebar").classList.toggle("dark-mode");
        }

        /* relacionamos la funcion anterior con el control */
        document.getElementById("switchModo").onclick = function() {
            mododark();
        }
    </script> --}}


    {{-- ! JAVASCRIP --}}

    <!-- jQuery -->
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>

    {{-- ! alerta SWEETALERT 2 --}}
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}
    <!-- SweetAlert2 -->
    <script src="{{ asset('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    {{-- ! <!-- Toastr --> --}}
    <script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

    {{-- SWEETALERT 2 --}}
    {{--! Error cuando no se tiene acceso --}}
    @if (Session('acceso')=='denegado')
        <script>
            toastr.error('No tiene acceso, comuníquese con el administrador.');
        </script>
    @endif

    {{--! MENSAJES EXCEL --}}
    @if (Session('generar_excel')=='error')
    <script>
        toastr.error('Inténtelo nuevamente.');
    </script>
    @endif
    
    @if (Session('generar_excel')=='sin_datos')
    <script>
        toastr.warning ('No tiene datos.');
    </script>
    @endif
    
    {{--! CAMBIO DE ESTADO DEL MENU LATERAL --}}
    {{--! AJAX --}}
    <script>
        $(".pushmenu").click(function () {
            //alert("esramos dentro");

            $.ajax({
                url: "{{route('preferencias-usuario.aside')}}",
                type: "GET",
                data: "cambiar:cambiar",
                success: function (response) {
                    //console.log(response);
                }
            });
        });
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! Modal MAyor --}}
    <script>
        $("#codigo_subcuenta_modal_mayor, #fecha_inicio_modal_mayor, #fecha_fin_modal_mayor").change(function () { 
            var idsubcuenta = $("#codigo_subcuenta_modal_mayor").val();
            var fecha_i = $("#fecha_inicio_modal_mayor").val();
            var fecha_f = $("#fecha_fin_modal_mayor").val();

            var ruta = "/contabilidad/pdf-mayor-analitico?id="+idsubcuenta+"&fechaInicio_buscado="+fecha_i+"&fechaFin_buscado="+fecha_f;
            $("#generar-modal-mayor").attr("href",ruta);
        });
    </script>

    @yield('js')

</body>

</html>
