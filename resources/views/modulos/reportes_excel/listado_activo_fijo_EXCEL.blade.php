{{-- ! Encabezado --}}

    <table>
        <tr>
            <td style="color: blue; font-weight: bold">
                {{$datosEmpresaActiva->denominacionSocial}}
            </td>

        </tr>

        <tr>
            <td style="color: blue; font-weight: bold">
                NIT: {{$datosEmpresaActiva->nit}}
            </td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr>
            <td colspan="5" style="text-align: center; color: blue; font-weight: bold">
                LISTADO DE ACTIVO FIJO
            </td>
        </tr>

        <tr>
            <td colspan="5" style="height: 10px"></td>
        </tr>
    </table>

{{-- ! Fin Encabezado --}}
    <table >
        @if ($rubro_buscado=="todos")
            <tr>
                <th style="color: blue; font-weight: bold; width: 170px">
                    <p>Categoría o Rubro:</p>
                </th>
                <td style="width: 60px">
                    <p>Todos</p>
                </td>
            </tr>
            <tr>
                <th>
                    <p style="color: blue; font-weight: bold;">Años de Vida Útil:</p>
                </th>
                <td>
                    <p>-</p>
                </td>
            </tr>
            <tr>
                <th>
                    <p style="color: blue; font-weight: bold">Sujeto a depreciación:</p>
                </th>
                <td>
                    <p>-</p>
                </td>
            </tr>
            <tr>
                <th style="color: blue; font-weight: bold">
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
                <th style="color: blue; font-weight: bold; width: 170px">
                    <p>Categoría o Rubro:</p>
                </th>
                <td style="width: 150px; text-align: left">
                    <p>{{$rubro_buscado->rubro}}</p>
                </td>
            </tr>
            <tr>
                <th style="color: blue; font-weight: bold">
                    <p>Años de Vida Útil:</p>
                </th>
                <td style="text-align: left">
                    <p>{{$rubro_buscado->aniosVidaUtil}}</p>
                </td>
            </tr>
            <tr>
                <th style="color: blue; font-weight: bold">
                    <p>Sujeto a depreciación:</p>
                </th>
                <td style="text-align: left">
                    @if ($rubro_buscado->sujetoAdepreciacion == 1)
                        <p>Si</p>
                    @else
                        <p>No</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="color: blue; font-weight: bold">
                    <p>Cantidad Items (activos):</p>
                </th>

                <td style="text-align: left">
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

    {{-- tabla --}}
    @if ($rubroSeleccionado != "")
        @if (isset($activosFijosEncontrados))

            <table>
                <thead>
                    <tr>
                        <th style="color: blue; font-weight: bold; text-align: center;">
                            <p>Nro.</p>
                        </th>
                        <th style="height: 40px; color: blue; font-weight: bold; text-align: center;">
                            <p>Item</p>
                        </th>
                        <th style="width: 300px; color: blue; font-weight: bold; text-align: center;">
                            <p>Descripción</p>
                        </th>
                        <th style="width: 100px; color: blue; font-weight: bold; text-align: center;">
                            <p>Cantidad</p>
                        </th>
                        <th style="width: 100px; color: blue; font-weight: bold; text-align: center;">
                            <p>Medida</p>
                        </th>
                        <th style="width: 100px; color: blue; font-weight: bold; text-align: center;">
                            <p>Situación</p>
                        </th>
                        <th style="width: 100px; color: blue; font-weight: bold; text-align: center;">
                            <p>Estado del A.F.</p>
                        </th>
                        @if ($rubroSeleccionado == '-1')
                            <th style="width: 100px; color: blue; font-weight: bold; text-align: center;">
                                <p>Categoría/Rubro</p>
                            </th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @php // declaramos la variable, no la imprimimos aun
                        $numero = 0;
                    @endphp
                    @foreach ($activosFijosEncontrados as $activo)
                        <tr>
                            <td >
                                <p>{{ $numero = $numero + 1 }}</p>
                            </td>
                            <td >
                                <p>{{ $activo->id }}</p>
                            </td>
                            <td>
                                <p>{{ $activo->activoFijo }}</p>
                            </td>
                            <td >
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
    {{-- ! Fin Contenido --}}

