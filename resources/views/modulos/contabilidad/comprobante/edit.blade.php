@extends('plantilla.adminlte')

@section('titulo')
    Comprobante de Contabilidad
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <a href="">
                            <h1 class="m-0">Edición de Asiento/Registro Contable</h1>
                        </a>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Registro de Comprobante de Contabilidad</li>
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
                    {{-- comprobante --}}
                    <div class="col-md-9">
                        <div class="card card-dark card-outline">
                            <form action="{{route('comprobante.update',$datosGeneralesComprobante->id)}}" method="POST"
                            id="frmEditar-Comprobante" class="frmEditar-Comprobante">
                                @csrf
                                @method('PUT')

                                <div class="p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="nroComprobante">Nro. Comprobante:</label>
                                            <input type="text" name="nroComprobante" id="nroComprobante" class="form-control"
                                            value="{{$datosGeneralesComprobante->nroComprobante}}" readonly>
                                            <input type="hidden" name="correlativo" id="correlativo">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="tipoComprobante">Tipo de Comprobante:</label>
                                            <select name="tipoComprobante" id="tipoComprobante" class="form-control" readonly>

                                                <option value="{{$datosGeneralesComprobante->tipoComprobante_id}}" selected>
                                                    {{$datosGeneralesComprobante->tipo->nombre}}
                                                </option>

                                                {{-- @foreach ($tiposComprobantes as $tipo )
                                                    @if ($tipo->id == $datosGeneralesComprobante->tipoComprobante_id)
                                                        <option value="{{$tipo->id}}" selected>{{$tipo->nombre}}</option>
                                                    @else
                                                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                                    @endif
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="fecha">Fecha:</label>
                                            <input type="date" name="fecha" id="fecha" class="form-control" autocomplete="off"
                                            value="{{$datosGeneralesComprobante->fecha}}" readonly>
                                            {{-- <input type="text" name="fecha" id="date-mask-input-a" class="form-control" autocomplete="off"> --}}
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="concepto">Concepto:</label>
                                            <textarea name="concepto" id="concepto" cols="30" rows="2" class="form-control text-uppercase"
                                            maxlength="250" required>{{$datosGeneralesComprobante->concepto}}</textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="">Documento Respaldatorio:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="documento">Documento:</label>
                                            <input type="text" list="listaDocumentos" name="documento" id="documento" class="form-control text-uppercase"
                                            maxlength="30" autocomplete="off" value="{{$datosGeneralesComprobante->documento}}">
                                            <datalist id="listaDocumentos">
                                                    <option value="Factura">Factura</option>
                                                    <option value="Recibo">Recibo</option>
                                                    <option value="Nota de Venta">Nota de Venta</option>
                                                    <option value="Contrato">Contrato</option>
                                                    <option value="Balance de Apertura">Balance de Apertura</option>
                                                    <option value="Balance General Anterior">Balance General Anterior</option>
                                                    <option value="Otro">Otro</option>
                                            </datalist>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for=""></label>
                                            <label for="numeroDocumento">Nro./Cod.:</label>
                                            <input type="text" name="numeroDocumento" id="numeroDocumento" class="form-control text-uppercase"
                                            maxlength="30" autocomplete="off" value="{{$datosGeneralesComprobante->numeroDocumento}}">
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <button id="btnNuevaFila" type="button" class="btn btn-outline-info" >
                                                <i class="fas fa-plus"></i> Nueva Fila
                                            </button>
                                        </div>
                                    </div>
                                    <br>

                                    <!-- Table row -->
                                    <div class="row" style="background-color: rgb(170, 254, 170)">
                                        <div class="col-12 table-responsive">
                                            <table class="table" id="tablaCodigoCuentaDebeHaber">
                                                <thead>
                                                    <tr>
                                                        <th>Codigo</th>
                                                        <th>Cuenta</th>
                                                        <th>Debe</th>
                                                        <th>Haber</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-md">
                                                    @foreach ( $detalleComprobante as $detalle )
                                                        <tr>
                                                            <td style="width: 20%">
                                                                {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                                <select name="codigo[]" class="codigo form-control select2">
                                                                    @foreach ($sub_cuentas as $sub_cuenta)

                                                                        @if ($sub_cuenta->id == $detalle->codigo)
                                                                            <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}" selected>
                                                                                {{$sub_cuenta->id}}
                                                                            </option>
                                                                        @else
                                                                            <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                                {{$sub_cuenta->id}}
                                                                            </option>
                                                                        @endif

                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="width: 30%">
                                                                <select name="subcuenta[]" class="subcuenta form-control select2">
                                                                    <option value="{{$detalle->codigo}}">
                                                                        {{$detalle->descripcion}}
                                                                    </option>

                                                                    @foreach ($sub_cuentas as $sub_cuenta)
                                                                        @if ($sub_cuenta->id == $detalle->codigo)
                                                                            <option value="{{$sub_cuenta->id}}" selected>{{$sub_cuenta->descripcion}}</option>
                                                                        @else
                                                                            <option value="{{$sub_cuenta->id}}">{{$sub_cuenta->descripcion}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            {{-- ? debe y haber --}}
                                                            @php
                                                                //debe
                                                                if($detalle->debe == 0 || $detalle->debe == ""){
                                                                    $debe = "";
                                                                }
                                                                else {
                                                                    $debe = number_format($detalle->debe,2,".",",");
                                                                }
                                                                //haber
                                                                if($detalle->haber == 0 || $detalle->haber == ""){
                                                                    $haber = "";
                                                                }
                                                                else {
                                                                    $haber = number_format($detalle->haber,2,".",",");
                                                                }
                                                            @endphp

                                                            <td style="width: 20%">
                                                                <input type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off"
                                                                value="{{$debe}}">
                                                            </td>
                                                            <td style="width: 20%">
                                                                <input type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off"
                                                                value="{{$haber}}">
                                                            </td>
                                                            <td class="align-middle">
                                                                <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    {{-- SUMAS --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table" id="totales">
                                                    <tr>
                                                        <th style="width:50%">Suma DEBE:</th>
                                                        <td>
                                                            <input type="text" name="sumaDebe" id="sumaDebe" value="0.00" class="border-0 text-right" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:50%">Suma HABER:</th>
                                                        <td>
                                                            <input type="text" name="sumaHaber" id="sumaHaber" value="0.00" class="border-0 text-right" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Diferencia</p><p>(Debe - Haber):</p></th>
                                                        <td>
                                                            <input type="text" name="diferencia" id="diferencia" value="0.00" class="border-0 text-red text-right" readonly>
                                                        </td>
                                                        <input type="hidden" name="observaciones" id="observaciones">
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <th>Notas:</th>
                                                        <td>
                                                            <textarea name="notas" id="notas" class="form-control w-100 text-uppercase" maxlength="100">{{$datosGeneralesComprobante->notas}}</textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row no-print">
                                        <div class="col-12">
                                            {{-- <a href="" target="_blank" class="btn btn-default">
                                                <i class="fas fa-print"></i>Imprimir
                                            </a> --}}
                                            <button type="submit" class="btn btn-dark float-right">
                                                <i class="fas fa-save"></i>
                                                Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- derecha --}}
                    <div class="col-md-3">
                        <!-- /.card -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Periodo habilitado</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body table-responsive p-2">
                                    <table class="table table-head-fixed text-nowrap" style="width:100%">
                                        <tbody>
                                            @if ($ejercicioActivo != "")
                                                <tr>
                                                    <th>Ejercicio Cont:</th>
                                                    <td>{{$ejercicioActivo->ejercicioFiscal}}</td>
                                                </tr>
                                                @php
                                                    $f1 = explode('-',$ejercicioActivo->fechaInicio);
                                                    $f2 = explode('-',$ejercicioActivo->fechaCierre);
                                                @endphp
                                                <tr>
                                                    <th>Del:</th>
                                                    <td>{{$f1[2]."/".$f1[1]."/".$f1[0]}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Al:</th>
                                                    <td>{{$f2[2]."/".$f2[1]."/".$f2[0]}}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Tipos de Comprobantes</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body table-responsive p-2">
                                    <table class="table table-head-fixed text-nowrap table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tiposComprobantes as $tipo )
                                            <tr>
                                                <td>{{$tipo->id}}</td>
                                                <td>{{$tipo->nombre}}</td>
                                                {{-- Botones --}}
                                                {{-- <td style="text-align: center">
                                                    <form  action="" method="POST" class="frmEliminar-TipoComprobante">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="btn-group btn-group-xs">
                                                            <a role="button" class="btn btn-info btn-xs btnTipoComprobante"
                                                                data-toggle="modal"
                                                                data-target="#modal-editar-tipo-comprobante{{$tipo->id}}">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </form>
                                                </td> --}}
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <br>
                                <a href="#" class="btn btn-outline-dark btn-block"><b>Nuevo</b></a> --}}
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        {{-- <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Otras Opciones</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <strong><i class="far fa-file-alt mr-1"></i>Asiento de Re-apertura</strong>

                                <p class="text-muted">
                                    Solamente puede importar el archivo generado por éste sistema al terminar la contabilidad del ejercicio contable anterior

                                </p>
                                <a role="button" class="btn btn-outline-success"
                                data-toggle="modal" data-target="#modal-importar-apertura">
                                    <i class="far fa-file-alt mr-1"></i>
                                    Importar desde Excel
                                </a>

                                <hr>

                                <strong><i class="fas fa-pencil-alt mr-1"></i> Asientos Comunes</strong>

                                <a role="button" class="btn btn-outline-info m-1"
                                data-toggle="modal" data-target="#modal-">
                                    <i class="far fa-file-alt mr-1"></i>
                                    Asientos Predeterminados
                                </a>
                                <a href="" target="_blank" class="btn btn-outline-danger m-1">
                                    <i class="fas fa-cogs mr-1"></i>
                                    configurar
                                </a>
                            </div>
                        </div> --}}

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
        $('#menuAsientoContable').addClass('active');
    </script>
    {{--! colapsar menu --}}
    <script>
        // document.getElementById("body").classList.remove('')
        document.getElementById("body").classList.add('sidebar-collapse');
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! libreria numeral --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{--! mascara fecha start ui--}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>

    {{--! generador de numero de comprobante --}}
    <script src="{{ asset('/custom-code/modulos/comprobantes/generar-numero.comprobante.js') }}"></script>

    {{--! mensajes de error --}}
        @if (Session('fecha')=='fecha inexistente')
            <script>
                    toastr.error('Fecha erronea, por favor revise la fecha, es posible que la fecha no exista.')
            </script>
        @endif
        @if (Session('fecha')=='fuera de periodo')
            <script>
                    toastr.error('La fecha del comprobante no se encuentra dentro del periodo que comprende el ejercicio contable.')
            </script>
        @endif
        @if (Session('numeroComprobante')=='numero actualizado')
            <script>
                    toastr.success('El numero de comprobante fue actualizado.')
            </script>
        @endif
    {{--! mensajes de error --}}

    @if (Session('comprobante')=='actualizado')
        <script>
                toastr.success('Comprobante actualizado exitosamente.')
        </script>
    @endif

    {{--! FORMATOS DE NUMERAL Y SUMA DE  Debe y Haber --}}
    <script>
        $(".debe").change(function(){
            calcularSumaDebe();

            //alert( $(this).val() );
            var valor = $(this).val();
            this.value = numeral(valor).format('0,0.00');
        });

        $(".haber").change(function(){
            calcularSumaHaber()

            var valor = $(this).val();
            this.value = numeral(valor).format('0,0.00');
        });

        function numeral_Debe_y_Haber(){
            $(".debe").change(function(){
                calcularSumaDebe();

                //alert( $(this).val() );
                var valor = $(this).val();
                this.value = numeral(valor).format('0,0.00');
            });

            $(".haber").change(function(){
                calcularSumaHaber()

                var valor = $(this).val();
                this.value = numeral(valor).format('0,0.00');
            });
        }
    </script>

    {{--! FUNCIONES PARA LA SUMA Debe y Haber y diferencias--}}
    <script>

        function calcularSumaDebe(){
            var sumaDebe = 0;
            var importe =0;

            $(".debe").each(function(){

                if($(this).val() == ""){
                    importe = 0;
                }
                else{
                    importe = $(this).val().replace(',','');
                }
                sumaDebe += parseFloat(importe);

            });

            $("#sumaDebe").val(sumaDebe.toFixed(2)); //alert( sumaDebe );
            calcularDiferencia();
        }

        function calcularSumaHaber(){
            var sumaHaber = 0;
            var importe =0;

            $(".haber").each(function(){

                if($(this).val() == ""){
                    importe = 0;
                }
                else{
                    importe = $(this).val().replace(',','');
                }
                sumaHaber += parseFloat(importe);

            });

            $("#sumaHaber").val(sumaHaber.toFixed(2)); //utilizamos to fixed para redondear por el probema de decimales
            calcularDiferencia();
        }

        function calcularDiferencia(){
            var suma1 = $("#sumaDebe").val().replace(',','');
            var suma2 = $("#sumaHaber").val().replace(',','');

            var diferencia = suma1 - suma2;
            $("#diferencia").val(diferencia.toFixed(2));

            //alert(diferencia);

            if(diferencia == 0 || diferencia==""){ // si no hay diferencia
                $('#observaciones').val("");

                if(suma1 == 0 && suma2 == 0){ //por si se quiere enviar sin importes
                    $('#observaciones').val("incompleto");
                }
            }
            else{   //si hay diferencia
                $('#observaciones').val("incompleto");
            }
        }

        calcularDiferencia();

        function calcularSumasyDiferencias(){
            calcularSumaDebe();
            calcularSumaHaber();
            calcularDiferencia();
        }

        $('body').click(function () {
            calcularSumasyDiferencias();
        });
    </script>

    {{--! MOSTRAMOS LA CUENTA POR SUBCUENTA SELECCIONADA --}}
    <script>
        /* no se puede pasar array de php A js
        creo que lo envia como en una solacadena */

        /* $('.codigo').change(function(){
            //alert("estas aquí");
            $('.cuentaOculta').attr("type","text");
        }); */
    </script>

    {{--! interaccion select codigo con la cuenta --}}
    <script>
        $('.subcuenta').change(function(){
            // alert($(this).val()); //propiedad value actual del select
            var codigo_de_la_subcuenta = $(this).val();

            //pasos:
            //console.log($(this).parent().parent()); //estamos en el tr
            //alert($(this).parent().parent().find('.codigo').val()); //podemos interactuarcon el codigo de la misma fila
            //alert(codigo_de_la_subcuenta);

            $(this).parent().parent().find(".codigo option[value="+ codigo_de_la_subcuenta +"]").attr("selected",true);

            //con esto seleccionamos un elemento en select2
            //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
            $(this).parent().parent().find(".codigo").val(codigo_de_la_subcuenta).trigger('change.select2');
        });

        $('.codigo').change(function(){
            //var codigo_seleccionado =  $('option:selected',this).attr(''); //obtenemos el atributo personalizado del option
            //alert(codigo_seleccionado);

            var codigo_seleccionado =  $(this).val(); // no es necesario el nuevo atributo
            $(this).parent().parent().find(".subcuenta option[value="+ codigo_seleccionado +"]").attr("selected",true);
            $(this).parent().parent().find(".subcuenta").val(codigo_seleccionado).trigger('change.select2');
        });

    </script>

    {{--! agregar y eliminar filas (tambien se inicia select2 por cada fila agregada, para enventos dinamicos)--}}
    <script>

        //configuracion de eventos simples
        $("#btnNuevaFila").on('click',NuevaFila);

        //configuracion de asignacion de eventos a elemento dinamicos
        //nota: asegurarse que la clase exista en el elemento dinamico agregado

        $("body").on('click',".btnQuitarFila",QuitarFila);
        //asociamos un evento a elementos creados dinamicamente
        //en el caso de eliminar lo hacemos distinto por que se debe asignar esta funcion aun despues de cargar
        //todo el documento, es decir utilizamos objetos dinamicos al agregar filas y a los nuevos botones ->esto no funciona $(".btnQuitarFila").on('click',QuitarFila);

        $("body").on('change',".debe",calcularSumaDebe);
        $("body").on('change',".haber",calcularSumaHaber); // CONTENEDOR , EVENTO , OBJETO DINAMICO, FUNCION

        $("body").on('change',".subcuenta",function(){
            var codigo_de_la_subcuenta = $(this).val();
            $(this).parent().parent().find(".codigo option[value="+ codigo_de_la_subcuenta +"]").attr("selected",true);
            $(this).parent().parent().find(".codigo").val(codigo_de_la_subcuenta).trigger('change.select2');
        });

        $("body").on('change',".codigo",function(){
            var codigo_seleccionado =  $(this).val(); // no es necesario el nuevo atributo
            $(this).parent().parent().find(".subcuenta option[value="+ codigo_seleccionado +"]").attr("selected",true);
            $(this).parent().parent().find(".subcuenta").val(codigo_seleccionado).trigger('change.select2');
        });


        function NuevaFila()
        {
            //NOTAS
            //append - se encarga de agregar contenido al final del ya existente
            //aqui hacemos uso de attr y prop - es lo mismo pero lo mas recomentable es prop
            $("#tablaCodigoCuentaDebeHaber")
            .append
            (
                '<tr><td style="width: 20%"><select name="codigo[]" class="codigo form-control select2"><option value=""></option>@foreach ($sub_cuentas as $sub_cuenta)<option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">{{$sub_cuenta->id}}</option>@endforeach</select></td><td style="width: 30%"><select name="subcuenta[]" class="subcuenta form-control select2"><option value=""></option>@foreach ($sub_cuentas as $sub_cuenta)<option value="{{$sub_cuenta->id}}">{{$sub_cuenta->descripcion}}</option>@endforeach</select></td><td style="width: 20%"><input type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off"></td><td style="width: 20%"><input type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off"></td><td class="align-middle"><a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2"><i class="fas fa-times"></i></a></td></tr>'
            );

            //configuracion de asignacion de eventos a elemento dinamicos

            //volvemos a iniciar select2
            $('.select2').select2();
            //para que funcione select2 con varios select, mejor si no tiene id o si tiene id diferentes

            numeral_Debe_y_Haber(); //para dar formato a los nuevos elementos añadidos
        }

        function QuitarFila()
        {
            //console.log($(this).parent().parent());

            //por cada parent() me trae el objeto contenedor (jquery)
            // --- esto sirve pero no se actualiza
            //$(this).parent().parent().remove();

            // -- aqui utilizamos una animacion, primero ocultamos luego eliminamos
            $(this).parent().parent().fadeOut("slow",function(){ $(this).remove(); });
            //fadeOut("slow", callback }); fadeOut solo oculta

        }

    </script>


    {{--! Pregunta desea EDITAR COMPROBANTE--}}
    @if (Auth::user()->editar == 1)
        <script>

            $('.frmEditar-Comprobante').submit(function(e){
                e.preventDefault();

                // verificamos numero de comprobante
                if($('#nroComprobante').val() ==""){
                    toastr.error('Aún no tiene el NÚMERO DE COMPROBANTE generado. Por favor revise el Tipo de Comprobante y la fecha del mismo.');
                }
                else{
                    // contamos las filas de la tabla

                    var rowTableCount = $("#tablaCodigoCuentaDebeHaber tbody tr").length;
                    //alert(rowTableCount);
                    if(rowTableCount==0){
                        toastr.error('No tiene CUENTAS agregadas al Comprobante. Mínimamente se requiere de una.');
                    }
                    else{

                        //! PREGUNTA
                        if( $("#observaciones").val() != "" )
                        {
                            swal.fire("¡¡El Comprobante Contable no cuadra o no tiene importes!!, si continúa, se guardará con la observacion de INCOMPLETA.")

                            alert("¡¡El Comprobante Contable no cuadra o no tiene importes!!, si continúa, se guardará con la observacion de INCOMPLETA.");
                        }

                        Swal.fire({
                        title: '¿Desea Actualizar el Comprobante Contable ?',
                        text: "¡Guardar cambios del Asiento o Registro Contable!",
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

                    }
                }

            })
        </script>
    @else
        <script>
            $('.frmEditar-Comprobante').submit(function(e){
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

@endsection
