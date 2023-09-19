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
                            <h1 class="m-0">Registro de Asiento/Registro Contable</h1>
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
                            <form action="{{route('comprobante.store')}}" method="POST"
                            id="frmCrear-Comprobante" class="frmCrear-Comprobante">
                                @csrf
                                <div class="p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="nroComprobante">Nro. Comprobante:</label>
                                            <input type="text" name="nroComprobante" id="nroComprobante" class="form-control" readonly>
                                            <input type="hidden" name="correlativo" id="correlativo">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="tipoComprobante">Tipo de Comprobante:</label>
                                            <select name="tipoComprobante" id="tipoComprobante" class="form-control" required>
                                                <option value=""></option>
                                                @foreach ($tiposComprobantes as $tipo )
                                                    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="fecha">Fecha:</label>
                                            <input type="date" name="fecha" id="fecha" class="form-control" autocomplete="off" required>
                                            {{-- <input type="text" name="fecha" id="date-mask-input-a" class="form-control" autocomplete="off"> --}}
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="concepto">Concepto:</label>
                                            <textarea name="concepto" id="concepto" cols="30" rows="2" class="form-control text-uppercase" maxlength="250" required></textarea>
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
                                            <input type="text" list="listaDocumentos" name="documento" id="documento" class="form-control text-uppercase" maxlength="30" autocomplete="off">
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
                                            <input type="text" name="numeroDocumento" id="numeroDocumento" class="form-control text-uppercase" maxlength="30" autocomplete="off">
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
                                    <div class="row" style="background-color: rgb(254, 254, 170)">
                                        <div class="col-12 table-responsive">
                                            <table class="table" id="tablaCodigoCuentaDebeHaber">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Cuenta</th>
                                                        <th>Debe</th>
                                                        <th>Haber</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-md">
                                                    <tr id="fila1">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_1" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_1" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_1" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_1" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila2">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_2" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_2" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_2" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_2" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila3">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_3" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_3" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_3" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_3" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila4">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_4" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_4" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_4" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_4" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila5">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_5" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_5" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_5" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_5" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila6">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_6" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_6" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_6" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_6" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila7">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_7" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_7" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_7" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_7" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila8">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_8" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_8" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_8" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_8" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila9">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_9" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_9" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_9" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_9" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr id="fila10">
                                                        <td style="width: 20%">
                                                            {{-- <input type="hidden" class="cuentaOculta form-control bg-transparent p-0 m-0 border-transparent" value="CUENTA" readonly> --}}
                                                            <select id="cod_10" name="codigo[]" class="codigo form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">
                                                                        {{$sub_cuenta->id}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 30%">
                                                            <select id="subc_10" name="subcuenta[]" class="subcuenta form-control select2">
                                                                <option value=""></option>
                                                                @foreach ($sub_cuentas as $sub_cuenta)
                                                                    <option value="{{$sub_cuenta->id}}">
                                                                        {{$sub_cuenta->descripcion}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="deb_10" type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td style="width: 20%">
                                                            <input id="hab_10" type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off">
                                                        </td>
                                                        <td class="align-middle">
                                                            <a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
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
                                                            <textarea name="notas" id="notas" class="form-control w-100 text-uppercase" maxlength="100"></textarea>
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
                                                Guardar
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

                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Asientos Comunes</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <a id="modelo_de_compras" class="btn btn-outline-info m-1">
                                    <i class="far fa-file-alt mr-1"></i>
                                    De Compras
                                </a>
                                <a id="modelo_de_ventas" class="btn btn-outline-info m-1">
                                    <i class="far fa-file-alt mr-1"></i>
                                    De Ventas
                                </a>

                                <a id="modelo_de_compras_con_descuento" class="btn btn-outline-info m-1">
                                    <i class="far fa-file-alt mr-1"></i>
                                    De Compras con Descuentos
                                </a>
                                <a id="modelo_de_ventas_con_descuento" class="btn btn-outline-info m-1">
                                    <i class="far fa-file-alt mr-1"></i>
                                    De Ventas con Descuentos
                                </a>

                                <a id="modelo_de_sueldos" class="btn btn-outline-info m-1">
                                    <i class="far fa-file-alt mr-1"></i>
                                    De Devengado de Sueldos
                                </a>

                            </div>
                        </div>

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

    {{--! mensajes de error -frmCrear--}}
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

    @if (Session('comprobante')=='creado')
        <script>
                toastr.success('Comprobante creado exitosamente.')
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

            $("#sumaDebe").val(sumaDebe.toFixed(2)); //alert( sumaDebe ); //utilizamos to fixed para redondear por el probema de decimales
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
            $("#diferencia").val(diferencia.toFixed(2));//utilizamos to fixed para redondear por el probema de decimales

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
            //var codigo_seleccionado =  $('option:selected',this).attr('atributopersonalizado'); //obtenemos el atributo personalizado del option
            //alert(codigo_seleccionado);


            //mostramos la cuenta usando el codigo
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
                '<tr><td style="width: 20%"><select name="codigo[]" class="codigo form-control select2"><option value=""></option>@foreach ($sub_cuentas as $sub_cuenta)<option value="{{$sub_cuenta->id}}">{{$sub_cuenta->id}}</option>@endforeach</select></td><td style="width: 30%"><select name="subcuenta[]" class="subcuenta form-control select2"><option value=""></option>@foreach ($sub_cuentas as $sub_cuenta)<option value="{{$sub_cuenta->id}}" sub_cuenta_nombre="{{$sub_cuenta->descripcion}}">{{$sub_cuenta->descripcion}}</option>@endforeach</select></td><td style="width: 20%"><input type="text" name="debe[]" class="debe form-control text-right" maxlength="10" autocomplete="off"></td><td style="width: 20%"><input type="text" name="haber[]" class="haber form-control text-right" maxlength="10" autocomplete="off"></td><td class="align-middle"><a class="btnQuitarFila btn btn-outline-danger btn-xs pr-2 pl-2"><i class="fas fa-times"></i></a></td></tr>'
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


    {{--! Pregunta desea CREAR COMPROBANTE--}}
    @if (Auth::user()->crear == 1)
        <script>

            $('.frmCrear-Comprobante').submit(function(e){
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
                        title: '¿Desea Agregar el Comprobante Contable ?',
                        text: "¡Creará un nuevo Asiento Contable o Registro Contable!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#11151c',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Agegar',
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
            $('.frmCrear-Comprobante').submit(function(e){
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


    {{--! asientos modelo --}}
    <script>
        function poner_encero_modelos()
        {
            $("#deb_1").val("");
            $("#deb_2").val("");
            $("#deb_3").val("");
            $("#deb_4").val("");
            $("#deb_5").val("");
            $("#deb_6").val("");
            $("#deb_7").val("");
            $("#hab_1").val("");
            $("#hab_2").val("");
            $("#hab_3").val("");
            $("#hab_4").val("");
            $("#hab_5").val("");
            $("#hab_6").val("");
            $("#hab_7").val("");
        }

        $('#modelo_de_compras').click(function (e) {
            e.preventDefault();
            poner_encero_modelos();

            let importe = prompt("Ingrese el Importe Total");
            if(!isNaN(importe)){
                //alert("es numero");
                if(importe < 0)
                {
                    importe=0;
                }
                let credito_f = (importe*0.13).toFixed(2);
                let neto = (importe-credito_f).toFixed(2);

                //seleccionamos cuetas
                $("#deb_1").val(neto);
                $("#deb_2").val(credito_f);
                $("#hab_3").val(importe);
                //codigo y cuenta

                //compra
                $("#fila1").find(".codigo option[value='5010102001']").attr("selected",true);
                $("#fila1").find(".codigo").val('5010102001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila1").find(".subcuenta option[value='5010102001']").attr("selected",true);
                $("#fila1").find(".subcuenta").val('5010102001').trigger('change.select2');
                //cf
                $("#fila2").find(".codigo option[value='1010202001']").attr("selected",true);
                $("#fila2").find(".codigo").val('1010202001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila2").find(".subcuenta option[value='1010202001']").attr("selected",true);
                $("#fila2").find(".subcuenta").val('1010202001').trigger('change.select2');
                //caja
                $("#fila3").find(".codigo option[value='1010101001']").attr("selected",true);
                $("#fila3").find(".codigo").val('1010101001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila3").find(".subcuenta option[value='1010101001']").attr("selected",true);
                $("#fila3").find(".subcuenta").val('1010101001').trigger('change.select2');
            }

        });

        $('#modelo_de_ventas').click(function (e) {
            e.preventDefault();
            poner_encero_modelos();

            let importe = prompt("Ingrese el Importe Total");
            if(!isNaN(importe)){
                //alert("es numero");
                if(importe < 0)
                {
                    importe=0;
                }
                let debito_f = (importe*0.13).toFixed(2);
                let venta_neta = (importe-debito_f).toFixed(2);
                let it = (importe*0.03).toFixed(2);

                //seleccionamos cuetas
                $("#deb_1").val(importe);
                $("#deb_2").val(it);
                $("#hab_3").val(venta_neta);
                $("#hab_4").val(debito_f);
                $("#hab_5").val(it);
                //codigo y cuenta
                //caja
                $("#fila1").find(".codigo option[value='1010101001']").attr("selected",true);
                $("#fila1").find(".codigo").val('1010101001').trigger('change.select2');
                $("#fila1").find(".subcuenta option[value='1010101001']").attr("selected",true);
                $("#fila1").find(".subcuenta").val('1010101001').trigger('change.select2');
                //it
                $("#fila2").find(".codigo option[value='5020105001']").attr("selected",true);
                $("#fila2").find(".codigo").val('5020105001').trigger('change.select2');
                $("#fila2").find(".subcuenta option[value='5020105001']").attr("selected",true);
                $("#fila2").find(".subcuenta").val('5020105001').trigger('change.select2');
                //venta
                $("#fila3").find(".codigo option[value='4010101001']").attr("selected",true);
                $("#fila3").find(".codigo").val('4010101001').trigger('change.select2');
                $("#fila3").find(".subcuenta option[value='4010101001']").attr("selected",true);
                $("#fila3").find(".subcuenta").val('4010101001').trigger('change.select2');
                //df
                $("#fila4").find(".codigo option[value='2010201001']").attr("selected",true);
                $("#fila4").find(".codigo").val('2010201001').trigger('change.select2');
                $("#fila4").find(".subcuenta option[value='2010201001']").attr("selected",true);
                $("#fila4").find(".subcuenta").val('2010201001').trigger('change.select2');
                //itxp
                $("#fila5").find(".codigo option[value='2010201002']").attr("selected",true);
                $("#fila5").find(".codigo").val('2010201002').trigger('change.select2');
                $("#fila5").find(".subcuenta option[value='2010201002']").attr("selected",true);
                $("#fila5").find(".subcuenta").val('2010201002').trigger('change.select2');

            }

        });

        $('#modelo_de_sueldos').click(function (e) {
            e.preventDefault();
            poner_encero_modelos();

            let importe = prompt("Ingrese el Importe Total");
            if(!isNaN(importe)){
                //alert("es numero");
                if(importe < 0)
                {
                    importe=0;
                }
                let aportes_afp_por_pagar = (importe*0.1271).toFixed(2);
                let aportes_patronales_por_pagar = (importe*0.1671).toFixed(2);
                let indemnizacion_aguinaldo = (importe*0.0833).toFixed(2);
                let sueldo_por_pagar = (importe-aportes_afp_por_pagar).toFixed(2);
                let cargas_sociales = (indemnizacion_aguinaldo*2).toFixed(2);
                cargas_sociales = (parseFloat(cargas_sociales)+parseFloat(aportes_patronales_por_pagar)).toFixed(2);



                //importes
                $("#deb_1").val(importe); //sueldos
                $("#deb_2").val(cargas_sociales); //cargas
                $("#hab_3").val(sueldo_por_pagar); //suelpo por pagar
                $("#hab_4").val(aportes_afp_por_pagar); //afp
                $("#hab_5").val(aportes_patronales_por_pagar);    //patronales
                $("#hab_6").val(indemnizacion_aguinaldo);    //aguinaldo
                $("#hab_7").val(indemnizacion_aguinaldo);    //indem

                //codigo y cuenta
                //sueldos
                $("#fila1").find(".codigo option[value='5020101001']").attr("selected",true);
                $("#fila1").find(".codigo").val('5020101001').trigger('change.select2');
                $("#fila1").find(".subcuenta option[value='5020101001']").attr("selected",true);
                $("#fila1").find(".subcuenta").val('5020101001').trigger('change.select2');
                //cargas sociales
                $("#fila2").find(".codigo option[value='5020102001']").attr("selected",true);
                $("#fila2").find(".codigo").val('5020102001').trigger('change.select2');
                $("#fila2").find(".subcuenta option[value='5020102001']").attr("selected",true);
                $("#fila2").find(".subcuenta").val('5020102001').trigger('change.select2');
                //sueldos por pagar
                $("#fila3").find(".codigo option[value='2010301001']").attr("selected",true);
                $("#fila3").find(".codigo").val('2010301001').trigger('change.select2');
                $("#fila3").find(".subcuenta option[value='2010301001']").attr("selected",true);
                $("#fila3").find(".subcuenta").val('2010301001').trigger('change.select2');
                //afp
                $("#fila4").find(".codigo option[value='2010302006']").attr("selected",true);
                $("#fila4").find(".codigo").val('2010302006').trigger('change.select2');
                $("#fila4").find(".subcuenta option[value='2010302006']").attr("selected",true);
                $("#fila4").find(".subcuenta").val('2010302006').trigger('change.select2');
                //patr
                $("#fila5").find(".codigo option[value='2010303005']").attr("selected",true);
                $("#fila5").find(".codigo").val('2010303005').trigger('change.select2');
                $("#fila5").find(".subcuenta option[value='2010303005']").attr("selected",true);
                $("#fila5").find(".subcuenta").val('2010303005').trigger('change.select2');

                //aguin
                $("#fila6").find(".codigo option[value='2010301002']").attr("selected",true);
                $("#fila6").find(".codigo").val('2010301002').trigger('change.select2');
                $("#fila6").find(".subcuenta option[value='2010301002']").attr("selected",true);
                $("#fila6").find(".subcuenta").val('2010301002').trigger('change.select2');
                //indem
                $("#fila7").find(".codigo option[value='2020101001']").attr("selected",true);
                $("#fila7").find(".codigo").val('2020101001').trigger('change.select2');
                $("#fila7").find(".subcuenta option[value='2020101001']").attr("selected",true);
                $("#fila7").find(".subcuenta").val('2020101001').trigger('change.select2');

            }

        });

        $('#modelo_de_compras_con_descuento').click(function (e) {
            e.preventDefault();
            poner_encero_modelos();

            let importe = prompt("Ingrese el Importe Total");
            if(!isNaN(importe)){
                //alert("es numero");
                if(importe < 0)
                {
                    importe=0;
                }
                let credito_f = (importe*0.13).toFixed(2);
                let neto = (importe-credito_f).toFixed(2);

                //seleccionamos cuetas
                $("#deb_1").val(neto);
                $("#deb_2").val(credito_f);
                //
                //
                $("#hab_5").val(importe);
                //codigo y cuenta

                //compra
                $("#fila1").find(".codigo option[value='5010102001']").attr("selected",true);
                $("#fila1").find(".codigo").val('5010102001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila1").find(".subcuenta option[value='5010102001']").attr("selected",true);
                $("#fila1").find(".subcuenta").val('5010102001').trigger('change.select2');
                //cf
                $("#fila2").find(".codigo option[value='1010202001']").attr("selected",true);
                $("#fila2").find(".codigo").val('1010202001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila2").find(".subcuenta option[value='1010202001']").attr("selected",true);
                $("#fila2").find(".subcuenta").val('1010202001').trigger('change.select2');
                //desc
                $("#fila3").find(".codigo option[value='5010102002']").attr("selected",true);
                $("#fila3").find(".codigo").val('5010102002').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila3").find(".subcuenta option[value='5010102002']").attr("selected",true);
                $("#fila3").find(".subcuenta").val('5010102002').trigger('change.select2');
                //df
                $("#fila4").find(".codigo option[value='2010201001']").attr("selected",true);
                $("#fila4").find(".codigo").val('2010201001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila4").find(".subcuenta option[value='2010201001']").attr("selected",true);
                $("#fila4").find(".subcuenta").val('2010201001').trigger('change.select2');
                //caja
                $("#fila5").find(".codigo option[value='1010101001']").attr("selected",true);
                $("#fila5").find(".codigo").val('1010101001').trigger('change.select2'); //https://es.stackoverflow.com/questions/57038/c%C3%B3mo-le-digo-al-plugin-select2-qu%C3%A9-elemento-poner-seleccionado
                $("#fila5").find(".subcuenta option[value='1010101001']").attr("selected",true);
                $("#fila5").find(".subcuenta").val('1010101001').trigger('change.select2');
            }

        });

        $('#modelo_de_ventas_con_descuento').click(function (e) {
            e.preventDefault();
            poner_encero_modelos();

            let importe = prompt("Ingrese el Importe Total");
            if(!isNaN(importe)){
                //alert("es numero");
                if(importe < 0)
                {
                    importe=0;
                }
                let debito_f = (importe*0.13).toFixed(2);
                let venta_neta = (importe-debito_f).toFixed(2);
                let it = (importe*0.03).toFixed(2);

                //seleccionamos cuetas
                $("#deb_1").val(importe);
                //
                //
                $("#deb_4").val(it);
                $("#hab_5").val(venta_neta);
                $("#hab_6").val(debito_f);
                $("#hab_7").val(it);
                //codigo y cuenta
                //caja
                $("#fila1").find(".codigo option[value='1010101001']").attr("selected",true);
                $("#fila1").find(".codigo").val('1010101001').trigger('change.select2');
                $("#fila1").find(".subcuenta option[value='1010101001']").attr("selected",true);
                $("#fila1").find(".subcuenta").val('1010101001').trigger('change.select2');
                //desc v
                $("#fila2").find(".codigo option[value='4010101002']").attr("selected",true);
                $("#fila2").find(".codigo").val('4010101002').trigger('change.select2');
                $("#fila2").find(".subcuenta option[value='4010101002']").attr("selected",true);
                $("#fila2").find(".subcuenta").val('4010101002').trigger('change.select2');
                //cf
                $("#fila3").find(".codigo option[value='1010202001']").attr("selected",true);
                $("#fila3").find(".codigo").val('1010202001').trigger('change.select2');
                $("#fila3").find(".subcuenta option[value='1010202001']").attr("selected",true);
                $("#fila3").find(".subcuenta").val('1010202001').trigger('change.select2');
                //it
                $("#fila4").find(".codigo option[value='5020105001']").attr("selected",true);
                $("#fila4").find(".codigo").val('5020105001').trigger('change.select2');
                $("#fila4").find(".subcuenta option[value='5020105001']").attr("selected",true);
                $("#fila4").find(".subcuenta").val('5020105001').trigger('change.select2');
                //venta
                $("#fila5").find(".codigo option[value='4010101001']").attr("selected",true);
                $("#fila5").find(".codigo").val('4010101001').trigger('change.select2');
                $("#fila5").find(".subcuenta option[value='4010101001']").attr("selected",true);
                $("#fila5").find(".subcuenta").val('4010101001').trigger('change.select2');
                //df
                $("#fila6").find(".codigo option[value='2010201001']").attr("selected",true);
                $("#fila6").find(".codigo").val('2010201001').trigger('change.select2');
                $("#fila6").find(".subcuenta option[value='2010201001']").attr("selected",true);
                $("#fila6").find(".subcuenta").val('2010201001').trigger('change.select2');
                //itxp
                $("#fila7").find(".codigo option[value='2010201002']").attr("selected",true);
                $("#fila7").find(".codigo").val('2010201002').trigger('change.select2');
                $("#fila7").find(".subcuenta option[value='2010201002']").attr("selected",true);
                $("#fila7").find(".subcuenta").val('2010201002').trigger('change.select2');

            }

        });

    </script>



@endsection
