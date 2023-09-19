<!-- Navbar -->
{{-- navbar-white navbar-dark --}}
<nav class="main-header navbar navbar-expand {{Auth::user()->tema->nav}} " id="navbar">
    <ul class="navbar-nav">
        <li class="nav-item">
            {{-- clase pushmenu es usado tambien para el estado del aside en la bd --}}
            <a class="nav-link pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        {{--! EMPRESA ACTIVA --}}
        {{-- lo utilizamos cuando exista . para el caso de edicion de perfil de usuario no es utilizado--}}
        @isset($empresas)
            {{--! Empresas --}}
            @foreach ($empresas as $empr)
                @if ($empr->id == Auth::user()->idEmpresaActiva)
                    @if ($empr->estado == 1)
                            <li class="nav-item d-none d-md-inline-block">
                                <a href="/empresas/{{$empr->id}}" class="nav-link pr-1  {{Auth::user()->tema->text_nav}}">
                                @if (strlen($empr->denominacionSocial) > 50)
                                    {{ substr($empr->denominacionSocial, 0, 49)."..." }}
                                    @else
                                    {{$empr->denominacionSocial}}
                                @endif
                                </a>
                            </li>

                            {{--! Ejercicios --}}
                            @foreach ($ejercicios as $ejer)
                                @if ($ejer->id == Auth::user()->idEjercicioActivo)
                                    @if ($ejer->estado == 1)
                                        <li class="nav-item d-none d-md-inline-block">
                                            <a href="/ejercicios?id_denominacionSocial={{Auth::user()->idEmpresaActiva}}" class="nav-link pl-0  {{Auth::user()->tema->text_nav}}">| Contabilidad:{{ $ejer->ejercicioFiscal }}</a>
                                        </li>
                                        @else
                                        <li class="nav-item d-none d-md-inline-block">
                                            <a href="/empresas/{{$empr->id }}" class="nav-link pr-1  {{Auth::user()->tema->text_nav}}">Seleccione Ejercicio</a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                            {{--* Fin Ejercicios --}}

                            @else
                            <li class="nav-item d-none d-sm-inline-block">
                                <a href="/empresas" class="nav-link pr-1  {{Auth::user()->tema->text_nav}}">Seleccione una Empresa</a>
                            </li>
                    @endif
                @endif
            @endforeach
            {{--* Fin Empresas --}}
        @endisset
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- USUARIO -->
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                {{-- <span class="nav-item d-none d-md-inline-block">{{ Auth::user()->name }}</span> --}}
                @if (Auth::user()->profile_photo_path == "")
                    <img src="{{ asset('/storage/profile-photos/usuario-image-predeterminado.jpeg') }}" alt="Avatar" class="img-size-32 img-circle">
                @else
                    <img src="{{ asset('/storage/'.Auth::user()->profile_photo_path) }}" alt="Avatar" class="img-size-32 img-circle">
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ Auth::user()->name }}
                    <br>
                    {{ Auth::user()->rol }}
                </span>
                {{-- configuar perfil --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.show') }}"
                    :active="request() - > routeIs('profile.show')">
                    <i class="fas fa-users mr-2"></i>
                    Perfil del usuario
                </a>
                {{-- cerrar cesion --}}
                <a href="#" class="dropdown-item">
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item p-0">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                        </button>
                    </form>
                </a>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                role="button">
                <i class="fas fa-cogs"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
