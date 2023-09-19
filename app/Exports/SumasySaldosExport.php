<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SumasySaldosExport implements FromView
{
    use Exportable; //hace exportable a la clase

    private $datosEmpresaActiva;
    private $fechaInicio_buscado;
    private $fechaFin_buscado;
    private $registrosBCSS_entontrados;

    public function __construct($datosEmpresaActiva, $fechaInicio_buscado, $fechaFin_buscado, $registrosBCSS_entontrados)
    {
        $this->datosEmpresaActiva = $datosEmpresaActiva;
        $this->fechaInicio_buscado = $fechaInicio_buscado;
        $this->fechaFin_buscado = $fechaFin_buscado;
        $this->registrosBCSS_entontrados = $registrosBCSS_entontrados;
    }

    public function view(): View
    {
        return view('modulos.reportes_excel.sumas_y_saldos_excel',['datosEmpresaActiva'=>$this->datosEmpresaActiva, 'fechaInicio_buscado'=>$this->fechaInicio_buscado, 'fechaFin_buscado'=>$this->fechaFin_buscado, 'registrosBCSS_entontrados'=>$this->registrosBCSS_entontrados]);
    }

}
