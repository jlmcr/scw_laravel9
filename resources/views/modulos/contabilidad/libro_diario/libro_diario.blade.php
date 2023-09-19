@extends('plantilla.adminlte')

@section('titulo')
    Libro Diario
@endsection

@section('css')
    {{--! DataTables --}}
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{--! fixed columns --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/custom-code/FixedColumns-4.1.0/css/fixedColumns.dataTables.css') }}">

@endsection

@section('contenido')
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <a href="{{route('libro-diario')}}">
                            <h1 class="m-0">Libro Diario</h1>
                        </a>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Libro Diario</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">

                {{--! Buscador --}}
                <form method="GET" action="{{ route('libro-diario') }}">
                    <div class="row">
                        {{--! Criterios de busqueda --}}

                        <input type="hidden" name="process" value="search">
                        {{--* fechas Del - Al --}}
                        @php
                            $fi = date('d/m/Y', strtotime($datosEjercicioActivo->fechaInicio));
                            $ff = date('d/m/Y', strtotime($datosEjercicioActivo->fechaCierre));
                        @endphp
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Del:</label>
                                <input type="date" name="fechaInicio" class="form-control" value="{{$fechaInicio_buscado}}" required>
                                <small>Fecha Mínina: {{$fi}}</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Al:</label>
                                <input type="date" name="fechaFin" class="form-control" value="{{$fechaFin_buscado}}" required>
                                <small>Fecha Máxima: {{$ff}}</small>
                            </div>
                        </div>

                        {{-- * Botones busqueda --}}
                        <div class="col-md-2">
                            <label for=""></label>
                            <button type="submit" class="btn btn-block btn-outline-info mt-2">
                                <i class="fas fa-search"> </i>
                                Buscar
                            </button>
                        </div>

                        <div class="col-md-2">
                            <label for=""></label>
                            <a href="{{route('pdf-libro-diario',["fechaInicio_buscado"=>$fechaInicio_buscado,"fechaFin_buscado"=>$fechaFin_buscado])}}"
                                target="_blank" class="btn btn-block btn-outline-danger mt-2">
                                <i class="fas fa-file-pdf"></i>
                                Libro Diario
                            </a>
                        </div>
                        <div class="col-md-2">
                            <label for=""></label>
                            <a href="{{route('excel-libro-diario',["fechaInicio_buscado"=>$fechaInicio_buscado,"fechaFin_buscado"=>$fechaFin_buscado])}}" 
                                target="_blank" class="btn btn-block btn-outline-success mt-2" id="btnExportarExcel">
                                <i class="fas fa-file-excel"></i> Libro Diario
                            </a>
                        </div>

                        <div class="col-md-2">
                            <label for=""></label>
                            <a href="{{route('pdf-comprobantes-varios',["fechaInicio_buscado"=>$fechaInicio_buscado,"fechaFin_buscado"=>$fechaFin_buscado])}}"
                                target="_blank" class="btn btn-block btn-outline-danger mt-2">
                                <i class="fas fa-file-pdf"></i>
                                Comprobantes
                            </a>
                        </div>
                    </div>
                </form>
                {{--! Fin Buscador --}}

                <br>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark card-outline">

                            {{--! tabla --}}
                            <div class="card-body table-responsive p-2">
                                @isset($comprobantesEncontrados)
                                <table id="tablaLibroDiario" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Nro. Compr.</th>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Notas</th>
                                            <th class="text-center">Observaciones</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 14px">
                                        @foreach ($comprobantesEncontrados as $comprobante )
                                            <tr>
                                                @php
                                                    $f = explode('-',$comprobante->fecha);
                                                    $f2 = $f[2]."/".$f[1]."/".$f[0];
                                                @endphp
                                                <td class="text-center">{{$f2}}</td>

                                                <td class="text-center">{{$comprobante->nroComprobante}}</td>
                                                <td class="text-center">
                                                    <span class="badge p-2 w-100 {{$comprobante->tipo->color}}">
                                                        {{$comprobante->tipo->nombre}}
                                                    </span>
                                                </td>

                                                @php
                                                    if(strlen($comprobante->concepto) > 50){
                                                        $concepto = substr($comprobante->concepto,0,50)."...";
                                                    }
                                                    else{
                                                        $concepto = $comprobante->concepto;
                                                    }
                                                @endphp
                                                <td>{{$concepto}}</td>

                                                @php
                                                    if(strlen($comprobante->notas) > 50){
                                                        $notas = substr($comprobante->notas,0,50)."...";
                                                    }
                                                    else{
                                                        $notas = $comprobante->notas;
                                                    }
                                                @endphp
                                                <td>{{$notas}}</td>

                                                <td style="font-size: 16px">
                                                    @if ($comprobante->observaciones =="incompleto")
                                                        <span class="badge bg-yellow p-2">
                                                            incompleto
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- botones --}}
                                                <td style="text-align: center">
                                                    <form  action="{{route ('comprobante.destroy',$comprobante->id)}}" method="POST" class="frmEliminar-Comprobante">
                                                        @csrf
                                                        @method('DELETE')

                                                        <input type="hidden" name="fechaInicio_eliminar" value="{{$fechaInicio_buscado}}">
                                                        <input type="hidden" name="fechaFin_eliminar" value="{{$fechaFin_buscado}}">

                                                        <div class="btn-group btn-group-xs">
                                                            <a role="button" class="btn btn-outline-success btnVerComprobante btn-xs"
                                                            data-toggle="modal" data-target="#modal-ver-comprobante"
                                                            idComprobante="{{$comprobante->id}}"
                                                            fecha="{{$comprobante->fecha}}"
                                                            numero="{{$comprobante->nroComprobante}}"
                                                            notas="{{$comprobante->notas}}"
                                                            concepto="{{$comprobante->concepto}}"
                                                            tipo="{{$comprobante->tipo->nombre}}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>

                                                            <a href="{{route('pdf-comprobante-individual',['id'=>$comprobante->id])}}" target="_blank" role="button" class="btn btn-outline-dark btn-xs">
                                                                <i class="fas fa-print"></i>
                                                            </a>

                                                            <a href="{{route('comprobante.edit',$comprobante->id)}}" target="_blank" role="button" class="btn btn-outline-info btn-xs">
                                                                <i class="fas fa-pen"></i>
                                                            </a>

                                                            <button type="submit" class="btn btn-outline-danger btn-xs"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Nro. Comprobante</th>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Notas</th>
                                            <th class="text-center">Observaciones</th>
                                            <th class="text-center bg-white">Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @endisset
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{--* modal --}}
            {{--! modal Editar--}}
            <div class="modal fade" id="modal-ver-comprobante">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <h4 class="modal-title">Vista de Registro Contable</h4>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label class="mb-0" for="fecha_ver">
                                        Fecha:
                                    </label>
                                    <input id="fecha_ver" type="text" class="form-control form-control" style="background-color: rgb(252, 249, 156)" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="mb-0" for="numero_ver">
                                        Número Comprobante:
                                    </label>
                                    <input id="numero_ver" type="text" class="form-control form-control" style="background-color: rgb(252, 249, 156)" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="mb-0" for="tipo_ver">
                                        Tipo Comprobante:
                                    </label>
                                    <input id="tipo_ver" type="text" class="form-control form-control" style="background-color: rgb(252, 249, 156)" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <div class="table-responsive p-2">
                                        <table class="table table-hover table-bordered" id="tablaDetalleComprobante">
                                            <thead class="text-center">
                                                <th style="width: 70px;">CÓDIGO</th>
                                                <th>CUENTA</th>
                                                <th>DEBE</th>
                                                <th>HABER</th>
                                                <th>MAYOR</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="concepto_ver">Concepto:</label>
                                    <p id="concepto_ver"></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="notas_ver">Notas</label>
                                    <p id="notas_ver"></p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <div class="form-group">
                                <a id="modal-btn-imprimir" href="" target="_blank" role="button" class="btn btn-outline-dark">
                                    <i class="fas fa-print"></i>
                                </a>

                                <a id="modal-btn-editar" href="" target="_blank" role="button" class="btn btn-outline-info">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </div>

                            <button type="button" class="btn btn-danger col-md-2" data-dismiss="modal">
                                Cerrar
                            </button>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            {{--! Fin modal Editar--}}
            {{--* Fin modal --}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuLibroDiario').addClass('active');
    </script>

    {{--! este mensaje es recibido al ELIMINAR --}}
    @if (Session('eliminar')=='ok')
        <script>
                toastr.success('Registro Contable anulado exitosamente.')
        </script>
    @endif

    {{--! mensajes de error --}}
        @if (Session('error')=='fechas_de_busqueda')
        <script>
                toastr.warning('Por favor revise la fecha, es posible que no se encuentra dentro del periodo que comprende el ejercicio contable.')
        </script>
        @endif
    {{--! mensajes de error --}}

    {{--! Pregunta Eliminar --}}
    <script>
        $('.frmEliminar-Comprobante').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Desea Anular el Registro Contable?',
            text: "¡No podrá recuperar datos!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#11151c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Anular',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    //enviamos el formulario
                    this.submit();
                }
            })
        })
    </script>

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
            $("#tablaLibroDiario").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": false,
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
                "scrollY": '600px',
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns":
                {
                    "left": false,
                    "right": 1,
                }
            }).buttons().container().appendTo('#tablaLibroDiario_wrapper .col-md-6:eq(0)');
        });
    </script>

    {{--! ajax ver--}}
    <script>
        /* CON JQUERY */

        let fechaInicio_bucado = "{{$fechaInicio_buscado}}";
        let fechaFin_buscado = "<?php echo $fechaFin_buscado ?>";
        //alert(fechaFin_buscado);


        $(".btnVerComprobante").click(function() {
            // datos generales extraidos del boton
            let idComprobante = $(this).attr("idComprobante");
            //alert(idComprobante);

            //modifcamos la ruta del boton para editar
            let rutaEdicion ="/contabilidad/comprobante/"+idComprobante+"/edit";
            $("#modal-btn-editar").prop("href",rutaEdicion);
            //alert($("#modal-btn-editar").prop("href"));

            //modifcamos la ruta del boton para imprimir
            let rutaImpresion ="/contabilidad/imprimir-pdf-comprobante?id="+idComprobante;
            $("#modal-btn-imprimir").prop("href",rutaImpresion);

            let fecha = $(this).attr("fecha");
            let numero = $(this).attr("numero");
            let tipo = $(this).attr("tipo");
            let concepto = $(this).attr("concepto");
            let notas = $(this).attr("notas");

            //removemos filas del modal
            $('.fila-detalle').remove();

            $("#fecha_ver").val("");
            $("#numero_ver").val("");
            $("#tipo_ver").val("");
            $("#concepto_ver").html("");
            $("#notas_ver").html("");


            $.ajax({
                url: "{{ route('search.ComprobanteDetalle') }}",
                type: "GET",
                datatype: 'json',
                data: { idComprobante: idComprobante },
                success: function(response) {
                    //console.log(response);

                    // datos generales extraidos del boton
                    let fecha_ = (fecha).split("-");//a-m-d
                    $("#fecha_ver").val(fecha_[2]+"-"+fecha_[1]+"-"+fecha_[0]);

                    $("#numero_ver").val(numero);
                    $("#tipo_ver").val(tipo);
                    $("#concepto_ver").html(concepto);
                    $("#notas_ver").html(notas);

                    //sub cuentas
                    $.each(response, function(index, value) {
                        $("#tablaDetalleComprobante")
                        .append
                        (
                            '<tr class="fila-detalle">'+
                                '<td>'+value.codigo+'</td>'+
                                '<td>'+value.descripcion+'</td>'+
                                '<td class="text-right">'+value.debe+'</td>'+
                                '<td class="text-right">'+value.haber+'</td>'+
                                '<td class="text-center">'+
                                    '<a href="/contabilidad/pdf-mayor-analitico?id='+value.codigo+'&fechaInicio_buscado='+fechaInicio_bucado+'&fechaFin_buscado='+fechaFin_buscado+'" target="_blank">ver aquí...</a>'+
                                '</td>'+
                            '</tr>'
                        );
                    });

                }
            })
        });
    </script>
@endsection
