@extends('plantilla.adminlte')

@section('titulo')
Usuarios
@endsection

@section('css')
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection

@section('contenido')
    <div class="content-wrapper">
        {{--! <!--  Encabezado del contenido - Content Header --> --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Usuarios del Sistema</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{--! <!-- /. Encabezado del contenido - content-header --> --}}

        {{--! <!-- Contenido Principal - Main content --> --}}
        <section class="content">
            <div class="container-fluid">
                {{--* boton de crear nuevo --}}
                <div class="row">
                    <div class="col-3">
                        <button type="button" role="button"  data-toggle="modal" data-target="#modal-crear-usuario" class="btn btn-block btn-outline-success mt-2">
                            <i class="fas fa-plus"></i>
                            Nuevo Usuario
                        </button>
                    </div>
                </div>
                {{--* boton de crear nuevo --}}
                <br>

                {{-- ! DataTable --}}
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Usuarios registrado en el Sistema</h3>
                    </div>

                    <div class="card-body table-responsive p-2">
                        <table id="tablaUsuarios" class="table table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th class="text-center">Nro</th>
                                    {{-- <th>ID</th> --}}
                                    <th class="text-center">Nombres y Apellidos</th>
                                    <th class="text-center">Correo Electrónico</th>
                                    <th class="text-center">Acceso</th>
                                    <th class="text-center">Rol de usuario</th>
                                    <th class="text-center">Puede</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php // declaramos la variable, no la imprimimos aun
                                    $numero=0;
                                @endphp
                                @foreach ( $users as $user )
                                <tr>
                                    <td>{{$numero=$numero + 1}}</td>
                                    {{-- <td>{{$user->id}}</td> --}}
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>

                                    <td>
                                        @if ($user->acceso == "Permitido")
                                        <span class="badge bg-dark p-2">
                                            {{$user->acceso}}
                                        </span>
                                        @else
                                        <span class="badge bg-danger p-2">
                                            {{$user->acceso}}
                                        </span>
                                        @endif
                                    </td>
                                    <td>{{$user->rol}}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if ($user->acceso == "Permitido")
                                                @if ($user->crear == 1)
                                                <span class="badge bg-cyan p-2">
                                                    Crear
                                                </span>
                                                @endif
                                                @if ($user->editar == 1)
                                                <span class="badge bg-fuchsia p-2">
                                                    Editar
                                                </span>
                                                @endif
                                                @if ($user->eliminar == 1)
                                                <span class="badge bg-yellow p-2">
                                                    Eliminar
                                                </span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger p-2">
                                                    No tiene acceso al sistema
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    {{-- botones --}}
                                    <td style="text-align: center">
                                        <form  action="{{route ('usuarios.destroy',$user->id)}}" method="POST" class="frmEliminar-usuario">
                                            @csrf
                                            @method('DELETE')

                                            <div class="btn-group btn-group-sm">
                                                <a role="button" class="btn btn-info"
                                                    data-toggle="modal" data-target="#modal-editar-usuario{{$user->id}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- ! Fin DataTable --}}
            </div>

            {{--! modal crear usuario --}}
            <div class="modal fade" id="modal-crear-usuario">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('usuarios.store')}}" class="frmCrear-Usuario" >
                            @csrf

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="nombres">Nombres</label>
                                        <input name="nombres" id="nombres" type="text" class="form-control" autocomplete="off" value="{{old('nombres')}}" required>
                                    </div>
                                    {{-- <div class="form-group col-md-4">
                                        <label for="primer_apellido">Primer Apellido</label>
                                        <input name="primer_apellido" id="primer_apellido" type="text" class="form-control" autocomplete="off" value="{{old('primer_apellido')}}" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input name="segundo_apellido" id="segundo_apellido" type="text" class="form-control" autocomplete="off" value="{{old('segundo_apellido')}}">
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="email">Correo Electrónico</label>
                                        <input name="email" id="email" type="email" class="form-control" autocomplete="off" value="{{old('correo')}}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="password">Contraseña</label>
                                        <input name="password" id="password" type="password" class="form-control" autocomplete="off" required>
                                        <small class="text-muted">Mínimo debe contener 8 carácteres</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password_conf">Confirmar Contraseña</label>
                                        <input name="password_conf" id="password_conf" type="password" class="form-control" autocomplete="off" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acceso">Acceso</label>
                                            <select name="acceso" id="acceso" class="form-control select2" style="width: 100%;" required>
                                                <option value=""></option>
                                                <option value="Permitido">Permitido</option>
                                                <option value="Denegado">Denegado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rol">Rol de usuario</label>
                                            <select name="rol" id="rol" class="form-control select2" style="width: 100%;" required>
                                                <option value=""></option>
                                                <option value="Administrador">Administrador</option>
                                                <option value="Contador">Contador</option>
                                                <option value="Auxiliar Contable">Auxiliar Contable</option>
                                                <option value="Invitado">Invitado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-dark col-md-3">Guardar</button>
                                <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            {{--! Fin modal crear usuario --}}

            {{--! modal editar usuario --}}
            @foreach ( $users as $user )
            <div class="modal fade" id="modal-editar-usuario{{$user->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar Usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('usuarios.update',$user->id)}}" class="frmEditar-Usuario" >
                            @csrf
                            @method('PUT')

                            <input type="hidden" value="datosUsuario" name="validador">

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="nombres">Nombres</label>
                                        <input name="nombres" id="nombres" type="text" class="form-control" autocomplete="off" value="{{$user->name}}" required>
                                    </div>
                                    {{-- <div class="form-group col-md-4">
                                        <label for="primer_apellido">Primer Apellido</label>
                                        <input name="primer_apellido" id="primer_apellido" type="text" class="form-control" autocomplete="off" value="{{$user->primer_apellido}}" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input name="segundo_apellido" id="segundo_apellido" type="text" class="form-control" autocomplete="off" value="{{$user->segundo_apellido}}">
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="email">Correo Electrónico</label>
                                        <input name="email" id="email" type="email" class="form-control" autocomplete="off" value="{{$user->email}}" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acceso">Acceso</label>
                                            <select name="acceso" id="acceso" class="form-control select2" style="width: 100%;" required>
                                                @if ($user->acceso == "Permitido")
                                                    <option value="Permitido" selected>Permitido</option>
                                                    <option value="Denegado">Denegado</option>
                                                @else
                                                    <option value="Permitido">Permitido</option>
                                                    <option value="Denegado" selected>Denegado</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rol">Rol de usuario</label>
                                            <select name="rol" id="rol" class="form-control select2" style="width: 100%;" required>
                                                <option value="{{$user->rol}}">{{$user->rol}}</option>
                                                <option value="Administrador">Administrador</option>
                                                <option value="Contador">Contador</option>
                                                <option value="Auxiliar Contable">Auxiliar Contable</option>
                                                <option value="Invitado">Invitado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>El usuario puede:</label>
                                    </div>
                                </div>
                                <!-- checkbox -->
                                <div class="row">
                                    <div class="col-md-4">
                                        @if ($user->crear == 1)
                                            <div class="icheck-cyan form-group">
                                                <input type="checkbox" name="crear" id="someCheckboxId1_{{$user->id}}" checked />
                                                <label for="someCheckboxId1_{{$user->id}}">Crear Registros</label>
                                            </div>
                                        @else
                                            <div class="icheck-cyan form-group">
                                                <input type="checkbox" name="crear" id="someCheckboxId1_{{$user->id}}" />
                                                <label for="someCheckboxId1_{{$user->id}}">Crear Registros</label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        @if ($user->editar == 1)
                                            <div class="icheck-fuchsia form-group">
                                                <input type="checkbox" name="editar" id="someCheckboxId2_{{$user->id}}" checked/>
                                                <label for="someCheckboxId2_{{$user->id}}">Editar Registros</label>
                                            </div>
                                        @else
                                            <div class="icheck-fuchsia form-group">
                                                <input type="checkbox" name="editar" id="someCheckboxId2_{{$user->id}}" />
                                                <label for="someCheckboxId2_{{$user->id}}">Editar Registros</label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        @if ($user->eliminar == 1)
                                            <div class="icheck-yellow form-group">
                                                <input  type="checkbox" name="eliminar" id="someCheckboxId3_{{$user->id}}" checked/>
                                                <label for="someCheckboxId3_{{$user->id}}">Eliminar Registros</label>
                                            </div>
                                        @else
                                            <div class="icheck-yellow form-group">
                                                <input type="checkbox" name="eliminar" id="someCheckboxId3_{{$user->id}}" />
                                                <label for="someCheckboxId3_{{$user->id}}">Eliminar Registros</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Reestablecer Contraseña</label>
                                        <input name="password" type="password" class="form-control" autocomplete="off">
                                        <small class="text-muted">Mínimo debe contener 8 carácteres</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Confirmar Contraseña</label>
                                        <input name="password_conf" type="password" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-dark col-md-3">Actualizar</button>
                                <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            @endforeach
            {{--! fin modal editar usuario --}}
        </section>
    </div>
@endsection

@section('js')

    {{--! menu actual --}}
    <script>
        $('#menuUsuarios').addClass('active');
    </script>

    {{--! Error cuando el correo ya existe --}}
    @error('email')
    <script>
        toastr.error('El correo Electrónico ya fue registrado, no puede ser registrado nuevamente.')
    </script>
    @enderror
    @error('password')
    <script>
        toastr.error('La nueva contraseña minimamente debe contener 8 carácteres, no se actualizó el usuario.')
    </script>
    @enderror
    @if (Session('igualdad_contr')=='error')
    <script>
            toastr.error('Las contraseñas no igualan, no se actualizó el usuario.');
    </script>
    @endif



    {{--! este mensaje es recibido al CREAR NUEVO --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Usuario creado exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.');
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Usuario dado de baja exitosamente.');
    </script>
    @endif

    {{--! Pregunta desea CREAR--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Usuario').submit(function(e){
                /* verificamos contraseñas (tamaño e igualdad) */
                if(password.value.length >= 8 )
                {
                    if(password.value == password_conf.value)
                    {
                        e.preventDefault();
                        Swal.fire({
                        title: '¿Desea crear el Usuario?',
                        text: "¡Agregará una nuevo ususario al sistema!",
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
                    }
                    else
                    {
                        alert("Las contraseñas no igualan");
                        return false;
                    }
                }
                else{
                    alert("La contraseña debe contener mínimo 8 carácteres");
                    return false;
                }
            })
        </script>
    @else
        <script>
            $('.frmCrear-Usuario').submit(function(e){
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

    {{--! Pregunta desea EDITAR--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Usuario').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Usuario?',
                text: "¡Actualizará datos del usuario!",
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
            $('.frmEditar-Usuario').submit(function(e){
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


    {{--! Pregunta Eliminar --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-usuario').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea dar de baja al Usuario?',
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
            $('.frmEliminar-usuario').submit(function(e){
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
{{--! DATATABLE --}}
<script>
    $(function () {
        $("#tablaUsuarios").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": false,
            //"buttons": ["copy","excel"],
            //"aaSorting": [], //desabilitamos el orden automatico
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
/*                 "scrollCollapse": true,
            "scrollY": 300,
            "scrollX": true, */
        }).buttons().container().appendTo('#tablaUsuarios_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection
