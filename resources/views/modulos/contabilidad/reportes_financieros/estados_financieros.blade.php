@extends('plantilla.adminlte')

@section('titulo')
    Estados Financieros
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
                            <a href="{{route('estados-financieros')}}">Estados Financieros Básicos</a>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                            <li class="breadcrumb-item active">Reportes</li>
                            <li class="breadcrumb-item active">Estados Financieros</li>
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
                <form method="GET" action="{{ route('estados-financieros') }}">
                    <div class="row">
                        {{--! Criterios de busqueda --}}

                        <input type="hidden" name="process" value="search">
                        {{--* fechas Del - Al --}}
                        @php
                            $fi = date('d/m/Y', strtotime($datosEjercicioActivo->fechaInicio));
                            $ff = date('d/m/Y', strtotime($datosEjercicioActivo->fechaCierre));
                        @endphp
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ESTADO DE RESULTADOS</label>
                                <label for="">Del:</label>
                                <input type="date" name="fechaInicio_er" class="form-control" value="{{$fechaInicio_buscado_er}}" required>
                                <small>Fecha Mínina: {{$fi}}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""></label>
                                <label>Al:</label>
                                <input type="date" name="fechaFin_er" class="form-control" value="{{$fechaFin_buscado_er}}" required>
                                <small>Fecha Máxima: {{$ff}}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Balance General</label>
                                <label>Al:</label>
                                <input type="date" name="fechaFin_bg" class="form-control" required value="{{$fechaFin_buscado_bg}}">
                                <small>Fecha Máxima: {{$ff}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- * Botones busqueda --}}
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-block btn-outline-info mt-2">
                                <i class="fas fa-file-invoice"></i>
                                Generar
                            </button>
                        </div>
                    </div>
                </form>
                {{--! Fin Buscador --}}
                <br>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-dark card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-ER-tab" data-toggle="pill" href="#custom-tabs-one-ER" role="tab" aria-controls="custom-tabs-one-ER" aria-selected="true">ESTADO DE RESULTADOS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-BG-tab" data-toggle="pill" href="#custom-tabs-one-BG" role="tab" aria-controls="custom-tabs-one-BG" aria-selected="false">BALANCE GENERAL</a>
                                </li>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-ER" role="tabpanel" aria-labelledby="custom-tabs-one-ER-tab">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{route('excel-estado-de-resultados',['fechaInicio_er'=>$fechaInicio_buscado_er,'fechaFin_er'=>$fechaFin_buscado_er,'fechaFin_bg'=>$fechaFin_buscado_bg])}}"
                                                    target="_blank" class="btn btn-block btn-outline-success mt-2">
                                                    <i class="fas fa-file-excel"></i>
                                                    Descargar
                                                </a>
                                            </div>
                                        </div>
                                        <br>
                                        @isset($acumulado_subcuentas_er)
                                            {{-- estado de resultados --}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-dark card-outline">
                                                        {{--! tabla --}}
                                                        <div class="card-body table-responsive p-2">
                                                            <table id="tablaER" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center align-middle">CÓDIGO</th>
                                                                        <th class="text-center align-middle">CUENTA</th>
                                                                        <th class="text-center">SALDO Bs.-</th>
                                                                        <th class="text-center align-middle">Mayor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="font-size: 16px">
                                                                    @foreach ($tipos_todos as $tipo )
                                                                        @if ($tipo->id == 4 || $tipo->id == 5)
                                                                            <tr class="text-blue text-bold" style="text-decoration: underline">
                                                                                <td>{{$tipo->id}}</td>
                                                                                <td>{{$tipo->descripcion}}</td>

                                                                                <td class="text-right">
                                                                                    @php
                                                                                        $saldo=0;                                                            
                                                                                    @endphp
                                                                                    @foreach ($acumulado_tipos_er as $tipo_er)
                                                                                        @if ($tipo_er->tipo_codigo == $tipo->id)
                                                                                            @php
                                                                                                if($tipo_er->tipo_codigo == 4){
                                                                                                    $saldo = $tipo_er->suma_haber - $tipo_er->suma_debe;
                                                                                                }
                                                                                                if($tipo_er->tipo_codigo == 5){
                                                                                                    $saldo = $tipo_er->suma_debe - $tipo_er->suma_haber;
                                                                                                }
                                                                                            @endphp
                                                                                        @endif
                                                                                    @endforeach
                                                                                    {{number_format($saldo,2,'.',',')}}
                                                                                </td>

                                                                                <td class="text-center">-</td>
                                                                            </tr>
                                                                            {{--! grupo --}}
                                                                            @foreach ( $grupos_todos as $grupo)
                                                                                @if ($grupo->tipo_codigo == $tipo->id)
                                                                                    <tr class="text-blue text-bold">
                                                                                        <td>{{$grupo->grupo_codigo}}</td>
                                                                                        <td>{{$grupo->grupo_descripcion}}</td>

                                                                                        <td class="text-right">
                                                                                            @php
                                                                                                $saldo=0;                                                            
                                                                                            @endphp
                                                                                            @foreach ($acumulado_grupos_er as $grupo_er)
                                                                                                @if ($grupo_er->tipo_codigo == $tipo->id)
                                                                                                    @if ($grupo_er->grupo_codigo == $grupo->grupo_codigo)
                                                                                                        @php
                                                                                                            if($grupo_er->tipo_codigo == 4){
                                                                                                                $saldo = $grupo_er->suma_haber - $grupo_er->suma_debe;
                                                                                                            }
                                                                                                            if($grupo_er->tipo_codigo == 5){
                                                                                                                $saldo = $grupo_er->suma_debe - $grupo_er->suma_haber;
                                                                                                            }
                                                                                                        @endphp
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endforeach
                                                                                            {{number_format($saldo,2,'.',',')}}
                                                                                        </td>

                                                                                        <td class="text-center">-</td>
                                                                                    </tr>

                                                                                    {{--! sub grupo --}}
                                                                                    @foreach ( $acumulado_subgrupos_er as $subgrupo_er)
                                                                                        @if ($subgrupo_er->tipo_codigo == $tipo->id)
                                                                                            @if ($subgrupo_er->grupo_id == $grupo->grupo_codigo)
                                                                                                <tr class="text-blue">
                                                                                                    <td>{{$subgrupo_er->subGrupo_codigo}}</td>
                                                                                                    <td>{{$subgrupo_er->subGrupo_descripcion}}</td>

                                                                                                    <td class="text-right">
                                                                                                        @php
                                                                                                            $saldo=0;                                                            
                                                                                                        @endphp
                                                                                                            @php
                                                                                                                if($subgrupo_er->tipo_codigo == 4){
                                                                                                                    $saldo = $subgrupo_er->suma_haber - $subgrupo_er->suma_debe;
                                                                                                                }
                                                                                                                if($subgrupo_er->tipo_codigo == 5){
                                                                                                                    $saldo = $subgrupo_er->suma_debe - $subgrupo_er->suma_haber;
                                                                                                                }
                                                                                                            @endphp
                                                                                                        {{number_format($saldo,2,'.',',')}}
                                                                                                    </td>

                                                                                                    <td class="text-center">-</td>
                                                                                                </tr>
                                                                                                
                                                                                                {{--! cuenta --}}
                                                                                                @foreach ( $acumulado_cuentas_er as $cuenta_er)
                                                                                                    @if ($cuenta_er->tipo_codigo == $tipo->id)
                                                                                                        @if ($cuenta_er->subGrupo_id ==  $subgrupo_er->subGrupo_codigo)
                                                                                                            <tr class="font-italic">
                                                                                                                <td>{{$cuenta_er->cuenta_codigo}}</td>
                                                                                                                <td>{{$cuenta_er->cuenta_descripcion}}</td>

                                                                                                                <td class="text-right">
                                                                                                                    @php
                                                                                                                        $saldo=0;                                                            
                                                                                                                    @endphp
                                                                                                                        @php
                                                                                                                            if($cuenta_er->tipo_codigo == 4){
                                                                                                                                $saldo = $cuenta_er->suma_haber - $cuenta_er->suma_debe;
                                                                                                                            }
                                                                                                                            if($cuenta_er->tipo_codigo == 5){
                                                                                                                                $saldo = $cuenta_er->suma_debe - $cuenta_er->suma_haber;
                                                                                                                            }
                                                                                                                        @endphp
                                                                                                                    {{number_format($saldo,2,'.',',')}}
                                                                                                                </td>

                                                                                                                <td class="text-center">-</td>
                                                                                                            </tr>

                                                                                                            {{--! sub cuenta --}}
                                                                                                            @foreach ( $acumulado_subcuentas_er as $subCuenta_er)
                                                                                                                @if ($subCuenta_er->tipo_codigo == $tipo->id)
                                                                                                                    @if ($subCuenta_er->cuenta_id == $cuenta_er->cuenta_codigo )
                                                                                                                        <tr>
                                                                                                                            <td>{{$subCuenta_er->subCuenta_codigo}}</td>
                                                                                                                            <td>{{$subCuenta_er->subCuenta_descripcion}}</td>

                                                                                                                            <td class="text-right">
                                                                                                                                @php
                                                                                                                                    $saldo=0;                                                            
                                                                                                                                @endphp
                                                                                                                                    @php
                                                                                                                                        if($subCuenta_er->tipo_codigo == 4){
                                                                                                                                            $saldo = $subCuenta_er->suma_haber - $subCuenta_er->suma_debe;
                                                                                                                                        }
                                                                                                                                        if($subCuenta_er->tipo_codigo == 5){
                                                                                                                                            $saldo = $subCuenta_er->suma_debe - $subCuenta_er->suma_haber;
                                                                                                                                        }
                                                                                                                                    @endphp
                                                                                                                                {{number_format($saldo,2,'.',',')}}
                                                                                                                            </td>

                                                                                                                            {{-- botones --}}
                                                                                                                            <td style="text-align: center">
                                                                                                                                <div class="btn-group btn-group-xs">
                                                                                                                                    <a href="{{'/contabilidad/pdf-mayor-analitico?id='.$subCuenta_er->subCuenta_codigo.'&fechaInicio_buscado='.$fechaInicio_buscado_er.'&fechaFin_buscado='.$fechaFin_buscado_er}}" target="_blank" role="button" class="btn btn-outline-dark btn-xs">
                                                                                                                                        <i class="fas fa-eye"></i>
                                                                                                                                    </a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endif
                                                                                                                @endif
                                                                                                            @endforeach

                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach

                                                                                                
                                                                                            @endif
                                                                                        @endif
                                                                                    @endforeach

                                                                                @endif
                                                                            @endforeach

                                                                        @endif
                                                                    @endforeach
                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            {{-- pie estado de resultados --}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-dark card-outline">
                                                        <div class="card-body table-responsive p-2">
                                                            @php
                                                                $total_Ingresos=0;
                                                                $total_Gastos=0;

                                                                foreach ($acumulado_tipos_er as $tipo_er) {
                                                                    if($tipo_er->tipo_codigo == 4){
                                                                        $total_Ingresos = $tipo_er->suma_haber - $tipo_er->suma_debe;
                                                                    }
                                                                    if($tipo_er->tipo_codigo == 5){
                                                                        $total_Gastos = $tipo_er->suma_debe - $tipo_er->suma_haber;
                                                                    }
                                                                }

                                                                $texto_resultado ="";
                                                                if($total_Ingresos > $total_Gastos){
                                                                    $texto_resultado ="Utilidad del Ejercicio";
                                                                }
                                                                else {
                                                                    $texto_resultado ="Pérdida del Ejercicio";
                                                                }
                                                                
                                                                $numero_resultado = $total_Ingresos - $total_Gastos;
                                                            @endphp
                                                            
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Ingresos:</label>
                                                                        <input type="text" value="{{number_format($total_Ingresos,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(215, 215, 245)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>(Menos) Gastos:</label>
                                                                        <input type="text" value="{{number_format($total_Gastos,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(215, 215, 245)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>{{$texto_resultado}}:</label>
                                                                        <input type="text" value="{{number_format($numero_resultado,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(215, 215, 245)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset

                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-one-BG" role="tabpanel" aria-labelledby="custom-tabs-one-BG-tab">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{route('excel-balance-general',['fechaInicio_er'=>$fechaInicio_buscado_er,'fechaFin_er'=>$fechaFin_buscado_er,'fechaFin_bg'=>$fechaFin_buscado_bg])}}"
                                                    target="_blank" class="btn btn-block btn-outline-success mt-2">
                                                    <i class="fas fa-file-excel"></i>
                                                    Descargar
                                                </a>
                                            </div>
                                        </div>
                                        <br>
                                        @isset($acumulado_subcuentas_bg)
                                            {{-- balance general --}}
                                            {{--! calculo del resultado en el ejercicio buscado --}}
                                                @php
                                                    $total_Ingresos=0;
                                                    $total_Gastos=0;

                                                    foreach ($acumulado_tipos_bg as $tipo_bg) {
                                                        if($tipo_bg->tipo_codigo == 4){
                                                            $total_Ingresos = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                                                        }
                                                        if($tipo_bg->tipo_codigo == 5){
                                                            $total_Gastos = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                                                        }
                                                    }

                                                    $texto_resultado ="";
                                                    if($total_Ingresos > $total_Gastos){
                                                        $texto_resultado ="Utilidad del Ejercicio";
                                                    }
                                                    else {
                                                        $texto_resultado ="Pérdida del Ejercicio";
                                                    }
                                                    
                                                    $numero_resultado = $total_Ingresos - $total_Gastos;
                                                @endphp

                                                @php
                                                    $total_activo=0;
                                                    $total_pasivo=0;
                                                    $total_patrimonio=0;
                        
                                                    foreach ($acumulado_tipos_bg as $tipo_bg) {
                                                        if($tipo_bg->tipo_codigo == 1){
                                                            $total_activo = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                                                        }
                                                        if($tipo_bg->tipo_codigo == 2){
                                                            $total_pasivo = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                                                        }
                                                        if($tipo_bg->tipo_codigo == 3){
                                                            $total_patrimonio = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                                                        }
                                                    }

                                                    $total_patrimonio_2= $total_patrimonio + $numero_resultado;
                                                    $total_pasivo_y_patrimonio= $total_patrimonio_2 + $total_pasivo;
                        
                                                @endphp
                                            {{--! fin calculo del resultado en el ejercicio buscado --}}

                                            {{-- balance general contenido--}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-dark card-outline">
                                                        {{--! tabla --}}
                                                        <div class="card-body table-responsive p-2">
                                                            <table id="tablaBG" class="table table-head-fixed text-nowrap table-striped table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center align-middle">CÓDIGO</th>
                                                                        <th class="text-center align-middle">CUENTA</th>
                                                                        <th class="text-center">SALDO Bs.-</th>
                                                                        <th class="text-center align-middle">Mayor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="font-size: 16px">
                                                                    @foreach ($tipos_todos as $tipo )
                                                                        @if ($tipo->id == 1 || $tipo->id == 2 || $tipo->id == 3)
                                                                            <tr class="text-blue text-bold" style="text-decoration: underline">
                                                                                <td>{{$tipo->id}}</td>
                                                                                <td>{{$tipo->descripcion}}</td>
                                    
                                                                                <td class="text-right">
                                                                                    @php
                                                                                        $saldo=0;                                                            
                                                                                    @endphp
                                                                                    @foreach ($acumulado_tipos_bg as $tipo_bg)
                                                                                        @if ($tipo_bg->tipo_codigo == $tipo->id)
                                                                                            @php
                                                                                                if($tipo_bg->tipo_codigo == 1){
                                                                                                    $saldo = $tipo_bg->suma_debe - $tipo_bg->suma_haber;
                                                                                                }

                                                                                                if($tipo_bg->tipo_codigo == 2){
                                                                                                    $saldo = $tipo_bg->suma_haber - $tipo_bg->suma_debe;
                                                                                                }
                                                                                                if($tipo_bg->tipo_codigo == 3){
                                                                                                    $saldo = $tipo_bg->suma_haber - $tipo_bg->suma_debe + $numero_resultado;
                                                                                                }
                                                                                            @endphp
                                                                                        @endif
                                                                                    @endforeach
                                                                                    {{number_format($saldo,2,'.',',')}}
                                                                                </td>
                                    
                                                                                <td class="text-center">-</td>
                                                                            </tr>
                                                                            {{--! grupo --}}
                                                                            @foreach ( $grupos_todos as $grupo)
                                                                                @if ($grupo->tipo_codigo == $tipo->id)
                                                                                    <tr class="text-blue text-bold">
                                                                                        <td>{{$grupo->grupo_codigo}}</td>
                                                                                        <td>{{$grupo->grupo_descripcion}}</td>
                                    
                                                                                        <td class="text-right">
                                                                                            @php
                                                                                                $saldo=0;                                                            
                                                                                            @endphp
                                                                                            @foreach ($acumulado_grupos_bg as $grupo_bg)
                                                                                                @if ($grupo_bg->tipo_codigo == $tipo->id)
                                                                                                    @if ($grupo_bg->grupo_codigo == $grupo->grupo_codigo)
                                                                                                        @php
                                                                                                            if($grupo_bg->tipo_codigo == 1){
                                                                                                                $saldo = $grupo_bg->suma_debe - $grupo_bg->suma_haber;
                                                                                                            }

                                                                                                            if($grupo_bg->tipo_codigo == 2){
                                                                                                                $saldo = $grupo_bg->suma_haber - $grupo_bg->suma_debe;
                                                                                                            }
                                                                                                            if($grupo_bg->tipo_codigo == 3){
                                                                                                                $saldo = $grupo_bg->suma_haber - $grupo_bg->suma_debe + $numero_resultado;
                                                                                                            }
                                                                                                        @endphp
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endforeach
                                                                                            {{number_format($saldo,2,'.',',')}}
                                                                                        </td>
                                    
                                                                                        <td class="text-center">-</td>
                                                                                    </tr>
                                    
                                                                                    {{--! sub grupo --}}
                                                                                    @foreach ( $acumulado_subgrupos_bg as $subgrupo_bg)
                                                                                        @if ($subgrupo_bg->tipo_codigo == $tipo->id)
                                                                                            @if ($subgrupo_bg->grupo_id == $grupo->grupo_codigo)
                                                                                                <tr class="text-blue">
                                                                                                    <td>{{$subgrupo_bg->subGrupo_codigo}}</td>
                                                                                                    <td>{{$subgrupo_bg->subGrupo_descripcion}}</td>
                                    
                                                                                                    <td class="text-right">
                                                                                                        @php
                                                                                                            $saldo=0;                                                            
                                                                                                        @endphp
                                                                                                            @php
                                                                                                                if($subgrupo_bg->tipo_codigo == 1){
                                                                                                                    $saldo = $subgrupo_bg->suma_debe - $subgrupo_bg->suma_haber;
                                                                                                                }

                                                                                                                if($subgrupo_bg->tipo_codigo == 2){
                                                                                                                    $saldo = $subgrupo_bg->suma_haber - $subgrupo_bg->suma_debe;
                                                                                                                }
                                                                                                                if($subgrupo_bg->tipo_codigo == 3){
                                                                                                                    $saldo = $subgrupo_bg->suma_haber - $subgrupo_bg->suma_debe + $numero_resultado;
                                                                                                                }
                                                                                                            @endphp
                                                                                                        {{number_format($saldo,2,'.',',')}}
                                                                                                    </td>
                                    
                                                                                                    <td class="text-center">-</td>
                                                                                                </tr>
                                                                                                
                                                                                                {{--! cuenta --}}
                                                                                                @foreach ( $acumulado_cuentas_bg as $cuenta_bg)
                                                                                                    @if ($cuenta_bg->tipo_codigo == $tipo->id)
                                                                                                        @if ($cuenta_bg->subGrupo_id ==  $subgrupo_bg->subGrupo_codigo)
                                                                                                            <tr class="font-italic">
                                                                                                                <td>{{$cuenta_bg->cuenta_codigo}}</td>
                                                                                                                <td>{{$cuenta_bg->cuenta_descripcion}}</td>
                                    
                                                                                                                <td class="text-right">
                                                                                                                    @php
                                                                                                                        $saldo=0;                                                            
                                                                                                                    @endphp
                                                                                                                        @php
                                                                                                                            if($cuenta_bg->tipo_codigo == 1){
                                                                                                                                $saldo = $cuenta_bg->suma_debe - $cuenta_bg->suma_haber;
                                                                                                                            }

                                                                                                                            if($cuenta_bg->tipo_codigo == 2){
                                                                                                                                $saldo = $cuenta_bg->suma_haber - $cuenta_bg->suma_debe;
                                                                                                                            }
                                                                                                                            if($cuenta_bg->tipo_codigo == 3){
                                                                                                                                $saldo = $cuenta_bg->suma_haber - $cuenta_bg->suma_debe + $numero_resultado;
                                                                                                                            }
                                                                                                                        @endphp
                                                                                                                    {{number_format($saldo,2,'.',',')}}
                                                                                                                </td>
                                    
                                                                                                                <td class="text-center">-</td>
                                                                                                            </tr>
                                    
                                                                                                            {{--! sub cuenta --}}
                                                                                                            @foreach ( $acumulado_subcuentas_bg as $subCuenta_bg)
                                                                                                                @if ($subCuenta_bg->tipo_codigo == $tipo->id)
                                                                                                                    @if ($subCuenta_bg->cuenta_id == $cuenta_bg->cuenta_codigo )
                                                                                                                        <tr>
                                                                                                                            <td>{{$subCuenta_bg->subCuenta_codigo}}</td>
                                                                                                                            <td>{{$subCuenta_bg->subCuenta_descripcion}}</td>
                                    
                                                                                                                            <td class="text-right">
                                                                                                                                @php
                                                                                                                                    $saldo=0;                                                            
                                                                                                                                @endphp
                                                                                                                                    @php
                                                                                                                                        if($subCuenta_bg->tipo_codigo == 1){
                                                                                                                                            $saldo = $subCuenta_bg->suma_debe - $subCuenta_bg->suma_haber;
                                                                                                                                        }

                                                                                                                                        if($subCuenta_bg->tipo_codigo == 2){
                                                                                                                                            $saldo = $subCuenta_bg->suma_haber - $subCuenta_bg->suma_debe;
                                                                                                                                        }
                                                                                                                                        if($subCuenta_bg->tipo_codigo == 3){
                                                                                                                                            $saldo = $subCuenta_bg->suma_haber - $subCuenta_bg->suma_debe;
                                                                                                                                        }
                                                                                                                                    @endphp
                                                                                                                                {{number_format($saldo,2,'.',',')}}
                                                                                                                            </td>
                                    
                                                                                                                            {{-- botones --}}
                                                                                                                            <td style="text-align: center">
                                                                                                                                <div class="btn-group btn-group-xs">
                                                                                                                                    <a href="{{'/contabilidad/pdf-mayor-analitico?id='.$subCuenta_bg->subCuenta_codigo.'&fechaInicio_buscado='.$fechaInicio_buscado_bg.'&fechaFin_buscado='.$fechaFin_buscado_bg}}" target="_blank" role="button" class="btn btn-outline-dark btn-xs">
                                                                                                                                        <i class="fas fa-eye"></i>
                                                                                                                                    </a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endif
                                                                                                                @endif
                                                                                                            @endforeach
                                    
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                    
                                                                                                
                                                                                            @endif
                                                                                        @endif
                                                                                    @endforeach
                                    
                                                                                @endif
                                                                            @endforeach
                                    
                                                                        @endif
                                                                    @endforeach
                                                                    <tr>
                                                                        <td>3010101006</td>
                                                                        <td>Resultado del Ejercicio</td>
                                                                        <td class="text-right">{{number_format($numero_resultado,2,'.',',')}}</td>

                                                                        <td style="text-align: center">
                                                                            <div class="btn-group btn-group-xs">
                                                                                <a href="#" class="btn btn-outline-dark btn-xs">
                                                                                    <i class="fas fa-eye-slash "></i>
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                    
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            {{-- pie balance general --}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-dark card-outline">
                                                        <div class="card-body table-responsive p-2">                                                            
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Activo:</label>
                                                                        <input type="text" value="{{number_format($total_activo,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(157, 255, 235)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <hr>
                                                                    <div class="form-group">
                                                                        <label>Pasivo:</label>
                                                                        <input type="text" value="{{number_format($total_pasivo,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(215, 215, 245)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>(Más) Patrimonio:</label>
                                                                        <input type="text" value="{{number_format($total_patrimonio_2,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(215, 215, 245)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Pasivo y Patrimonio:</label>
                                                                        <input type="text" value="{{number_format($total_pasivo_y_patrimonio,2,'.',',')}}" 
                                                                        class="form-control text-right" style="background-color: rgb(157, 255, 235)" readonly>
                                                                        <small>Bolivianos</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset
                                        
                                    </div>
                                
                                </div>
                            </div>
                            <!-- /.card -->
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


    {{--! mensajes de error --}}
        @if (Session('error')=='fechas_de_busqueda')
        <script>
                toastr.warning('Por favor revise la fecha, es posible que no se encuentra dentro del periodo que comprende el ejercicio contable.')
        </script>
        @endif
    {{--! mensajes de error --}}


@endsection
