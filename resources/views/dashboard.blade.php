@extends('plantilla.adminlte')

@section('titulo')
    Inicio
@endsection

@section('css')

@endsection

@section('contenido')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        {{-- ! Encabezado --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Inicio</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Sistema Contable</a></li>
                            <li class="breadcrumb-item active">Inicio</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{-- ! Fin Encabezado --}}

        {{-- ! Contenido --}}
        <section class="content">
            <div class="container-fluid">
                {{--! Fila de Tarjetas --}}
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">

                                <h3 id="regitros_del_ejercicio">{{$cantRegistrosDelEjercicioAcivo}}</h3> {{-- $cantRegistrosDelEjercicioAcivo --}}
                                <p>Asientos Contables del Ejercicio Activo</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-paste"></i>
                            </div>
                            <a href="{{route('libro-diario')}}" class="small-box-footer">Ver Más <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">

                                <h3>{{$cantSucursalesDeLaEmpresaActiva}}</h3>
                                <p>Sucursales de la Empresa</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <a href="/sucursales" class="small-box-footer">Ver Más <i
                                class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">

                                <h3>{{$cantEjerciciosDeLaEmpresaActiva}}</h3>
                                <p>Ejercicios Contables de la Empresa</p>

                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <a href="/ejercicios" class="small-box-footer">Ver Más <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                {{--! Fin Fila de Tarjetas --}}

                <div class="row">
                    <div class="col-12">
                        <!-- STACKED BAR CHART -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Activo Fijo por Categoría o Rubro</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- <div style="width: 500px;"><canvas id="dimensions"></canvas></div><br/> -->
                                <div style="width: 100%; height: 400px;">
                                    <canvas id="grafico_activos"></canvas>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-row card-danger">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Compras
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body overflow-auto" style="height: 300px">
                                @foreach ($datos_compras as $compras)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title">{{$compras->anio}} - {{$compras->mes}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <label>
                                                <i class="fas fa-luggage-cart"></i>
                                                {{$compras->suma}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-row card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Ventas
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body overflow-auto" style="height: 300px">
                                @foreach ($datos_ventas as $ventas)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title">{{$ventas->anio}} - {{$ventas->mes}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <label>
                                                <i class="fas fa-cart-plus"></i>
                                                {{$ventas->suma}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-row card-cyan">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Sucursales
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body overflow-auto" style="height: 300px">
                                @foreach ($sucursales_de_la_empresa_dash as $sucursal_dash)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title">{{$sucursal_dash->descripcion}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <label>
                                                <i class="fas fa-store-alt"></i>
                                                {{$sucursal_dash->direccion}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-row card-cyan">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Ejercicios Contables
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body overflow-auto" style="height: 300px">
                                @foreach ($ejercicios_de_la_empresa_dash as $ejercicio_dash)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title">{{$ejercicio_dash->ejercicioFiscal}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <label>
                                                <i class="fas fa-cash-register"></i>
                                                {{date('d/m/Y', strtotime($ejercicio_dash->fechaInicio))}}
                                            </label>
                                            <br>
                                            <label for="">
                                                <i class="fas fa-cash-register"></i>
                                                {{date('d/m/Y', strtotime($ejercicio_dash->fechaCierre))}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
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
        $('#menuDashboard').addClass('active');
    </script>

    {{--! ACTIVACION DE EMPRESA--}}
    @if (Session('actualizacion_empresa_ejercicio')=='ok')
    <script>
            toastr.success('Cambio de empresa activa exitosamente.')
    </script>
    @endif

    @if (Auth::user()->rol == "Administrador")
        @if (Session('respaldo')=='recomendar')
            <script>
                    //toastr.success('Le recordamos que como administrador puede hacer copias de seguridad.')
                    Swal.fire("Le recordamos que como administrador puede hacer copias de seguridad.");
            </script>
        @endif
    @endif



    <!-- ChartJS -->
    <script src="{{asset('/plugins/chart.js/Chart.min.js')}}"></script>

    <script>
        $(document).ready(function () {

            //**************
            // var text = text.replace(/&quot;/g, '\\"'); //para reemplaza "&quot;" que es comillas
            //************

            var r = '{{ $label_rubros }}';
            var s = r.replace(/&quot;/g, '\"');

            var label_rubros = JSON.parse(s); // antes se usó json_encode en el controlador, si no, no funciona


            var data_rubros = JSON.parse('{{ $data_rubros }}'); // antes se usó json_encode en el controlador, si no, no funciona

            //alert(label_rubros);

            var ctx = document.getElementById('grafico_activos').getContext('2d'); // le damos id del canvas
            //ctx.height = 100; //alto

            const myChart = new Chart(ctx,{
                type:'bar',
                data:{
                    labels:label_rubros,
                    datasets:[{
                        label:'Cantidad de Activos o Items Registrados',
                        data:data_rubros,
                        backgroundColor:[
                            '#a9d6e5',
                            '#89c2d9',
                            '#61a5c2',
                            '#468faf',
                            '#2C7DA0',

                            '#fdb833',
                            '#f9dc5c',
                            '#fae588',
                            '#fcefb4',
                            '#fdf8e1'
                        ]
                    }],
                    borderWidth:1
                },
                options:{
                    scales:{
                        yAxes:[{
                            ticks:{
                                beginAtZero:true
                            }
                        }]
                    },
                    responsive: true, //para limitar altura
                    maintainAspectRatio: false, //para limitar altura
                }
            });

        });
    </script>

@endsection
