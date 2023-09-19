@extends('plantilla.adminlte')

@section('titulo')
    Depreciación
@endsection

@section('css')
    {{--! Select2 --}}
    <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .encabezado-cuadro tr th,
        .encabezado-cuadro tr{
            border-spacing: 0;
            border-collapse: collapse;
            border: 0.1px solid #ffffff !important;
        }
    </style>
@endsection

@section('contenido')
<div class="content-wrapper">
    {{-- ! Encabezado --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <a href="{{route('nueva-depreciacion.create')}}">Cuadro de Depreciación/Nueva depreciación</a>
                        </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                        <li class="breadcrumb-item"><a href="/activoFijo">Activo Fijo</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('historial-depreciaciones') }}">Historial</a></li>
                        <li class="breadcrumb-item active">Depreciación</li>
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
        <form method="GET" action="{{ route('nueva-depreciacion.create') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{--* select rubros --}}
                        <label>Categoría/Rubro:</label>
                        <select name="id_rubro_buscado" id="id_rubro_buscado" class="form-control select2" style="width: 100%;" required>
                            <option></option>
                            @foreach ($rubros as $rubro)
                                @if ($rubro_buscado_datos != "" && $idRubroSeleccionado != '-1')
                                    @if ($rubro->id == $rubro_buscado_datos->id)
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
                        </select>
                        {{--* select rubros --}}
                    </div>
                </div>

                <div class="col-md-3">
                    <label>Ejercicio Contable</label>
                    <select name="id_ejercicio_buscado" id="id_ejercicio_buscado" class="form-control select2" style="width: 100%;" required>
                        <option></option>
                        @foreach ($ejercicios_de_la_empresa as $ejercicio)
                            @if ($idEjercicioSeleccionado == $ejercicio->id)
                                <option value="{{ $ejercicio->id }}" selected>{{ $ejercicio->ejercicioFiscal }}</option>
                            @else
                                <option value="{{ $ejercicio->id }}">{{ $ejercicio->ejercicioFiscal }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label></label>
                    <button type="submit" class="btn btn-block btn-outline-info mt-2"><i class="fas fa-search"> </i>
                        Previsualizar
                    </button>
                </div>
            </div>
        </form>
        {{--* Fin Buscador --}}
        <br>
        {{-- ! DataTable de ACTIVO FIJO--}}
        @if (isset($idRubroSeleccionado))
            @if ($idRubroSeleccionado != "")
                @if (isset($activosFijos_encontrados))
                    <div class="row">
                        <div class="col-12">

                            <form class="frmNuevas-Depreciaciones" action="{{route('nueva-depreciacion.store')}}" method="POST">
                                @csrf

                                {{--! para retornar a la pagina --}}
                                @php
                                    $urlPagina = $_SERVER['REQUEST_URI'];
                                @endphp
                                <input type="hidden" value="{{$urlPagina}}" name="urlPagina">

                                <div class="card">
                                    <!-- ./card-header botones del segundo target-->
                                    <div class="card-header">

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>

                                        {{-- $cantidadDepreciaciones[0]->cantidad  obtenido con select - devuelve un array por eso usamos [] --}}
                                        @if ($cantidadDepreciaciones[0]->cantidad == 0)
                                            <h2 class="card-title">Cuadro de depreciación</h2>
                                            @else
                                            <h3 class="card-title">
                                                Cuadro de depreciación  <br> <b class="text-red">(la Categoría o Rubro ya tiene algunos datos guardados y se pintan de color rojo)</b>
                                            </h3>
                                        @endif

                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <button type="submit" class="btn btn-outline-success form-control">
                                                    <i class="fas fa-save"></i>
                                                    Guardar Datos
                                                </button>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <a href="{{route('pdf-cuadro-de-depreciacion',['id_rubro_buscado'=>$idRubroSeleccionado,'id_ejercicio_buscado'=>$idEjercicioSeleccionado])}}"
                                                target="_blank" class="btn btn-outline-danger form-control">
                                                    <i class="fas fa-file-pdf"></i>
                                                    PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./card-header botones del segundo target-->

                                    <div class="card-body table-responsive p-2">
                                        <table class="table table-bordered table-hover nowrap" id="tablaDepreciaciones">
                                            <thead class="encabezado-cuadro">
                                                <tr style="background-color: rgb(193, 193, 207)">
                                                    {{-- align-middle  text-center items-center --}}
                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Item/Código
                                                    </th>
                                                   {{--  <th rowspan="2" class="align-middle text-center items-center">
                                                        Estado
                                                    </th> --}}
                                                    <th rowspan="2" class="align-middle text-center items-center" style="padding-left: 100px; padding-right: 100px;">
                                                        Descripción
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Cantidad
                                                    </th>
                                                    <th colspan="3" class="align-middle text-center items-center" style="padding: 0">
                                                        Reexpresión
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center" style="padding: 0 50px 0 50px">
                                                        Valor en Libros
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Incremento por Actualización
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center" style="padding: 0 50px 0 50px">
                                                        Valor Actualizado Final
                                                    </th>

                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Tiempo a Depreciar (Meses)
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Depreciación del Periodo
                                                    </th>

                                                    <th rowspan="2" class="align-middle text-center items-center" style="padding: 0 30px 0 30px">
                                                        Dep. Acumulada Inicial
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Increm. Actualiz. Deprec. Acumulada
                                                    </th>
                                                    <th rowspan="2" class="align-middle text-center items-center" style="padding: 0 50px 0 50px">
                                                        Dep. Acumulada Final
                                                    </th>

                                                    <th rowspan="2" class="align-middle text-center items-center">
                                                        Valor Neto del Activo
                                                    </th>
                                                </tr>
                                                <tr style="background-color: rgb(193, 193, 207)">
                                                    <th class="align-middle text-center items-center">
                                                        Aplicar
                                                    </th>
                                                    <th class="align-middle text-center items-center" style="padding: 0 50px 0 50px">
                                                        Fecha inicio
                                                    </th>
                                                    <th class="align-middle text-center items-center" style="padding: 0 50px 0 50px">
                                                        Fecha final
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                {{--! inicio lista de activos encontrados --}}
                                                @foreach ($activosFijos_encontrados as $activo )
                                                    <tr id="fila{{$activo->id}}">
                                                        <input type="hidden" name="id_ActivoFijo[]" value="{{$activo->id}}">
                                                        <input type="hidden" name="id_EjercicioContable[]" value="{{$idEjercicioSeleccionado}}">
                                                        <input type="hidden" name="id_RubroSeleccionado[]" value="{{$idRubroSeleccionado}}">


                                                        <td class="text-center align-middle">{{$activo->id}}</td>
                                                        <td class="align-middle">{{$activo->activoFijo}}</td>

                                                        <td class="text-center align-middle">
                                                            <span class="badge bg-cyan p-2">{{$activo->cantidad}}</span>
                                                        </td>

                                                        @php
                                                            $tieneDatosGuardados = "";
                                                        @endphp

                                                        {{--! CASO 1: EL ACTIVO TIENE DEPRECIACIONES GUARDADAS --}}
                                                        @foreach ($depreciaciones_existentes_en_el_ejercicio as $depreciacion)

                                                            @if ($depreciacion->ejercicio_id == $ejercicio_buscado_datos->id
                                                            && $depreciacion->activoFijo_id == $activo->id)

                                                                @php
                                                                    $tieneDatosGuardados = "si";
                                                                @endphp

                                                                {{-- VALIDACION DE ACCION o creacion para el controlador--}}
                                                                <input type="hidden" name="accion[]" value="actualizar-{{$depreciacion->id}}">

                                                                {{--? checkbox aplicacion de reexpresion --}}
                                                                <td class="text-center align-middle">
                                                                    @if ($depreciacion->reexpresar == 1)
                                                                        {{-- checkbox --}}
                                                                        <input type="checkbox" name="" class="check_reexpresar" id_activo ="{{$activo->id}}" checked>
                                                                        {{-- input --}}
                                                                        <input type="hidden" name="actualizar[]" class="actualizar" value="1">
                                                                    @else
                                                                        {{-- checkbox --}}
                                                                        <input type="checkbox" name="" class="check_reexpresar" id_activo ="{{$activo->id}}">
                                                                        {{-- input --}}
                                                                        <input type="hidden" name="actualizar[]" class="actualizar" value="0">
                                                                    @endif
                                                                </td>

                                                                {{-- ? fechas y ufvs --}}
                                                                {{--! ufvs en inputs DEPRECIACIONES GUARDADAS--}}
                                                                @php
                                                                    $u1=1;
                                                                    $u2=1;
                                                                    foreach ($ufvs as $ufv) {
                                                                        if ($ufv->fecha == $depreciacion->fechaInicial) {
                                                                            $u1 = $ufv->ufv;
                                                                        }
                                                                        if ($ufv->fecha == $depreciacion->fechaFinal) {
                                                                            $u2 = $ufv->ufv;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <td>
                                                                    <input type="date" name="fechaInicial[]" id_activo ="{{$activo->id}}" value="{{$depreciacion->fechaInicial}}" class="fechaInicial form-control text-red">

                                                                    <input type="text" value="{{$u1}}" class="ufvInicial form-control form-control-sm mt-1 text-right" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="date" name="fechaFinal[]" id_activo ="{{$activo->id}}" value="{{$depreciacion->fechaFinal}}" class="fechaFinal form-control text-red">

                                                                    <input type="text" value="{{$u2}}" class="ufvFinal form-control form-control-sm mt-1 text-right" readonly>
                                                                </td>
                                                                {{-- ? fin fechas y ufvs --}}

                                                                <td>
                                                                    <input type="text" name="valorInicialBien[]" value="{{ number_format($depreciacion->valorInicial_depr, 2 , '.' , ',') }}"
                                                                    class="valorInicialBien text-red form-control text-right">
                                                                </td>


                                                                {{--! calculo de operaciones caso 1 --}}
                                                                @php
                                                                    $valorInicialBien = $depreciacion->valorInicial_depr;
                                                                    /* ? Incremento Actualización */
                                                                    if($depreciacion->reexpresar == 1 ){
                                                                        $incremPorActualizBien = (($u2/$u1)-1) * $valorInicialBien;
                                                                    }
                                                                    else{
                                                                        $incremPorActualizBien = 0;
                                                                    }

                                                                    /* Valor Actualizado Final */
                                                                    $valorFinalBien = $incremPorActualizBien + $valorInicialBien;
                                                                    /* Depreciación del Periodo */
                                                                    $meses = $depreciacion->meses;
                                                                    $porcentaje = 1 / $rubro_buscado_datos->aniosVidaUtil; //1 = 100%
                                                                    $deprecBien = (($valorFinalBien * $porcentaje)/12) * $meses;

                                                                    /* ***************** */
                                                                    /* Dep. Acumulada Inicial */
                                                                    $depAcumInicial = $depreciacion->depAcumInicial_depr;
                                                                    /* Increm. Actualiz. Deprec. Acumulada */
                                                                    if($depreciacion->reexpresar == 1 ){
                                                                        $incremPorActualizDepAcum = (($u2/$u1)-1) * $depAcumInicial;
                                                                    }
                                                                    else{
                                                                        $incremPorActualizDepAcum = 0;
                                                                    }

                                                                    /* Dep. Acumulada Final */
                                                                    $depAcFinal = $incremPorActualizDepAcum + $depAcumInicial + $deprecBien;

                                                                    /* ***************** */
                                                                    /* Valor Neto del Activo */
                                                                    $valorNeto = round($valorFinalBien, 2) - round($depAcFinal, 2);

                                                                @endphp

                                                                <td class="text-right incremPorActualizBien">{{ number_format($incremPorActualizBien, 2 , '.' , ',') }}</td>

                                                                <td>
                                                                    <input type="text" name="valorFinalBien[]" value="{{ number_format($valorFinalBien, 2 , '.' , ',') }}"
                                                                    class="valorFinalBien text-red form-control text-right" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="text" name="meses[]" maxlength="4" value="{{$depreciacion->meses}}"
                                                                    class="meses_depreciacion text-red form-control text-center" required>
                                                                </td>

                                                                <td class="text-right deprecBien">{{ number_format($deprecBien, 2 , '.' , ',') }}</td>

                                                                <td >
                                                                    <input type="text" name="depAcumInicial[]" value="{{ number_format($depreciacion->depAcumInicial_depr, 2 , '.' , ',') }}"
                                                                    class="depAcumInicial text-red form-control text-right">
                                                                </td>

                                                                <td class="text-right incremPorActualizDepAcum">{{ number_format($incremPorActualizDepAcum, 2 , '.' , ',') }}</td>

                                                                <td>
                                                                    <input type="text" name="depAcFinal[]" value="{{ number_format($depAcFinal, 2 , '.' , ',') }}"
                                                                    class="depAcFinal text-red form-control text-right" readonly>
                                                                </td>

                                                                <td class="text-right valorNeto">{{ number_format($valorNeto, 2 , '.' , ',' ) }}</td>
                                                            @endif
                                                        @endforeach

                                                        {{-- NO TIENE DATOS GUARDADOS --}}
                                                        {{--! PRIMERAS COLUMNAS PARA NUEVA DEPRECIACION --}}

                                                        @if ($tieneDatosGuardados != "si") {{-- distinto de si --}}

                                                            {{--? checkbox aplicacion de reexpresion --}}
                                                            <td class="text-center align-middle">
                                                                {{-- checkbox --}}
                                                                <input type="checkbox" name="" class="check_reexpresar" id_activo ="{{$activo->id}}">
                                                                {{-- input --}}
                                                                <input type="hidden" name="actualizar[]" class="actualizar" value="0">
                                                            </td>

                                                            {{--? fechas y ufvs--}}
                                                            @php
                                                                $u1=1;
                                                                $u2=1;
                                                                foreach ($ufvs as $ufv) {
                                                                    if ($ufv->fecha == $activo->fechaCompraRegistro) {
                                                                        $u1 = $ufv->ufv;
                                                                    }
                                                                    if ($ufv->fecha == $ejercicio_buscado_datos->fechaCierre) {
                                                                        $u2 = $ufv->ufv;
                                                                    }
                                                                }
                                                            @endphp
                                                            <td>
                                                                <input type="date" name="fechaInicial[]" id_activo ="{{$activo->id}}" value="{{$activo->fechaCompraRegistro}}" class="fechaInicial text-blue form-control">

                                                                <input type="text" value="{{$u1}}" class="ufvInicial form-control form-control-sm mt-1 text-right" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="date" name="fechaFinal[]" id_activo ="{{$activo->id}}" value="{{$ejercicio_buscado_datos->fechaCierre}}" class="fechaFinal text-blue form-control">

                                                                <input type="text" value="{{$u2}}" class="ufvFinal form-control form-control-sm mt-1 text-right" readonly>
                                                            </td>

                                                            {{--! VALIDACION DE CASO 2 Y 3  de datos para usar la anterior depreciacon u original--}}
                                                            @php
                                                                $ExisteDepreciacionAnterior = false;
                                                                $UsarDatosOriginales = true;

                                                                foreach ($todas_las_depreciaciones_de_la_empresa as $depreciacion) {
                                                                    if($depreciacion->ejercicioFiscal == ($ejercicio_buscado_datos->ejercicioFiscal-1)
                                                                    && $depreciacion->activoFijo_id == $activo->id)
                                                                    {
                                                                        $ExisteDepreciacionAnterior = true;
                                                                        $idDepreciacionAnteriorior = $depreciacion->id;
                                                                        $UsarDatosOriginales = false;
                                                                    }
                                                                }
                                                            @endphp

                                                            {{-- DESARROLLO DE CASOS 2 Y 3--}}
                                                                {{--! CASO 2: DATOS ORIGINALES --}}
                                                            @if ($ExisteDepreciacionAnterior == false && $UsarDatosOriginales == true)

                                                                {{-- VALIDACION DE ACCION o creacion para el controlador--}}
                                                                <input type="hidden" name="accion[]" value="crear">

                                                                {{--! CASO 2: DATOS ORIGINALES --}}
                                                                <td>
                                                                    <input type="text" name="valorInicialBien[]" value="{{ number_format($activo->valorInicial, 2 , '.' , ',') }}"
                                                                    class="valorInicialBien text-blue form-control text-right">
                                                                </td>

                                                                {{--! calculo de operaciones caso 2--}}
                                                                @php
                                                                    $valorInicialBien = $activo->valorInicial;

                                                                    /* Incremento Actualización */
                                                                    //$incremPorActualizBien = (($u2/$u1)-1) * $valorInicialBien;
                                                                    //al crear nueva depreciacion no se reexpresará
                                                                    $incremPorActualizBien = 0;

                                                                    /* Valor Actualizado Final */
                                                                    $valorFinalBien = $incremPorActualizBien + $valorInicialBien;
                                                                    /* Depreciación del Periodo */
                                                                    $meses = 12;
                                                                    $porcentaje = 1 / $rubro_buscado_datos->aniosVidaUtil; //1 = 100%
                                                                    $deprecBien = (($valorFinalBien * $porcentaje)/12) * $meses;

                                                                    /* ***************** */
                                                                    /* Dep. Acumulada Inicial */
                                                                    $depAcumInicial = $activo->depAcumInicial;

                                                                    /* Increm. Actualiz. Deprec. Acumulada */
                                                                    //$incremPorActualizDepAcum = (($u2/$u1)-1) * $depAcumInicial;
                                                                    $incremPorActualizDepAcum = 0;

                                                                    /* Dep. Acumulada Final */
                                                                    $depAcFinal = $incremPorActualizDepAcum + $depAcumInicial + $deprecBien;

                                                                    /* ***************** */
                                                                    /* Valor Neto del Activo */
                                                                    $valorNeto = round($valorFinalBien, 2) - round($depAcFinal, 2);
                                                                @endphp

                                                                <td class="text-right incremPorActualizBien">{{ number_format($incremPorActualizBien, 2 , '.' , ',') }}</td>
                                                                <td>
                                                                    <input type="text" name="valorFinalBien[]" value="{{ number_format($valorFinalBien, 2 , '.' , ',') }}"
                                                                    class="valorFinalBien text-blue form-control text-right" readonly>
                                                                </td>

                                                                <td>
                                                                    <input type="text" name="meses[]" maxlength="4" value="{{$meses}}"
                                                                    class="meses_depreciacion text-blue form-control text-center" required>
                                                                </td>

                                                                <td class="text-right deprecBien">{{ number_format($deprecBien, 2 , '.' , ',') }}</td>

                                                                <td>
                                                                    <input type="text" name="depAcumInicial[]" value="{{number_format($activo->depAcumInicial, 2 , '.' , ',')}}"
                                                                    class="depAcumInicial text-blue form-control text-right">
                                                                </td>

                                                                <td class="text-right incremPorActualizDepAcum">{{ number_format($incremPorActualizDepAcum, 2 , '.' , ',') }}</td>

                                                                <td>
                                                                    <input type="text" name="depAcFinal[]" value="{{ number_format($depAcFinal, 2 , '.' , ',') }}"
                                                                    class="depAcFinal text-blue form-control text-right" readonly>
                                                                </td>

                                                                <td class="text-right valorNeto">{{ number_format($valorNeto, 2 , '.' , ',' ) }}</td>

                                                            @elseif ($ExisteDepreciacionAnterior == true && $UsarDatosOriginales == false)

                                                                {{--! CASO 3 : CON DEPRECIACIO ANTERIOR --}}
                                                                @foreach ( $todas_las_depreciaciones_de_la_empresa as $depreciacion )
                                                                    @if ($depreciacion->ejercicioFiscal == $ejercicio_buscado_datos->ejercicioFiscal -1
                                                                    && $depreciacion->activoFijo_id == $activo->id )

                                                                        {{-- VALIDACION DE ACCION o creacion para el controlador--}}
                                                                        <input type="hidden" name="accion[]" value="crear">

                                                                        <td>
                                                                            <input type="text" name="valorInicialBien[]" value="{{ number_format($depreciacion->valorFinal_depr, 2 , '.' , ',' ) }}"
                                                                            class="valorInicialBien text-blue form-control text-right">
                                                                        </td>

                                                                        {{--! calculo de operaciones caso 3--}}
                                                                        @php
                                                                            $valorInicialBien = $depreciacion->valorFinal_depr;

                                                                            /* Incremento Actualización */
                                                                            //al crear nueva depreciacion no se reexpresará
                                                                            //$incremPorActualizBien = (($u2/$u1)-1) * $valorInicialBien;
                                                                            $incremPorActualizBien = 0;

                                                                            /* Valor Actualizado Final */
                                                                            $valorFinalBien = $incremPorActualizBien + $valorInicialBien;
                                                                            /* Depreciación del Periodo */
                                                                            $meses = 12;
                                                                            $porcentaje = 1 / $rubro_buscado_datos->aniosVidaUtil; //1 = 100%
                                                                            $deprecBien = (($valorFinalBien * $porcentaje)/12) * $meses;

                                                                            /* ***************** */
                                                                            /* Dep. Acumulada Inicial */
                                                                            $depAcumInicial = $depreciacion->depAcumFinal_depr;

                                                                            /* Increm. Actualiz. Deprec. Acumulada */
                                                                            //$incremPorActualizDepAcum = (($u2/$u1)-1) * $depAcumInicial;
                                                                            $incremPorActualizDepAcum = 0;

                                                                            /* Dep. Acumulada Final */
                                                                            $depAcFinal = $incremPorActualizDepAcum + $depAcumInicial + $deprecBien;

                                                                            /* ***************** */
                                                                            /* Valor Neto del Activo */
                                                                            $valorNeto = round($valorFinalBien, 2) - round($depAcFinal, 2);
                                                                        @endphp

                                                                        <td class="text-right incremPorActualizBien">{{ number_format($incremPorActualizBien, 2 , '.' , ',') }}</td>

                                                                        <td>
                                                                            <input type="text" name="valorFinalBien[]" value="{{ number_format($valorFinalBien, 2 , '.' , ',') }}"
                                                                            class="valorFinalBien text-blue form-control text-right" readonly>
                                                                        </td>

                                                                        <td>
                                                                            <input type="text" name="meses[]" maxlength="4" value="{{$meses}}"
                                                                            class="meses_depreciacion text-blue form-control text-center" required>
                                                                        </td>

                                                                        <td class="text-right deprecBien">{{ number_format($deprecBien, 2 , '.' , ',') }}</td>

                                                                        <td >
                                                                            <input type="text" name="depAcumInicial[]" value="{{ number_format($depreciacion->depAcumFinal_depr, 2 , '.' , ',') }}"
                                                                            class="depAcumInicial text-blue form-control text-right">
                                                                        </td>

                                                                        <td class="text-right incremPorActualizDepAcum">{{ number_format($incremPorActualizDepAcum, 2 , '.' , ',') }}</td>

                                                                        <td>
                                                                            <input type="text" name="depAcFinal[]" value="{{ number_format($depAcFinal, 2 , '.' , ',') }}"
                                                                            class="depAcFinal text-blue form-control text-right" readonly>
                                                                        </td>

                                                                        <td class="text-right valorNeto">{{ number_format($valorNeto, 2 , '.' , ',') }}</td>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                {{-- fin lista de activos encontrados --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                @endif
            @endif
        @endif
        {{-- ! Fin DataTable de ACTIVO FIJO--}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="https://www.impuestos.gob.bo/pdf/D.S.%2024051%20de%2029-06-1995%20-%20Reglamento%20del%20Impuesto%20sobre%20las%20Utilidades%20de%20las%20Empresas%20(IUE)%20(1).pdf" target="_blank">
                                    Ver Decreto Supremo 24051 de 29-06-1995
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    {{-- ! Fin Contenido --}}
</div>
@endsection

@section('js')
    {{--! menu actual --}}
    <script>
        $('#menuHistorialDeprec').addClass('active');
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

    {{--! este mensajes--}}
        @if (Session('guardadas_depreciaciones')=='sin datos')
        <script>
            toastr.warning('No se guardó ningún dato.')
        </script>
        @endif

        @if (Session('guardadas_depreciaciones')=='exitoso')
        <script>
            toastr.success('Registros guardados exitosamente.')
        </script>
        @endif

        @if (Session('guardadas_depreciaciones')=='error')
        <script>
            toastr.error('Ocurrió un error, vuelva a intentarlo.')
        </script>
        @endif
    {{--! fin este mensajes--}}

    {{--! Pregunta desea GUARDAR--}}
    @if (Auth::user()->crear == 1)
        <script>
            $('.frmNuevas-Depreciaciones').submit(function(e){
                e.preventDefault();

                Swal.fire({
                title: '¿Desea guardar datos de las depreciaciones?',
                text: "¡Creará y Actualizará datos!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11151c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Guardar',
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
            $('.frmNuevas-Depreciaciones').submit(function(e){
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

    {{--! calculos al hacer algun cambio --}}
    <script>
        function aplicaActualizar(){
            //recorremos el checkbox
            $(".check_reexpresar").each(function(){
                let id_activo =  $(this).attr("id_activo");

                if($(this).is(":checked")){
                    $("#fila"+id_activo + " .actualizar").val("1");
                    //alert("a");
                }
                else{
                    $("#fila"+id_activo + " .actualizar").val("0");
                }
            })
        }

        /* calculo de reexpresion y depresiacion */
        function recalcularTodo(){
            aplicaActualizar();

            $("#tablaDepreciaciones tbody tr").each(function () {
                //id de la fila actual
                let id_fila =  $(this).prop("id");
                //alert(id_fila);

                //vida util (si existe un rubro buscado)
                let vidaUtil2 = "<?php if (isset($rubro_buscado_datos)) { echo $rubro_buscado_datos->aniosVidaUtil; } ?>";


                let vidaUtil = parseFloat(vidaUtil2);

                //calculo de reexpresion
                    let valorInicialBien = parseFloat($("#" + id_fila + " .valorInicialBien").val().replace(',','')); //input
                    let ufv1 = $("#" + id_fila + " .ufvInicial").val(); //input
                    let ufv2 = $("#" + id_fila + " .ufvFinal").val(); //input

                    /* Incremento Actualización */
                    let incremPorActualizBien = 0;
                    if( $("#"+ id_fila + " .check_reexpresar").is(':checked') )
                    {
                        incremPorActualizBien = parseFloat(((ufv2/ufv1)-1) * valorInicialBien);
                    }

                    /* Valor Actualizado Final */
                    let valorFinalBien = parseFloat(incremPorActualizBien + valorInicialBien);

                    /* Depreciación del Periodo */
                    let meses = parseFloat( $("#" + id_fila + " .meses_depreciacion").val() );
                    let porcentaje = parseFloat(1 / vidaUtil); //1 = 100%
                    let deprecBien = parseFloat( ((valorFinalBien * porcentaje)/12) * meses );
                    /* alert(vidaUtil);
                    alert(meses);
                    alert(deprecBien); */

                    /* ***************** */
                    /* Dep. Acumulada Inicial */
                    let depAcumInicial = parseFloat( $("#" + id_fila + " .depAcumInicial").val().replace(',','') ); //input

                    /* Increm. Actualiz. Deprec. Acumulada */
                    let incremPorActualizDepAcum = 0;
                    if( $("#"+ id_fila + " .check_reexpresar").is(':checked') )
                    {
                        incremPorActualizDepAcum = parseFloat( ((ufv2/ufv1)-1) * depAcumInicial ); //input
                    }

                    /* Dep. Acumulada Final */
                    let depAcFinal = parseFloat(incremPorActualizDepAcum + depAcumInicial + deprecBien);
                    //alert(depAcFinal);
                    /* ***************** */
                    /* Valor Neto del Activo */
                    let valorNeto = parseFloat(valorFinalBien.toFixed(2) - depAcFinal.toFixed(2));
                //Fin calculo de reexpresion

                //PONEMOS VALORES EN LA TABLA
                    $("#" + id_fila +" .incremPorActualizBien").html( numeral(incremPorActualizBien.toFixed(2)).format('0,0.00') );
                    $("#" + id_fila +" .valorFinalBien").val( numeral(valorFinalBien.toFixed(2)).format('0,0.00') );
                    $("#" + id_fila +" .deprecBien").html( numeral(deprecBien.toFixed(2)).format('0,0.00') );
                    $("#" + id_fila +" .incremPorActualizDepAcum").html( numeral(incremPorActualizDepAcum.toFixed(2)).format('0,0.00') );
                    $("#" + id_fila +" .depAcFinal").val( numeral(depAcFinal.toFixed(2)).format('0,0.00') );
                    $("#" + id_fila +" .valorNeto").html( numeral(valorNeto.toFixed(2)).format('0,0.00') );
                //FIN PONEMOS VALORES EN LA TABLA

            });
        }

        /* consultas de ufv por cambio en fechas */
        $('.fechaInicial').change(function() { // focusout : Esta para cuando pierda el foco el input valide la fecha.
            let fecha = $(this).val();
            let id_activo =  $(this).attr("id_activo");
            //atrr para atributos personalizados y nativos
            //prop para atributos boolean
            $("#fila"+id_activo + " .check_reexpresar").prop("checked", false); //esto no funiona con attr

            //ponemos en 1 la ufv actual
            $("#fila"+id_activo + " .ufvInicial").val('1');

            $.ajax({
                type: "GET",
                url: "{{route('consulta-ufv')}}",
                data: { fecha: fecha },
                dataType: "json",

                success: function (response) {
                    valor = response[0].ufv;
                    $("#fila"+id_activo + " .ufvInicial").val(valor.toFixed(5));
                    //alert(valor.toFixed(5) );
                    recalcularTodo();
                }
            });
            //recalcularTodo(); // se ejecuta antes de tener el resultado del ajax, por eso hay otro dentro

        });

        $('.fechaFinal').change(function() { // focusout : Esta para cuando pierda el foco el input valide la fecha.

            let fecha = $(this).val();
            let id_activo =  $(this).attr("id_activo");
            //atrr para atributos personalizados y nativos
            //prop para atributos boolean
            $("#fila"+id_activo + " .check_reexpresar").prop("checked", false); //esto no funiona con attr

            //ponemos en 1 la ufv actual
            $("#fila"+id_activo + " .ufvFinal").val('1');

            $.ajax({
                type: "GET",
                url: "{{route('consulta-ufv')}}",
                data: { fecha: fecha },
                dataType: "json",

                success: function (response) {
                    valor = response[0].ufv;
                    $("#fila"+id_activo + " .ufvFinal").val(valor.toFixed(5));
                    //alert(valor.toFixed(5) );
                }
            });
            recalcularTodo();

        });

        $(".check_reexpresar").change(function () {
            //alert("estoy aqui");
            recalcularTodo();
        });

        $(".valorInicialBien").change(function () {
            $(this).val( numeral( $(this).val()).format('0,0.00') );
            recalcularTodo();
        });

        $(".depAcumInicial").change(function () {
            $(this).val( numeral( $(this).val()).format('0,0.00') );
            recalcularTodo();
        });
    </script>

    <script>
        $(".meses_depreciacion").change(function () {
            if( $(this).val()>12 ){
                toastr.error("Los meses de depreciación no pueden ser mayor a 12");
                $(this).val("0");
            }

            if( $(this).val()<0 ){
                toastr.error("Los meses de depreciación no pueden negativos");
                $(this).val("0");
            }
            if( $(this).val() == "" ){
                toastr.error("Los meses de depreciación no pueden negativos");
                $(this).val("0");
            }
            recalcularTodo();
        });
    </script>
@endsection
