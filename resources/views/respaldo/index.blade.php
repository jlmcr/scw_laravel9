@extends('plantilla.adminlte')

@section('titulo')
    Respaldo
@endsection

@section('css')

@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Respaldo</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Respaldo</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card card-row card-danger">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Credenciales
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{route('respaldar.ejecutar')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="pas">Contraseña de usuario</label>
                                            <input name="pas" id="pas" type="password" class="form-control"
                                            autocomplete="off"  maxlength="50">
                                        </div>

                                        <div class="form-group">
                                            <label for="des">Clave de respaldo</label>
                                            <input name="des" id="des" type="password" class="form-control"
                                            autocomplete="off"  maxlength="50">
                                        </div>

                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="submit" class="btn btn-outline-dark col-md-3">Descargar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{--* modal --}}

            {{--* Fin modal --}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuRespaldar').addClass('active');
    </script>

    {{--! MENSAJES--}}
    @if (Session('respaldo')=='error al respaldar')
    <script>
            toastr.error('Ocurrió un error, por favor inténtelo más tarde.')
    </script>
    @endif

    @if (Session('respaldo')=='credenciales incorrectos')
    <script>
            toastr.error('Credenciales ingresados incorrectos.')
    </script>
    @endif

@endsection
