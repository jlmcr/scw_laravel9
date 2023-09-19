<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroDiarioExport implements FromView
{
    private $datosEmpresaActiva; 
    private $fechaInicio_buscado; 
    private $fechaFin_buscado; 
    private $comprobantesEncontrados; 
    private $detalleComprobante; 
    private $cuentasDetalle;
    
    public function __construct($datosEmpresaActiva, $fechaInicio_buscado, $fechaFin_buscado, $comprobantesEncontrados, $detalleComprobante, $cuentasDetalle)
    {
        $this->datosEmpresaActiva = $datosEmpresaActiva;
        $this->fechaInicio_buscado = $fechaInicio_buscado;
        $this->fechaFin_buscado = $fechaFin_buscado;
        $this->comprobantesEncontrados = $comprobantesEncontrados;
        $this->detalleComprobante = $detalleComprobante;
        $this->cuentasDetalle = $cuentasDetalle;
    }

    public function view(): View
    {
        return view('modulos.reportes_excel.libro_diario_EXCEL',['datosEmpresaActiva'=>$this->datosEmpresaActiva, 'fechaInicio_buscado'=>$this->fechaInicio_buscado, 'fechaFin_buscado'=>$this->fechaFin_buscado, 'comprobantesEncontrados'=>$this->comprobantesEncontrados, 'detalleComprobante'=>$this->detalleComprobante, 'cuentasDetalle'=>$this->cuentasDetalle
        ]);

    }
}
