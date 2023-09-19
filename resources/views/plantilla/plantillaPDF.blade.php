<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> @yield('titulo') </title>

    @yield('css')

    {{--     use
    url()
    storage_path()
    base_path() --}}

    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{ url('/dist/css/adminlte.css') }}"> --}}

</head>

{{-- * Aqui se modifica los fixes de los menus sidebar-collapse --}}

<body id="body">

    @yield('contenido')

</body>

</html>
