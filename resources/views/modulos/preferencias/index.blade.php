@extends('plantilla.adminlte')

@section('titulo')
    Preferencias Usuario
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Preferencias del Usuario Actual</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Preferencias</li>
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
                    <div class="col-lg-12">
                        <div class="card card-dark">
                            <div class="card-body">

                                <form method="POST" action="{{url('/preferencias-usuario/'.Auth::user()->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-sm-2 text-right">
                                            <label for="mostrarBajas">Mostrar Bajas:</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="mostrarBajas" id="mostrarBajas" class="form-control" required>
                                                @if ($DatosUsuarioActivo->mostrarBajas == 0)
                                                    <option value="0" selected>Ocultar Bajas</option>
                                                    <option value="1">Mostrar Bajas</option>
                                                @elseif ($DatosUsuarioActivo->mostrarBajas == 1)
                                                    <option value="0">Ocultar Bajas</option>
                                                    <option value="1" selected>Mostrar Bajas</option>
                                                @else
                                                    <option value="0">Ocultar Bajas</option>
                                                    <option value="1">Mostrar Bajas</option>
                                                @endif
                                            </select>
                                            <p>(Empresas, Sucursales y Ejercicios)</p>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="validador" value="preferenciasUsuario">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark" style="background-color: #faedcd">
                            <div class="card-body">

                                <form method="POST" action="{{url('/preferencias-usuario/'.Auth::user()->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-1">
                                        </div>

                                        <div class="col-11 text-left">
                                            <label>Resaltar Campos relevantes en los formularios del Registro de Compras y Ventas:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="resaltarInputs" id="resaltarInputs" class="form-control" required>
                                                @if ($DatosUsuarioActivo->resaltar_inputs_rcv == 0)
                                                    <option value="0" selected>No resaltar</option>
                                                    <option value="1">Resaltar campos de entrada</option>
                                                @elseif ($DatosUsuarioActivo->resaltar_inputs_rcv == 1)
                                                    <option value="0">No resaltar</option>
                                                    <option value="1" selected>Resaltar campos de entrada</option>
                                                @else
                                                    <option value="0">No resaltar</option>
                                                    <option value="1">Resaltar campos de entrada</option>
                                                @endif
                                            </select>
                                            <p>(Registro de Compras y Ventas)</p>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="validador" value="resaltar_inputs">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark">
                            <div class="card-body">

                                <form method="POST" action="{{url('/preferencias-usuario/'.Auth::user()->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-1">
                                        </div>

                                        <div class="col-11 text-left">
                                            <label>Tema del Sistema:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="tema" id="tema" class="form-control" required>
                                                @foreach ($temas as $tema )
                                                    @if (Auth::user()->tema_id == $tema->id)
                                                        <option value="{{$tema->id}}" selected>{{$tema->nombre}}</option>
                                                    @else
                                                        <option value="{{$tema->id}}">{{$tema->nombre}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <p>(Menú lateral y Cabecera)</p>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="validador" value="tema">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark" style="background-color: #faedcd">
                            <div class="card-body">

                                <form method="POST" action="{{url('/preferencias-usuario/'.Auth::user()->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-1">
                                        </div>

                                        <div class="col-11 text-left">
                                            <label>Mostrar hora y fecha en reportes pdf:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="hora_fecha" id="hora_fecha" class="form-control" required>
                                                @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                                                    <option value="1" selected>Mostrar</option>
                                                    <option value="0">Ocultar</option>
                                                    @else
                                                    <option value="1">Mostrar</option>
                                                    <option value="0" selected>Ocultar</option>
                                                @endif
                                            </select>
                                            <p>(Utilizado en reportes PDFs)</p>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="validador" value="hora_fecha_en_pdf">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

            </div>
        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuConfiguraciones').addClass('menu-open');
        $('#menuConfiguraciones_').addClass('active');
        $('#subMenuPreferencias').addClass('active');
    </script>

    {{--! este mensaje es recibido al ACTUALIZAR PREFERENCIAS --}}
    @if (Session('actualizacion_preferencias_usuario')=='ok')
    <script>
        toastr.success('Datos actualizados con éxito.');
    </script>
    @endif
@endsection
