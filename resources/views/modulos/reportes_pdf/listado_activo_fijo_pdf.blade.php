@extends('plantilla.plantillaPDF')

@section('titulo')
    Listado activo fijo
@endsection

@section('css')
    <style>
        html {
            margin: 106pt 38pt 32pt 40pt; //margenes de la hpja pdf a d ab iz //32pt 32pt 32pt 32pt
            //aumentamos el margin top para que el encabezado no se sobreponga al contenido
            //y para eso tambien en al header le poner -xx en top
            // esto aplica para el caso de uso en en cabezado limitado por el margin, en el caso de que quiera un header de ancho completo es distinto
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }

        /* encabezados de tablas y hoja */

        .tabla-encabezado {
            width: 100%;
            font-weight: bold;
            margin: 0px;
        }
        .azul{
            color: rgb(28, 28, 145);
        }

        .bg-azul{
            background-color: rgb(68, 68, 187);
        }
        .blanco{
            color: white;
        }

        .datosEncabezado_2 tr,
        .datosEncabezado_2 td,
        .datosEncabezado_2 th{
            border-spacing: 0;
            width: 100%;
        }

        //CONTENIDO
        .encabezado-de-tabla,
        .footer-de-tabla{
            color: rgb(28, 28, 145);
        }

        /* texto */
        .centro {
            text-align: center;
        }

        .izquierda {
            text-align: left;
        }

        .derecha {
            text-align: right;
        }

        .arriba{
            vertical-align: text-top;
        }

        .abajo{
            vertical-align: text-bottom;
        }

        .medio{
            vertical-align: middle;
        }

        /* tabla */

        .tabla-detalle{
            width: 100%;
            border-spacing: 0;
            border: 0.1px solid #000000;
        }

        .tabla-detalle td,
        .tabla-detalle th{
            border-spacing: 0;
            border-collapse: collapse;
            border: 0.1px solid #000000;
        }



        /* parrrafo */
        thead p,
        tbody p,
        tfoot p
        {
            padding: 5px 5px 5px 5px;
        }
        .datosEncabezado_2 p{
            padding: 0px 5px 2px 5px;
        }
    </style>

    <style>
        /* saltos de pagina */
        .page_break {
            page-break-before: always;
        }

        header {
                position: fixed;
                top: -100px;
                left: 0px;
                right: 0px;
                height: 60px;

                /** Extra personal styles **/
                background-color: white;
            }
    </style>
@endsection

@section('contenido')

<header>
    {{-- ! Encabezado --}}
    <div class="div-encabezados">
        <table class="tabla-encabezado azul">
            <tr>
                <td colspan="4" style="font-size: 14px">
                    {{$datosEmpresaActiva->denominacionSocial}}
                </td>
                <td rowspan="2" class="izquierda" style="text-align: right; font-size: 12px; width: 200px">
                    LISTADO DE ACTIVO FIJO
                    @if (Auth::user()->hora_fecha_en_reportes_pdf == 1)
                        <p style="padding: 0; margin: 0;">Fecha impresión: {{date('d-m-y')}}</p>
                        <p style="padding: 0; margin: 0;">Hora impresión: {{date('h:i:s')}}</p>
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="4" style="font-size: 14px">
                    NIT: {{$datosEmpresaActiva->nit}}
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 20px"></td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center; font-size: 15px;">
                    LISTADO DE ACTIVO FIJO
                </td>
            </tr>

            <tr>
                <td colspan="5" style="height: 10px"></td>
            </tr>
        </table>
    </div>
    {{-- ! Fin Encabezado --}}
</header>

<main>

    {{-- ! Contenido --}}
    <section class="content">
        {{-- datos generales del rubro --}}
        <div>
            <table class="datosEncabezado_2" style="font-size: 11px; width: 100%;">
                @if ($rubro_buscado=="todos")
                    <tr>
                        <td style="height: 10px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="izquierda" style="width: 200px;">
                            <p>Categoría o Rubro:</p>
                        </th>
                        <td class="azul">
                            <p>Todos</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Años de Vida Útil:</p>
                        </th>
                        <td>
                            <p>-</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Sujeto a depreciación:</p>
                        </th>
                        <td>
                            <p>-</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Cantidad Items (activos):</p>
                        </th>

                        <td>
                            <p>-</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 4px"></td>
                        <td></td>
                    </tr>
                @else
                    <tr>
                        <td style="height: 10px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="izquierda" style="width: 200px;">
                            <p>Categoría o Rubro:</p>
                        </th>
                        <td class="azul">
                            <p>{{$rubro_buscado->rubro}}</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Años de Vida Útil:</p>
                        </th>
                        <td>
                            <p>{{$rubro_buscado->aniosVidaUtil}}</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Sujeto a depreciación:</p>
                        </th>
                        <td>
                            @if ($rubro_buscado->sujetoAdepreciacion == 1)
                                <p>Si</p>
                            @else
                                <p>No</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="izquierda">
                            <p>Cantidad Items (activos):</p>
                        </th>

                        <td>
                            @foreach ($rubros as $rub)
                                @if ($rubro_buscado->id == $rub->id)
                                    <p>{{$rub->cantidad_activos_registrados}}</p>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 4px"></td>
                        <td></td>
                    </tr>
                @endif
            </table>
        </div>
        {{-- tabla --}}
        <div class="contenido">
            <br>

            @if ($rubroSeleccionado != "")
                @if (isset($activosFijosEncontrados))

                    <table class="tabla-detalle">
                        <thead>
                            <tr class="blanco bg-azul" style="font-size: 12px;">
                                <th>
                                    <p>Nro.</p>
                                </th>
                                <th style="height: 30px">
                                    <p>Item</p>
                                </th>
                                <th>
                                    <p>Descripción</p>
                                </th>
                                <th>
                                    <p>Cantidad</p>
                                </th>
                                <th>
                                    <p>Medida</p>
                                </th>
                                <th>
                                    <p>Situación</p>
                                </th>
                                <th>
                                    <p>Estado del A.F.</p>
                                </th>
                                @if ($rubroSeleccionado == '-1')
                                    <th>
                                        <p>Categoría/Rubro</p>
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody style="font-size: 10px">
                            @php // declaramos la variable, no la imprimimos aun
                                $numero = 0;
                            @endphp
                            @foreach ($activosFijosEncontrados as $activo)
                                <tr>
                                    <td class="centro">
                                        <p>{{ $numero = $numero + 1 }}</p>
                                    </td>
                                    <td class="centro">
                                        <p>{{ $activo->id }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $activo->activoFijo }}</p>
                                    </td>
                                    <td class="centro">
                                        <p>{{$activo->cantidad}}</p>
                                    </td>
                                    <td>
                                        <p>{{ $activo->medida }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $activo->situacion}}</p>
                                    </td>
                                    <td>
                                        @if ($activo->estadoAF == "ALTA")
                                        <p style="color: blue;">{{$activo->estadoAF}}</p>
                                        @else
                                        <p style="color: red;">{{$activo->estadoAF}}</p>
                                        @endif
                                    </td>
                                    @if ($rubroSeleccionado == '-1')
                                        <td>
                                            <p>{{$activo->activosFijos_rubros->rubro}}</p>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @endif
            @endif

        </div>
    </section>
    {{-- ! Fin Contenido --}}

</main>

{{-- $pdf->text(370, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10); --}}
{{-- x,y --}}
{{-- 500,810,  vertical--}}
{{-- 510,55 --}}
{{-- $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 8,array(0,0,0)); --}}

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");

            $pdf->text(500,810, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);

        ');
    }
</script>

@endsection



