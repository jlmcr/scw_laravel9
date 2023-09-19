@extends('plantilla.adminlte')

@section('titulo')
    Cotizaciones
@endsection

@section('css')
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <a href="{{ route('tipoCambio.index') }}">
                            Cotizaciones de la Unidad de Fomento a la Vivienda
                            </a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Cotizaciones</li>
                            <li class="breadcrumb-item active">UFV</li>
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
                <form method="GET" action="{{ route('tipoCambio.index') }}">
                    <div class="row">
                        {{--* gestion --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gestión</label>
                                <select name="gestion" id="gestion" class="form-control" style="width: 100%;" required>
                                    @php
                                        $anio =  date("Y");
                                        $anioReferencia = $anioMinimo;//añp minimo
                                    @endphp
                                    @while ($anio >= $anioReferencia)
                                        @if (isset($gestionBuscada))
                                            @if ($gestionBuscada == $anio)
                                                <option value="{{$anio}}" selected>{{$anio}}</option>
                                            @else
                                                <option value="{{$anio}}">{{$anio}}</option>
                                            @endif
                                        @else
                                            <option value="{{$anio}}">{{$anio}}</option>
                                        @endif

                                        @php
                                            $anio = $anio-1;
                                        @endphp
                                    @endwhile
                                </select>
                            </div>
                        </div>

                        {{--* mes --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mes</label>
                                <select name="mes" id="mes" class="form-control" style="width: 100%;" required>
                                    @if (isset($mesBuscado))
                                        @if ($mesBuscado == 1)
                                            <option value="1" selected>Enero</option>
                                        @else
                                            <option value="1">Enero</option>
                                        @endif

                                        @if ($mesBuscado == 2)
                                            <option value="2" selected>Febrero</option>
                                        @else
                                            <option value="2">Febrero</option>
                                        @endif

                                        @if ($mesBuscado == 3)
                                            <option value="3" selected>Marzo</option>
                                        @else
                                            <option value="3">Marzo</option>
                                        @endif

                                        @if ($mesBuscado == 4)
                                            <option value="4" selected>Abril</option>
                                        @else
                                            <option value="4">Abril</option>
                                        @endif

                                        @if ($mesBuscado == 5)
                                            <option value="5" selected>Mayo</option>
                                        @else
                                            <option value="5">Mayo</option>
                                        @endif

                                        @if ($mesBuscado == 6)
                                            <option value="6" selected>Junio</option>
                                        @else
                                            <option value="6">Junio</option>
                                        @endif

                                        @if ($mesBuscado == 7)
                                            <option value="7" selected>Julio</option>
                                        @else
                                            <option value="7">Julio</option>
                                        @endif

                                        @if ($mesBuscado == 8)
                                            <option value="8" selected>Agosto</option>
                                        @else
                                            <option value="8">Agosto</option>
                                        @endif

                                        @if ($mesBuscado == 9)
                                            <option value="9" selected>Septiembre</option>
                                        @else
                                            <option value="9">Septiembre</option>
                                        @endif

                                        @if ($mesBuscado == 10)
                                            <option value="10" selected>Octubre</option>
                                        @else
                                            <option value="10">Octubre</option>
                                        @endif

                                        @if ($mesBuscado == 11)
                                            <option value="11" selected>Noviembre</option>
                                        @else
                                            <option value="11">Noviembre</option>
                                        @endif

                                        @if ($mesBuscado == 12)
                                            <option value="12" selected>Diciembre</option>
                                        @else
                                            <option value="12">Diciembre</option>
                                        @endif
                                    @else
                                        <option value=""></option>
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- * Botones busqueda --}}
                        <div class="col-md-2">
                            <label></label>
                            <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                                Buscar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="button" role="button"  data-toggle="modal" data-target="#modal-crear-cotizacion" class="btn btn-block btn-outline-success mt-2">
                                <i class="fas fa-plus"></i>
                                Nuevo
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="button" role="button"  class="btn btn-block btn-outline-info mt-2" data-toggle="modal" data-target="#modal-importar-cotizaciones">
                                <i class="fas fa-file-import"></i>
                                Importar Datos
                            </button>
                        </div>
                    </div>
                </form>
                {{--* Fin Buscador --}}
                <br>
                @if (isset($errors) && $errors->any())
                    <div class="row">
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error )
                                {{$error}}
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ! DataTable--}}
                <div class="row" >
                    <div class="col-lg-12">
                        <div class="card card-dark">

                            {{--! inicio tarjeta --}}
                            <div class="card-header">
                                <h3 class="card-title">Cotizaciones consultadas</h3>
                            </div>

                            {{--! tabla --}}
                            <div class="card-body table-responsive p-2">
                                <table id="tablaCotizaciones" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Tipo de Cambio UFV</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cotizaciones_encontradas as $cotizacion)
                                            <tr>
                                                @php
                                                    $f = explode('-',$cotizacion->fecha);
                                                    $fec = $f[2]."/".$f[1]."/".$f[0]
                                                @endphp
                                                <td>{{ $fec }}</td>

                                                <td class="text-right">{{ $cotizacion->ufv }}</td>
                                                {{-- botones --}}
                                                <td style="text-align: center">
                                                    <form  action="{{route ('tipoCambio.destroy',$cotizacion->id)}}" method="POST" class="frmEliminar-cotizacion">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="btn-group btn-group">
                                                            <a role="button" class="btn btn-outline-info btn-xs"
                                                                data-toggle="modal" data-target="#modal-editar-cotizacion{{$cotizacion->id}}">
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

                {{-- ! Fin DataTable--}}
            </div>

            {{--! modal IMPORTAR --}}
            <div class="modal fade" id="modal-importar-cotizaciones">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="cursor: move;">
                            <h4 class="modal-title" style="cursor: text;"><b>Importar</b> Cotizaciones</h4>
                        </div>

                        <form action="{{route('importar-tipos-de-cambio')}}" method="post" enctype="multipart/form-data" class="frmImportar-Cotizaciones">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="archivo">Seleccione el archivo Excel</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" accept=".xlsx, .xls" id="archivo" name="archivo" class="custom-file-input" required>
                                                <label class="custom-file-label" for="archivo"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <a href="{{ asset('storage/plantillas')."/plantilla-para-importar-cotizaciones.xlsx"}}">
                                            Descargar Plantilla...
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-info col-md-2">Importar</button>
                                <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{--! Fin modal IMPORTAR --}}

            {{--! modal crear cotizacion --}}
            <div class="modal fade" id="modal-crear-cotizacion">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Cotización</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('tipoCambio.store')}}" class="frmCrear-Cotizacion" >
                            @csrf

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="date-mask-input-c">Fecha</label>
                                        <input name="fecha" id="date-mask-input-c" type="text" class="form-control"
                                        placeholder="dd/mm/aaaa" autocomplete="off" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="ufv">Tipo de Cambio de UFV</label>
                                        {{-- step="any" --}}
                                        <input name="ufv" id="ufv" type="text" class="ufv form-control" placeholder="0.00000"
                                        autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary col-md-3">Guardar</button>
                                <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            {{--! Fin modal crear cotizacion --}}

            {{--! modal editar cotizacion --}}
            @foreach ($cotizaciones_encontradas as $cotizacion)
            <div class="modal fade" id="modal-editar-cotizacion{{$cotizacion->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar Cotización</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('tipoCambio.update',$cotizacion->id)}}" class="frmEditar-Cotizacion" >
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @php
                                            $f = explode('-',$cotizacion->fecha);
                                            $fec = $f[2]."/".$f[1]."/".$f[0]
                                        @endphp

                                        <label for="date-mask-input-c">Fecha</label>
                                        <input name="fecha" id="date-mask-input-c" type="text" class="form-control"
                                        placeholder="dd/mm/aaaa" autocomplete="off" value="{{$fec}}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="ufv">Tipo de Cambio de UFV</label>
                                        <input name="ufv" id="ufv" type="text" class="ufv form-control" placeholder="0.00000"
                                        autocomplete="off" value="{{$cotizacion->ufv}}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary col-md-3">Actualizar</button>
                                <button type="button" class="btn btn-danger col-md-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            @endforeach
            {{--! Fin modal editar cotizacion --}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
@endsection

@section('js')

    {{-- Unidad de Fomento de Vivienda UFV. Creada mediante Decreto Supremo N° 26390 --}}

    {{--! menu actual --}}
    <script>
        $('#menuCotizacion').addClass('active');
    </script>

    {{--! Select 2 --}}
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

    {{--! file --}}
    <script src="{{asset('/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

    {{--! libreria numeral --}}
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>

    {{--! Formatos en los input ufv --}}
    <script>
    //formato - para clases
    $(".ufv").change(
        function() {
            //alert( this.value);
            var auxiliar = numeral(this.value).format('0.00000');
            this.value = auxiliar;
        }
    )

    //limites - para clases
    $(".ufv").keypress(
        function() {
                //alert( this.value);
                if (this.value.length > 6) {
                //devuelve una copia de una parte del array
                this.value = this.value.slice(0,6); // cuenta tambien el 0 excepto el 7vo
            }
        }
    )
    </script>

    {{--! este mensaje es por ERROR EN FECHAS --}}
    @if (Session('fechas')=='error')
    <script>
            toastr.error('Por favor, revise las fechas del buscador.');
    </script>
    @endif
    @if (Session('fecha_')=='error')
    <script>
            toastr.error('El dato que ingresó no es una fecha.');
    </script>
    @endif
    @if (Session('ufv_error')=='error')
    <script>
            toastr.error('Error en el registro, para UFVs solo se permite un numero entero y 5 decimales.');
    </script>
    @endif

    {{--! este mensaje es recibido al CREAR NUEVA COTIZACION --}}
    @if (Session('crear')=='ok')
    <script>
            toastr.success('Cotización creada exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al ACTUALIZAR COTIZACION --}}
    @if (Session('actualizar')=='ok')
    <script>
            toastr.success('Datos actualizados con éxito.');
    </script>
    @endif

    {{--! este mensaje es recibido al ELIMINAR COTIZACION --}}
    @if (Session('eliminar')=='ok')
    <script>
            toastr.success('Cotización eliminada exitosamente.');
    </script>
    @endif

    {{--! este mensaje es recibido al IMPORTAR DESDE EL EXCEL --}}
    @if (Session('importarExcel')=='ok')
    <script>
            toastr.success('Cotizaciones importadas exitosamente.')
    </script>
    @endif

    @error('archivo')
    <script>
        toastr.error('Verifique el archivo seleccionado.')
    </script>
    @enderror

    {{--     un conjunto de tipos de cambio es una cotizacion
    es una tabla de cotizaciones
    un tipo de cambio es cuanto vale una moeda en relacion a otra --}}
    {{-- el tipo de cambio es parte de la cotizacion --}}

    {{--! Pregunta desea IMPORTAR COTIZACION--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmImportar-Cotizaciones').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea importar los Tipos de Cambio?',
                text: "¡Agregará una nuevas Cotizaciones!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Importar',
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
            $('.frmImportar-Cotizaciones').submit(function(e){
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

    {{--! Pregunta desea CREAR COTIZACION--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmCrear-Cotizacion').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea crear el Tipo de Cambio?',
                text: "¡Agregará una nueva Cotización!",
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
            $('.frmCrear-Cotizacion').submit(function(e){
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

    {{--! Pregunta desea EDITAR --}}
    @if (Auth::user()->editar == 1)
        <script>
            $('.frmEditar-Cotizacion').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar cambios del Tipo de Cambio de ésta fecha?',
                text: "¡Actualizará la tabla de cotizaciones!",
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
            $('.frmEditar-Cotizacion').submit(function(e){
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
            $('.frmEliminar-cotizacion').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea Eliminar la Cotización de ésta fecha?',
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
            $('.frmEliminar-cotizacion').submit(function(e){
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
        $("#tablaCotizaciones").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy","excel"],
            "aaSorting": [], //desabilitamos el orden automatico
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
        }).buttons().container().appendTo('#tablaCotizaciones_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection
