@extends('plantilla.adminlte')

@section('titulo')
    Sumas y Saldos
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
                            <a href="{{route('balance-de-sumas-y-saldos')}}">Balance de Comprobanción de Sumas y Saldos</a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Reportes</li>
                            <li class="breadcrumb-item active">Sumas y Saldos</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--! Buscador --}}
                <form method="GET" action="{{ route('balance-de-sumas-y-saldos') }}">
                    <div class="row">
                        {{--! Criterios de busqueda --}}

                        <input type="hidden" name="process" value="search">
                        {{--* fechas Del - Al --}}
                        @php
                            $fi = date('d/m/Y', strtotime($datosEjercicioActivo->fechaInicio));
                            $ff = date('d/m/Y', strtotime($datosEjercicioActivo->fechaCierre));
                        @endphp
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Del:</label>
                                <input type="date" name="fechaInicio" class="form-control" value="{{$fechaInicio_buscado}}" required>
                                <small>Fecha Mínina: {{$fi}}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                                <i class="fas fa-file-invoice"></i>
                                Generar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label for=""></label>
                            <a href="{{route('pdf-balance-de-sumas-y-saldos',["fechaInicio_buscado"=>$fechaInicio_buscado,"fechaFin_buscado"=>$fechaFin_buscado] )}}"
                                target="_blank" class="btn btn-block btn-outline-danger mt-2">
                                <i class="fas fa-file-pdf"></i>
                                Reporte Pdf
                            </a>
                        </div>
                        <div class="col-md-2">
                            <label for=""></label>
                            <a href="{{route('excel-balance-de-sumas-y-saldos',["fechaInicio_buscado"=>$fechaInicio_buscado,"fechaFin_buscado"=>$fechaFin_buscado] )}}" 
                                class="btn btn-outline-success mt-2 w-100" id="btnExportarExcel">
                                <i class="fas fa-file-excel"></i> Excel
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
                                @isset($registrosBCSS_entontrados)
                                <table id="tablaBCSS" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">NRO.</th>
                                            <th rowspan="2" class="text-center align-middle">CÓDIGO</th>
                                            <th rowspan="2" class="text-center align-middle">SUB-CUENTA</th>
                                            <th rowspan="2" class="text-center align-middle">TIPO</th>
                                            <th colspan="2" class="text-center">MOVIMIENTOS/SUMAS</th>
                                            <th colspan="2" class="text-center">SALDOS</th>
                                            <th rowspan="2" class="text-center align-middle">Mayor</th>
                                        </tr>
                                        <tr>
                                            {{-- <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th> --}}
                                            <th class="text-center">DEBE</th>
                                            <th class="text-center">HABER</th>
                                            <th class="text-center">DEUDOR</th>
                                            <th class="text-center">ACREEDOR</th>
                                            {{-- <th></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 14px">
                                        @php // declaramos la variable, no la imprimimos aun
                                            $numero = 0;
                                        @endphp
                                        @foreach ($registrosBCSS_entontrados as $registro )
                                            <tr>
                                                <td class="text-center">{{ $numero = $numero + 1 }}</td>

                                                <td class="text-center">{{$registro->subcuenta_id}}</td>

                                                <td>{{$registro->descripcion}}</td>

                                                <td class="text-center">{{$registro->descripcion_tipo}}</td>

                                                <td importe="{{$registro->suma_debe}}" class="col_debe text-right">
                                                    {{number_format($registro->suma_debe,2,'.',',')}}
                                                </td>

                                                <td importe="{{$registro->suma_haber}}" class="col_haber text-right">
                                                    {{number_format($registro->suma_haber,2,'.',',')}}
                                                </td>

                                                @php
                                                    $deudor=0;
                                                    $acreedor=0;
                                                    $debe = $registro->suma_debe;
                                                    $haber = $registro->suma_haber;

                                                    if($debe > $haber){
                                                        $deudor = $debe - $haber;
                                                    }
                                                    else {
                                                        $acreedor = $haber - $debe;
                                                    }
                                                @endphp
                                                <td importe="{{$deudor}}" class="col_deudor text-right">
                                                    {{number_format($deudor,2,'.',',')}}
                                                </td>

                                                <td importe="{{$acreedor}}" class="col_acreedor text-right">
                                                    {{number_format($acreedor,2,'.',',')}}
                                                </td>

                                                {{-- botones --}}
                                                <td style="text-align: center">
                                                    <div class="btn-group btn-group-xs">
                                                        <a href="{{'/contabilidad/pdf-mayor-analitico?id='.$registro->subcuenta_id.'&fechaInicio_buscado='.$fechaInicio_buscado.'&fechaFin_buscado='.$fechaFin_buscado}}" target="_blank" role="button" class="btn btn-outline-dark btn-xs">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        {{--! calculado con jquery --}}
                                        <tr>
                                            <th colspan="4" class="text-center">SUMAS IGUALES</th>
                                            <th id="footer_debe" class="text-right">0</th>
                                            <th id="footer_haber" class="text-right">0</th>
                                            <th id="footer_deudor" class="text-right">0</th>
                                            <th id="footer_acreedor" class="text-right">0</th>
                                            <th></th>
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

            {{--* Fin modal --}}
        </section>
        {{-- ! Fin Contenido --}}
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuSumasySaldos').addClass('active');
    </script>

    {{--! libreria numeral --}}
    {{-- <script src = "//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> --}}
    <script src="{{ asset('/custom-code/adamwdraper-Numeral-js-2.0.6/numeral.js') }}"></script>


    {{--! mensajes de error --}}
        @if (Session('error')=='fechas_de_busqueda')
        <script>
                toastr.warning('Por favor revise la fecha, es posible que no se encuentra dentro del periodo que comprende el ejercicio contable.')
        </script>
        @endif
    {{--! mensajes de error --}}

    {{--! calculo de footer --}}
    <script>
        var total_debe=0;
        let total_haber=0;
        let total_deudor=0;
        let total_acreedor=0;
        let importe=0;

        //COLUMNA DEBE
        $(".col_debe").each(function (indexInArray, valueOfElement) {
            importe = parseFloat($(this).attr("importe")); // convertimos para poder sumar
            total_debe = (total_debe + importe) ;
        });
        $("#footer_debe").html(numeral(total_debe).format('0,0.00')); //usamos libreria numeral

        //COLUMNA HABER
        importe=0;
        $(".col_haber").each(function (indexInArray, valueOfElement) {
            importe = parseFloat($(this).attr("importe")); // convertimos para poder sumar
            total_haber = (total_haber + importe) ;
        });
        $("#footer_haber").html(numeral(total_haber).format('0,0.00'));

        //COLUMNA DEUDOR
        importe=0;
        $(".col_deudor").each(function (indexInArray, valueOfElement) {
            importe = parseFloat($(this).attr("importe")); // convertimos para poder sumar
            total_deudor = (total_deudor + importe) ;
        });
        $("#footer_deudor").html(numeral(total_deudor).format('0,0.00'));

        //COLUMNA ACREEDOR
        importe=0;
        $(".col_acreedor").each(function (indexInArray, valueOfElement) {
            importe = parseFloat($(this).attr("importe")); // convertimos para poder sumar
            total_acreedor = (total_acreedor + importe) ;
        });
        $("#footer_acreedor").html(numeral(total_acreedor).format('0,0.00'));

        //alert(total_debe);

        //““var”” es la manera más antigua de declarar variables. No es muy estricta en cuanto al alcance, ya que al declarar variables de esta forma, dichas variables podrán ser accedidas, e incluso modificadas, tanto dentro como fuera de los bloques internos en una función.

        //Con ““let”” por otra parte, el alcance se reduce al bloque (las llaves) en el cual la variable fue declarada. Fuera de este bloque la variable no existe. Una vez declarada la variable con let, no se puede volver a declarar con en ninguna otra parte de la función.
    </script>
    {{--! calculo de footer --}}

@endsection
