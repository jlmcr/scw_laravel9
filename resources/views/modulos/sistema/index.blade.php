@extends('plantilla.adminlte')

@section('titulo')
    Sistema
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Configuración del Sistema</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Configuración del Sistema</li>
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
                            <div class="card-body" style="background-color: rgb(254, 254, 170)">

                                <form method="POST" action="{{url('/configuracion-sistema/'.$sistema->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-sm-2 text-right">
                                            <label for="mensaje">Mensaje predeterminado de WhatsApp:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" name="mensaje" id="mensaje" value="{{$sistema->mensajeWhatsapp}}" maxlength="100" title="Mensaje para comunicarse con representantes guardados en sistema | 100 carácteres" class="form-control"  required>
                                            <p>(Aplicado a todos los usuarios del sistema)</p>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="hidden" name="validador" value="mensaje">

                                            <button type="submit" class="btn btn-outline-success"><i class="fa fa-check"></i></button>
                                        </div>
                                        <div class="col-sm-2">
                                            <label id="msg1" class="text-red"></label>
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
                            <div class="card-body" style="background-color: rgb(254, 254, 170)">

                                <form method="POST" action="{{url('/configuracion-sistema/'.$sistema->id)}}" name="frmPreferenciasUsuario">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-sm-2 text-right">
                                            <label for="gestion">Año mínimo permitido en el sistema:</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" name="gestion" id="gestion" value="{{$sistema->anioMinimo}}" title="Predeterminado 2021 | mínimo 2010" class="form-control" required>

                                            <p>(Utilizado para Crear ejercicios Contables, Compras, Ventas y Reportes)</p>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="hidden" name="validador" value="anioMinimoPermitido">

                                            <button type="submit" class="btn btn-outline-success"><i class="fa fa-check"></i></button>
                                        </div>
                                        <div class="col-sm-2">
                                            <label id="msg2" class="text-red"></label>
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
                        <div class="card">
                            <div class="card-header" style="background-color: rgb(202, 255, 206)">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Detalle de Acceso por Roles</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-2" style="background-color: rgb(202, 255, 206)">
                                <table class="table text-nowrap table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Rol de Usuario</th>
                                            <th>Modulos</th>
                                            <th>Acciones</th>
                                            <th>Acceso preferencial a</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Administrador</th>
                                            <td>
                                                <p>Empresas</p>
                                                <p>Facturas de Compras y Ventas</p>
                                                <p>Contabilidad</p>
                                            </td>
                                            <td>
                                                <p>Crear, Editar, Eliminar</p>
                                                <p>Ver y Generar reportes</p>
                                                <p>*El Administrador puede limitar acciones</p>
                                            </td>
                                            <td>
                                                <p>Gestión de usuarios</p>
                                                <p>Configuración del Sistema</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Contador</th>
                                            <td>
                                                <p>Empresas</p>
                                                <p>Facturas de Compras y Ventas</p>
                                                <p>Contabilidad</p>
                                            </td>
                                            <td>
                                                <p>Crear, Editar, Eliminar</p>
                                                <p>Ver y Generar reportes</p>
                                                <p>*El Administrador puede limitar acciones</p>
                                            </td>
                                            <td>
                                                <p>Configuración del Sistema</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Auxiliar Contable</th>
                                            <td>
                                                <p>Empresas</p>
                                                <p>Facturas de Compras y Ventas</p>
                                                <p>Contabilidad</p>
                                            </td>
                                            <td>
                                                <p>Crear, Editar, Eliminar</p>
                                                <p>Ver y Generar reportes</p>
                                                <p>*El Administrador puede limitar acciones</p>
                                            </td>
                                            <td>
                                                <p></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Invitado</th>
                                            <td>
                                                <p>Empresas</p>
                                                <p>Facturas de Compras y Ventas</p>
                                                <p>Contabilidad</p>
                                            </td>
                                            <td>
                                                <p>Ver y Generar reportes</p>
                                                <p>*El Administrador puede conceder más acciones</p>
                                            </td>
                                            <td>
                                                <p></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
        $('#subMenuSistema').addClass('active');
    </script>

    {{--! este mensaje es recibido al ACTUALIZAR PREFERENCIAS --}}
    @if (Session('actualizar')=='ok')
    <script>
        toastr.success('Datos actualizados con éxito.');
    </script>
    @endif

    {{--! limite gestion --}}
    <script>
        // solo numeros en gestion
        $("#gestion").on('input', function (evt) {
            $(this).val($(this).val().replace(/[^0-9]/g, '')); //apuntes en JQuery
        });

        gestion.oninput = function(){      //! al cambiar algo dentro del input
            if (this.value.length > 4) {
                this.value = this.value.slice(0,4); //estraccion de cadena
            }
        }

        gestion.onchange = function(){ // al cambiar de input
            const hoy = new Date();  //obtenemos fecha completa del equipo 
            
            if (this.value.length < 4) {
                swal.fire("El año debe contener 4 dígitos");
                gestion.value="";
            }

            //mensaje de limite de año
            if (this.value > hoy.getFullYear()) {
                swal.fire("El año no puede superar el " + hoy.getFullYear());
                //gestion.select();

                gestion.value="";
            }
            //mensaje de año minimo
            if (this.value < 2010) {
                swal.fire("El año no puede ser inferior a 2010");
                //gestion.select();

                gestion.value="";
            }

            $("#msg2").html("Cambios no guardados...");
            
            // hoy.toLocaleDateString() -> para obtener la fecha actual en JavaScript
            //console.log(hoy);
            //console.log(hoy.getFullYear());
        }

        $("#mensaje").change(function(){
            $("#msg1").html("Cambios no guardados...");
        })

    </script>
@endsection
