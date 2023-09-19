<!-- Main Sidebar Container sidebar-light-navy  sidebar-dark-primary sidebar-light-warning  sidebar-dark-warning-->
{{-- main-sidebar elevation-4 sidebar-light-teal sidebar-light-maroon--}}

{{-- sidebar-dark-white sidebar-light-blue sidebar-light-warning sidebar-light-lime sidebar-light-secondary sidebar-dark-warning sidebar-light-dark sidebar-dark-light --}}
<aside class="main-sidebar elevation-4 {{Auth::user()->tema->aside}}" id="main-sidebar">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="{{ asset('custom-img/contabilidad_en_la_nube.png') }}" alt="" class="brand-image img-circle elevation-1"
            style="box-shadow: 0">
        <span class="brand-text font-weight-dark">Sistema Contable</span>
    </a>

    <!-- Sidebar -->
    {{--     sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-overflow-x --}}
    {{--     sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden --}}
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <br>
            <br>
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search"
                    style="background: rgb(255, 250, 250); color: black">
                <div class="input-group-append" style="background: rgb(232, 232, 232)">
                    <button class="btn btn-sidebar" style="background: rgb(232, 232, 232)">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            {{-- nav nav-pills nav-sidebar flex-column --}}
            {{-- nav nav-pills nav-sidebar flex-column nav-child-indent --}}
            {{-- nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent --}}
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">

                @if (Auth::user()->rol == "Administrador")
                <li class="nav-item">
                    <a href="/usuarios" class="nav-link" id="menuUsuarios">
                        <i class="fas fa-user-lock"></i>
                        <p>
                            Usuarios
                        </p>
                    </a>
                </li>
                @endif

                {{-- <--! text-warning--> --}}
                <li class="nav-header  {{Auth::user()->tema->aside_header}}">EMPRESAS</li>
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link" id="menuDashboard">
                        <i class="fas fa-chart-pie"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item" id="menuGestionarEmpresas">
                    <a href="#" class="nav-link" id="menuEmpresas">
                        <i class="fas fa-industry"></i>
                        <p>
                            Gestionar Empresas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/empresas" class="nav-link" id="submenuEmpresas">
                                <i class="fas fa-industry"></i>
                                <p>Empresas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/sucursales" class="nav-link" id="submenuSucursales">
                                <i class="fas fa-store-alt"></i>
                                <p>Sucursales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/ejercicios" class="nav-link" id="submenuEjercicios">
                                <i class="fas fa-cash-register"></i>
                                <p>Ejercicio Contable</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header  {{Auth::user()->tema->aside_header}}">FACTURAS - COMPRAS Y VENTAS</li>

                <li class="nav-item">
                    {{-- /compras?process=menu&idEmpresaActiva={{Auth::user()->idEmpresaActiva}} --}}
                    <a href="{{route('compras.index',['process'=>'menu','idEmpresaActiva'=>Auth::user()->idEmpresaActiva])}}" class="nav-link" id="menuCompras">
                        <i class="fas fa-luggage-cart"></i>
                        <p>Registrar Compras</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('ventas.index',['process'=>'menu','idEmpresaActiva'=>Auth::user()->idEmpresaActiva])}}"" class="nav-link" id="menuVentas">
                        <i class="fas fa-cart-plus"></i>
                        <p>Registrar Ventas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="menuComprasConsultas" href="/registro-compras-ventas/consultas?process=menu&idEmpresaActiva={{Auth::user()->idEmpresaActiva}}" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <p>
                            Consultas
                        </p>
                    </a>
                </li>
{{--                 <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-upload"></i>
                        <p>Registro Masivo</p>
                    </a>
                </li> --}}

                <li class="nav-header  {{Auth::user()->tema->aside_header}}">ACTIVO FIJO</li>
                <li class="nav-item">
                    <a href="{{route('activoFijo.index')}}" id="menuActivoFijo" class="nav-link">
                        <i class="fas fa-chair"></i>
                        <p>
                            Listado de Activo Fijo
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('historial-depreciaciones')}}" id="menuHistorialDeprec" class="nav-link">
                        <i class="fas fa-ruler-horizontal"></i>
                        <p>
                            Historial
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('rubrosActivoFijo.index')}}" id="menuRubro" class="nav-link">
                        <i class="fas fa-project-diagram"></i>
                        <p>
                            Categorías de Activo Fijo
                        </p>
                    </a>
                </li>

                <li class="nav-header  {{Auth::user()->tema->aside_header}}">COTIZACIONES</li>
                <li class="nav-item">
                    <a href="{{route('tipoCambio.index')}}" id="menuCotizacion" class="nav-link">
                        <i class="fas fa-sort-numeric-up-alt"></i>
                        <p>
                            UFV
                        </p>
                    </a>
                </li>



                <li class="nav-header  {{Auth::user()->tema->aside_header}}">CONTABILIDAD</li>
                <li class="nav-item">
                    <a href="{{route('comprobante.create')}}" class="nav-link" id="menuAsientoContable">
                        <i class="fas fa-keyboard"></i>
                        <p>Registro Contable</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a id="menuLibroDiario" href="{{route('libro-diario')}}" class="nav-link">
                        <i class="fas fa-book"></i>
                        <p>Libro Diario</p>
                    </a>
                </li>

                @isset($sub_cuentas)
                <li class="nav-item">
                    <a class="nav-link"  data-toggle="modal" data-target="#modal-mayor-analitico" >
                        <i class="fas fa-window-restore"></i>
                        <p>Mayor Analítico</p>
                    </a>
                </li>
                @endisset

                <li class="nav-item">
                    <a id="menuSumasySaldos" href="{{route('balance-de-sumas-y-saldos')}}" target="_blank" class="nav-link">
                        <i class="fas fa-file-invoice"></i>
                        <p>Sumas y Saldos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a id="menuEEFF" href="{{route('estados-financieros')}}" target="_blank" class="nav-link">
                        <i class="far fa-file-alt"></i>
                        <p>Estados Financieros</p>
                    </a>
                </li>

                <li class="nav-item" id="menuGeneradores">
                    <a href="#" class="nav-link" id="menuGeneradores2" >
                        <i class="fas fa-window-restore"></i>
                        <p>
                            Generar Asientos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('generador-asientos-de-compras.index',['process'=>'menu'])}}" class="nav-link" id="submenuGeneradorCompras">
                                <i class="far fa-file-alt"></i>
                                <p>Asientos de Compras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('generador-asientos-de-ventas.index',['process'=>'menu'])}}" class="nav-link" id="submenuGeneradorVentas">
                                <i class="far fa-file-alt"></i>
                                <p>Asientos de Ventas</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{route('plan-de-cuentas')}}" class="nav-link" id="menuPlanDeCuentas">
                        <i class="fas fa-sitemap"></i>
                        <p>Plan de Cuentas</p>
                    </a>
                </li>

                <li class="nav-item" id="menuConfiguraciones">
                    <a href="#" class="nav-link" id="menuConfiguraciones_">
                        <i class="fas fa-tools"></i>
                        <p>
                            Configuraciones
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('tipo-comprobante.index')}}" class="nav-link" id="subMenuTipos">
                                <i class="fas fa-stream"></i>
                                <p>Tipos de Comprobantes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('preferencias-usuario.index')}}" target="_blank" class="nav-link" id="subMenuPreferencias">
                                <i class="fas fa-mouse-pointer"></i>
                                <p>Preferencias de usuario</p>
                            </a>
                        </li>
                        @if (Auth::user()->rol=="Administrador" || Auth::user()->rol=="Contador")
                        <li class="nav-item">
                            <a href="{{route('configuracion-sistema.index')}}" target="_blank" class="nav-link" id="subMenuSistema">
                                <i class="fas fa-wrench"></i>
                                <p>Sistema</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                @if (Auth::user()->rol == "Administrador")
                <li class="nav-item">
                    <a href="{{route('respaldar.index')}}" class="nav-link" id="menuRespaldar">
                        <i class="fas fa-database"></i>
                        <p>
                            Respaldar
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a id="" href="{{ asset('storage/manual-usuario/manual_de_usuario.pdf')}}" target="_blank" class="nav-link">
                        <i class="fas fa-file-invoice"></i>
                        <p>Manual de usuario</p>
                    </a>
                </li>
            </ul>
            <br><br><br><br><br>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
