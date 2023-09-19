@extends('plantilla.adminlte')

@section('titulo')
    Activo Fijo
@endsection
@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{--! fixed columns --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/custom-code/FixedColumns-4.1.0/css/fixedColumns.dataTables.css') }}">


    {{--! /* Quitamos flechas del imput number */ --}}
    <style>
        /*input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
        input[type=number] { -moz-appearance:textfield; } */
        .InputNumeroSinFlechas::-webkit-inner-spin-button,
        #InputNumeroSinFlechas::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
        #InputNumeroSinFlechas { -moz-appearance:textfield; }
    </style>

@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <a href="/activoFijo">Listado de activo fijo</a>
                        </h1>
                        <small class="text-muted">Se muetra el listado de los activos fijos registrados para la <b>Empresa Activa</b></small>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Activo Fijo</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--* Buscador --}}
                <form method="GET" action="{{ route('activoFijo.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{--* select rubros --}}
                                <label>Categoría/Rubro:</label>
                                <select name="id_rubro_buscado" id="id_rubro_buscado" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach ($rubros as $rubro)
                                        @if ($rubro_buscado != "" and $rubroSeleccionado != '-1')
                                            @if ($rubro->id == $rubro_buscado->id)
                                                <option value="{{ $rubro->id }}" selected>
                                                    {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                                </option>
                                            @else
                                                <option value="{{ $rubro->id }}">
                                                    {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $rubro->id }}">
                                                {{ $rubro->rubro." (".$rubro->cantidad_activos_registrados." items)" }}
                                            </option>
                                        @endif
                                    @endforeach
                                    {{-- todos --}}
                                    @if ($rubroSeleccionado == '-1')
                                        <option value="-1" selected>TODOS</option>
                                    @else
                                        <option value="-1">TODOS</option>
                                    @endif

                                </select>
                                {{--* select rubros --}}
                            </div>
                        </div>
                        {{-- * Botones busqueda --}}
                        <div class="col-md-2">
                            <label></label>
                            {{-- btn-outline-primary --}}
                            <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                                Buscar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="button" role="button"  class="btn btn-block btn-outline-success mt-2" data-toggle="modal" data-target="#modal-crear-activo">
                                <i class="fas fa-plus"></i>
                                Nuevo Activo
                            </button>
                        </div>
                        @isset($rubroSeleccionado)
                        <div class="col-md-2">
                            <label></label>
                            <a href="{{route('pdf-listado-activo-fijo',['id_rubro_buscado'=>$rubroSeleccionado])}}" target="_blank" class="btn btn-block btn-outline-info mt-2">
                                <i class="fas fa-print"></i>
                                Lista PDF
                            </a>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <a href="{{route('excel-listado-activo-fijo',['id_rubro_buscado'=>$rubroSeleccionado])}}" target="_blank" class="btn btn-block btn-outline-success mt-2">
                                <i class="fas fa-file-excel"></i>
                                Lista Excel
                            </a>
                        </div>
                        @endisset
                    </div>
                </form>
                {{--* Fin Buscador --}}
                <br>
                {{-- ! DataTable de ACTIVO FIJO--}}
                {{-- rubro_buscado  es un array
                rubroSeleccionado es un numero - el id del rubro enviado --}}
                @if ($rubroSeleccionado != "")
                    @if (isset($activosFijosEncontrados))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-dark">

                                    {{--! inicio tarjeta --}}
                                    @if ($rubro_buscado == "todos"){{-- CUANDO SELECCIONAMOS TODOS ESTA VARIABLE SE ENVIA "todos" DESDE EL CONTROLADOR --}}
                                        <div class="card-header">
                                            <h3 class="card-title">Categoría/Rubro: TODOS</h3>
                                        </div>
                                        @else
                                        <div class="card-header">
                                            <h3 class="card-title">Categoría/Rubro: {{ $rubro_buscado->rubro  }}</h3>
                                            <br>
                                            <h3 class="card-title">ID: {{ $rubro_buscado->id }}</h3>
                                        </div>
                                    @endif
                                    {{--! tabla  table-responsive --}}
                                    <div class="card-body p-2">
                                        <table id="tablaActivos" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    {{--  <th>Nro.</th> --}}
                                                    <th>Item</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Medida</th>
                                                    <th>Situación</th>
                                                    <th>Estado del A.F.</th>
                                                    @if ($rubroSeleccionado == '-1')
                                                        <th>Categoría/Rubro</th>
                                                    @endif
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php // declaramos la variable, no la imprimimos aun
                                                    $numero = 0;
                                                @endphp
                                                @foreach ($activosFijosEncontrados as $activo)
                                                    <tr>
                                                        {{-- <td>{{ $numero = $numero + 1 }}</td> --}}
                                                        <td>{{ $activo->id }}</td>
                                                        <td>{{ $activo->activoFijo }}</td>
                                                        <td style="text-align: center">
                                                            <span class="badge bg-cyan p-2">
                                                                {{$activo->cantidad}}
                                                            </span>
                                                        </td>
                                                        <td>{{ $activo->medida }}</td>
                                                        <td>{{ $activo->situacion}}</td>
                                                        <td style="text-align: center">
                                                            @if ($activo->estadoAF == "ALTA")
                                                            <span class="badge bg-cyan p-2">
                                                                {{$activo->estadoAF}}
                                                            </span>
                                                            @else
                                                            <span class="badge bg-lightblue p-2">
                                                                {{$activo->estadoAF}}
                                                            </span>
                                                            @endif
                                                        </td>
                                                        @if ($rubroSeleccionado == '-1')
                                                            <td>{{$activo->activosFijos_rubros->rubro}}</td>
                                                        @endif
                                                        {{-- botones --}}
                                                        <td style="text-align: center">
                                                            <form  action="{{route ('activoFijo.destroy',$activo->id)}}" method="POST" class="frmEliminar-Activo">
                                                                @csrf
                                                                @method('DELETE')
                                                                {{-- para editar un activo uilizamos una clase - y para elli usamos JQUERY --}}
                                                                <div class="btn-group btn-group">
                                                                    <a role="button" class="btn btn-outline-info btn-xs btnEditarActivo"
                                                                        data-toggle="modal" data-target="#modal-editar-activo{{$activo->id}}">
                                                                        <i class="fas fa-pen"></i>
                                                                    </a>
                                                                    <button type="submit" class="btn btn-outline-danger btn-xs"><i class="fas fa-trash-alt"></i></button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {{-- ! Fin DataTable de ACTIVO FIJO--}}
            </div>

            {{--! modal crear ACTIVO --}}
            <div class="modal fade" id="modal-crear-activo">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Activo Fijo</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="/activoFijo" class="frmCrear-Activo" >
                            @csrf
                            <div class="modal-body">

                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        {{--* select rubros --}}
                                        <label>Categoría/Rubro del Activo Fijo:</label>
                                        <select name="id_rubro_nuevo_activo" id="id_rubro_nuevo_activo" class="form-control select2" style="width: 100%;" required>
                                                        <option value=""></option>
                                            @foreach ($rubros as $rubro)
                                                @if ($rubro_buscado != "")
                                                    @if ($rubro->id == $rubroSeleccionado)
                                                        <option value="{{ $rubro->id }}" selected>{{ $rubro->rubro }}</option>
                                                    @else
                                                        <option value="{{ $rubro->id }}">{{ $rubro->rubro }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $rubro->id }}">{{ $rubro->rubro }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        {{--* select rubros --}}
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <input type="hidden" id="id_empresa_activa" name="id_empresa_activa" class="form-control" value="{{Auth::user()->idEmpresaActiva}}" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6">
                                            <label for="nombre">Decripción o Nombre:</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control text-uppercase" placeholder="Nombre o Descripcion" autocomplete="off" maxlength="100" title="Breve descripcion o nombre general del activo | 100 carácteres"  required>
                                    </div>
                                    <div class="form-group col-lg-3">
                                            <label for="cantidad">Cantidad:</label>
                                            <input type="text" id="cantidad" name="cantidad" class="cantidad form-control" placeholder="Número entero" autocomplete="off" maxlength="5" title="Cantidad | hasta 5 digitos" required>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="medida">Medida:</label>
                                        <input type="text" list="listaMedida" id="medida" name="medida" class="form-control text-uppercase" placeholder="Medida" autocomplete="off"  value="UNIDAD (ES)" maxlength="30" title="Concepto de medición del activo | 30 carácteres" required>
                                        {{-- <input type="text" list="listaMedida"> --}}
                                        <datalist id="listaMedida">
                                            <option value="UNIDAD (ES)">
                                            <option value="EQUIPO (S)">
                                            <option value="COMPONENTE (S)">
                                            <option value="PAQUETE (S)">
                                            <option value="SERIE (S)">
                                        </datalist>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-3">
                                            <label for="valorInicial">Valor Actual o incial:</label>
                                            <input type="text" id="valorInicial" name="valorInicial" class="form-control InputNumeroSinFlechas" placeholder="Valor monetario" autocomplete="off" value="0" required>
                                            <small class="text-muted">Separador decimal: "." (2 decimales)</small>
                                    </div>
                                    <div class="form-group col-lg-3">
                                            <label for="depAcumInicial">Depreciacion acumulada:</label>
                                            <input type="text" id="depAcumInicial" name="depAcumInicial" class="form-control InputNumeroSinFlechas" placeholder="Valor monetario" autocomplete="off" value="0">
                                            <small class="text-muted">Separador decimal: "." (2 decimales)</small>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="situacion">Situación:</label>
                                        <select name="situacion" id="situacion" class="form-control" required>
                                            <option value=""></option>
                                            <option value="NUEVO">NUEVO</option>
                                            <option value="SALDO">SALDO</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="estadoAF">Estado del Activo:</label>
                                        <select name="estadoAF" id="estadoAF" class="form-control" required>
                                            <option value=""></option>
                                            <option value="ALTA">ALTA</option>
                                            <option value="BAJA">BAJA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label class="" data-toggle="tooltip" data-placement="top" title="Será utilizada como fecha inicial al realizar la reexpresión (para el uso de las ufv's) del activo fijo."
                                        for="date-mask-input-a" style="cursor: pointer">
                                            Fecha registro en sistema/ingreso a empresa:
                                            <i class="fas fa-info-circle"></i>
                                        </label>
                                        <input type="text" id="date-mask-input-a" name="fechaRegistro" class="fecha form-control" placeholder="DD/MM/AAAA" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                            <label for="documento">Documento respaldatorio:</label>
                                            <select name="documento" id="documento" class="form-control">
                                                <option value=""></option>
                                                <option value="Factura">Factura</option>
                                                <option value="Recibo">Recibo</option>
                                                <option value="Nota de Venta">Nota de Venta</option>
                                                <option value="Contrato">Contrato</option>
                                                <option value="Balance de Apertura">Balance de Apertura</option>
                                                <option value="Balance General Anterior">Balance General Anterior</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="numeroDoc">Número o Código Doc.:</label>
                                        <input type="text" id="numeroDoc" name="numeroDoc" class="form-control" placeholder="Identificador Doc." maxlength="30" title="En caso de existir, un identificador del documento respaldatorio | 30 carácteres" autocomplete="off">
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
            {{--! Fin modal crear ACTIVO --}}

            {{--! modal editar ACTIVO --}}
            @foreach ($activosFijosEncontrados as $activo)
                <div class="modal fade" id="modal-editar-activo{{$activo->id}}">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Editar Activo Fijo</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="/activoFijo/{{$activo->id}}" class="frmEditar-Activo" >
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <h4 class="text-red">ITEM: {{$activo->id}}</h4>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                                <label for="">Decripción o Nombre:</label>
                                                <input type="text" id="nombre" name="nombre" class="form-control text-uppercase" placeholder="Nombre o Descripcion" autocomplete="off" maxlength="100" maxlength="100" title="Breve descripcion o nombre general del activo | 100 carácteres" value="{{ $activo->activoFijo }}" required>
                                        </div>
                                        <div class="form-group col-lg-3">
                                                <label for="cantidad">Cantidad:</label>
                                                <input type="text" id="cantidad" name="cantidad" class="cantidad form-control" placeholder="Número entero" autocomplete="off" maxlength="5" title="Cantidad | hasta 5 digitos" value="{{ $activo->cantidad }}" required>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="medida">Medida:</label>
                                            <input type="text" list="listaMedida" id="medida" name="medida" class="form-control text-uppercase" placeholder="Medida" autocomplete="off" maxlength="30" title="Concepto de medición del activo | 30 carácteres" value="{{ $activo->medida }}"  required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                                <label>Valor incial:</label>
                                                <input type="text" name="valorInicial" class="form-control InputNumeroSinFlechas valorInicial_Editar" placeholder="Valor monetario" autocomplete="off"
                                                value="{{ number_format($activo->valorInicial,2,'.',',') }}" required>
                                                <small class="text-muted">Separador decimal: "." (2 decimales)</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                                <label>Dep. acumulada inicial:</label>
                                                <input type="text" name="depAcumInicial" class="form-control InputNumeroSinFlechas depAcumInicial_Editar" placeholder="Valor monetario" autocomplete="off"
                                                value="{{ number_format($activo->depAcumInicial,2,'.',',') }}">
                                                <small class="text-muted">Separador decimal: "." (2 decimales)</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="situacion">Situación:</label>
                                            <select name="situacion" id="situacion" class="form-control" required>
                                                <option value="{{ $activo->situacion }}">{{ $activo->situacion }}</option>
                                                <option value="NUEVO">NUEVO</option>
                                                <option value="SALDO">SALDO</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-3">
                                            <label for="estadoAF">Estado del Activo:</label>
                                            <select name="estadoAF" id="estadoAF" class="form-control" required>
                                                <option value="{{ $activo->estadoAF }}">{{ $activo->estadoAF }}</option>
                                                <option value="ALTA">ALTA</option>
                                                <option value="BAJA">BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            @php
                                                if($activo->fechaCompraRegistro != "")
                                                {
                                                    $fi = explode('-',$activo->fechaCompraRegistro);
                                                    $fi2 = $fi[2]."/".$fi[1]."/".$fi[0];
                                                }
                                                else
                                                {
                                                    $fi2="";
                                                }
                                            @endphp

                                            <label class="" data-toggle="tooltip" data-placement="top" title="Será utilizada como fecha inicial al realizar la reexpresión (para el uso de las ufv's) del activo fijo."
                                            for="date-mask-input-b" style="cursor: pointer">
                                                Fecha registro en sistema/ingreso a empresa:
                                                <i class="fas fa-info-circle"></i>
                                            </label>
                                            <input type="text" id="date-mask-input-b" name="fechaRegistro" class="fecha form-control" placeholder="DD/MM/AAAA" autocomplete="off" value="{{$fi2}}" required>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="documento">Documento respaldatorio:</label>
                                            <select name="documento" id="documento" class="form-control">
                                                <option value="{{ $activo->documento }}">{{ $activo->documento }}</option>
                                                <option value="Factura">Factura</option>
                                                <option value="Recibo">Recibo</option>
                                                <option value="Nota de Venta">Nota de Venta</option>
                                                <option value="Contrato">Contrato</option>
                                                <option value="Balance de Apertura">Balance de Apertura</option>
                                                <option value="Balance General Anterior">Balance General Anterior</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="numeroDoc">Número o Código Doc.:</label>
                                            <input type="text" id="numeroDoc" name="numeroDoc" class="form-control" placeholder="Identificador Doc." autocomplete="off" maxlength="30" title="En caso de existir, un identificador del documento respaldatorio | 30 carácteres" value="{{ $activo->numeroDocumento }}">
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
            {{--! Fin modal editar ACTIVO --}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuActivoFijo').addClass('active');
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
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{-- ! uso de la libreria numeral --}}
    <script>
        //oninput //! al cambiar algo dentro del input
        //onchange //! al cambiar de input

        valorInicial.onchange = function()
        {
            //alert(valorInicial.value);
            //alert(numeral(valorInicial.value).format('0,0.00'));
            var auxiliar = numeral(valorInicial.value).format('0,0.00');
            valorInicial.value = auxiliar;
        }
        depAcumInicial.onchange = function()
        {
            depAcumInicial.value = numeral(depAcumInicial.value).format('0,0.00');
        }

        /* EDICIONES */

        //APUNTES
        //como tenemos varios input con id iguales: no podemos usar las mismas funciones que en crear
        //una opcion es usar ajax
        //esta vex optamos por usar jquery
        //seleccionamos las clases: no funciona seleccionando las clases con js nativo
        //con change: metodo de jquery que detecta el cambio de valor del elemento seleccionado
        //? ahorramos el tener que cambiar id en el formulario y nombres de inputs en el formulario y controlador, ventaja de ajax carga más rapido cuando sean muchos activos
        //desventaja de usar en esto jquery: el uso de mascara de fechas no funcionara, mas tiempo de carga, aun falta comprobar si funciona

        $(".valorInicial_Editar").change(function() {
                //alert( this.value);
                var auxiliar = numeral(this.value).format('0,0.00');
                this.value = auxiliar;
            }
        )
        $(".depAcumInicial_Editar").change(function() {
                //alert( this.value);
                this.value = numeral(this.value).format('0,0.00');
            }
        )

        //NOTAS:
        /* para crear y editar un activo siempre se pide 4 decimales,
        para mostrar se muestra dependiendo de la configuracion del usuario
        en base de datos siempre deben estar 4 decimales*/

    </script>

    {{--! este mensaje es recibido al CREAR NUEVO ACTIVO --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Activo Fijo creado exitosamente.')
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.')
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Activo Fijo eliminado exitosamente.')
    </script>
    @endif

    {{--! Pregunta desea CREAR RUBRO--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Activo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear el Activo Fijo?',
                text: "¡Creará una nuevo Activo Fijo!",
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
            })
        </script>
    @else
        <script>
            $('.frmCrear-Activo').submit(function(e){
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

    {{--! Pregunta desea EDITAR ACTIVO FIJO--}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Activo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios al Activo Fijo?',
                text: "¡Actualizará el Activo Fijo!",
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
            $('.frmEditar-Activo').submit(function(e){
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

    {{--! Pregunta Eliminar RUBRO --}}
    @if (Auth::user()->eliminar == 1)
        <script>
            $('.frmEliminar-Activo').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Está seguro que desea ELIMINAR el Activo Fijo?',
                text: "Eliminará juntamente su historial de depreciaciones. ¡No podrá recuperar datos!",
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
            $('.frmEliminar-Activo').submit(function(e){
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

    {{--! mascara fecha start ui--}}
    <script src="{{ asset('/custom-code/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/custom-code/input-mask/input-mask-init.js') }}"></script>


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
    {{--! fixed columns --}}
    <script src="{{ asset('/custom-code/FixedColumns-4.1.0/js/dataTables.fixedColumns.js') }}"></script>

    {{--! DATATABLE --}}
    <script>
        $(function () {
            $("#tablaActivos").DataTable({
                "responsive": false,
                "lengthChange": true,//cambio de los items que se veran
                "autoWidth": false,
                 /* "dom": 'lrtip' quita el buscador https://datatables.net/reference/option/dom */
                "aaSorting": [],//desabilitamos el orden automatico

                "language":
                {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "lengthMenu": "Mostrar " +
                    `<select class='form-control input-sm'>
                    <option value='5'>5</option>
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
                "scrollY": '350px',
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns":
                {
                    "left": false,
                    "right": 1,
                }
            }).buttons().container().appendTo('#tablaActivos_wrapper .col-md-6:eq(0)');
        });
    </script>

    {{--! validacion de año ingresado en inputs --}}
    <script>
        $(".fecha").change(function () {

            //valor del input
            let str = $(this).val();
            let arr = str.split('/');
            //alert(arr);

            //año actual
            let today = new Date();
            let year = today.getFullYear();
            //alert(today);

            if(arr[2] > year){
                toastr.warning("Tome en cuenta que acaba de ingresar una fecha que supera el año actual");
            }
            if(arr[2] < year){
                toastr.warning("Tome en cuenta que acaba de ingresar una anterior al año actual");
            }
        });
    </script>

    {{--! solo numeros en años de vida util --}}
    <script>
        $(".cantidad").on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, '')); //reemplazamos digitos que no sean del solo de 0 al 9
        });

        $(".cantidad").change(function () {
            if($(this).val() < 0){
                toastr.error("Los años de vida util no pueden inferior a 0 años");
                $(this).val(0);
            }
        });
    </script>
@endsection
