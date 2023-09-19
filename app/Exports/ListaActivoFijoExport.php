<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ListaActivoFijoExport implements FromView
{

    private $datosEmpresaActiva;
    private $rubros;
    private $rubroSeleccionado;
    private $rubro_buscado;
    private $activosFijosEncontrados;

    public function __construct($datosEmpresaActiva, $rubros, $rubroSeleccionado, $rubro_buscado, $activosFijosEncontrados)
    {
        $this->datosEmpresaActiva = $datosEmpresaActiva;
        $this->rubros = $rubros;
        $this->rubroSeleccionado = $rubroSeleccionado;
        $this->rubro_buscado = $rubro_buscado;
        $this->activosFijosEncontrados = $activosFijosEncontrados;
    }
    public function view(): View
    {
        return view('modulos.reportes_excel.listado_activo_fijo_EXCEL',
        [
            'datosEmpresaActiva'=>$this->datosEmpresaActiva,
            'rubros'=>$this->rubros,
            'rubroSeleccionado'=>$this->rubroSeleccionado,
            'rubro_buscado'=>$this->rubro_buscado,
            'activosFijosEncontrados'=>$this->activosFijosEncontrados,
        ]);

    }

}
