<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class EstadoResultadoExport implements FromView
{
    use Exportable; //hace exportable a la clase , averiguar

    private $datosEmpresa;
    private $datosEjercicioActivo;
    private $fechaInicio_buscado_er;
    private $fechaFin_buscado_er;
    private $acumulado_subcuentas_er;
    private $acumulado_cuentas_er;
    private $acumulado_subgrupos_er;
    private $acumulado_grupos_er;
    private $acumulado_tipos_er;
    private $fechaInicio_buscado_bg;
    private $fechaFin_buscado_bg;
    private $acumulado_subcuentas_bg;
    private $acumulado_cuentas_bg;
    private $acumulado_subgrupos_bg;
    private $acumulado_grupos_bg;
    private $acumulado_tipos_bg;
    private $tipos_todos;
    private $grupos_todos;


    public function __construct($datosEmpresa, $datosEjercicioActivo, $fechaInicio_buscado_er, $fechaFin_buscado_er, $acumulado_subcuentas_er, $acumulado_cuentas_er, $acumulado_subgrupos_er, $acumulado_grupos_er, $acumulado_tipos_er, $fechaInicio_buscado_bg, $fechaFin_buscado_bg, $acumulado_subcuentas_bg, $acumulado_cuentas_bg, $acumulado_subgrupos_bg, $acumulado_grupos_bg, $acumulado_tipos_bg, $tipos_todos, $grupos_todos)
    {
        $this->datosEmpresa = $datosEmpresa;
        $this->datosEjercicioActivo = $datosEjercicioActivo;
        $this->fechaInicio_buscado_er = $fechaInicio_buscado_er;
        $this->fechaFin_buscado_er = $fechaFin_buscado_er;
        $this->acumulado_subcuentas_er = $acumulado_subcuentas_er;
        $this->acumulado_cuentas_er = $acumulado_cuentas_er;
        $this->acumulado_subgrupos_er = $acumulado_subgrupos_er;
        $this->acumulado_grupos_er = $acumulado_grupos_er;
        $this->acumulado_tipos_er = $acumulado_tipos_er;
        $this->fechaInicio_buscado_bg = $fechaInicio_buscado_bg;
        $this->fechaFin_buscado_bg = $fechaFin_buscado_bg;
        $this->acumulado_subcuentas_bg = $acumulado_subcuentas_bg;
        $this->acumulado_cuentas_bg = $acumulado_cuentas_bg;
        $this->acumulado_subgrupos_bg = $acumulado_subgrupos_bg;
        $this->acumulado_grupos_bg = $acumulado_grupos_bg;
        $this->acumulado_tipos_bg = $acumulado_tipos_bg;
        $this->tipos_todos = $tipos_todos;
        $this->grupos_todos = $grupos_todos;
    }


    public function view(): View
    {
        return view('modulos.reportes_excel.eeff.eerr_EXCEL',[
            'datosEmpresa'=>$this->datosEmpresa,
            'datosEjercicioActivo'=>$this->datosEjercicioActivo,
            'fechaInicio_buscado_er'=>$this->fechaInicio_buscado_er,
            'fechaFin_buscado_er'=>$this->fechaFin_buscado_er,
            'acumulado_subcuentas_er'=>$this->acumulado_subcuentas_er,
            'acumulado_cuentas_er'=>$this->acumulado_cuentas_er,
            'acumulado_subgrupos_er'=>$this->acumulado_subgrupos_er,
            'acumulado_grupos_er'=>$this->acumulado_grupos_er,
            'acumulado_tipos_er'=>$this->acumulado_tipos_er,
            'fechaInicio_buscado_bg'=>$this->fechaInicio_buscado_bg,
            'fechaFin_buscado_bg'=>$this->fechaFin_buscado_bg,
            'acumulado_subcuentas_bg'=>$this->acumulado_subcuentas_bg,
            'acumulado_cuentas_bg'=>$this->acumulado_cuentas_bg,
            'acumulado_subgrupos_bg'=>$this->acumulado_subgrupos_bg,
            'acumulado_grupos_bg'=>$this->acumulado_grupos_bg,
            'acumulado_tipos_bg'=>$this->acumulado_tipos_bg,
            'tipos_todos'=>$this->tipos_todos,
            'grupos_todos'=>$this->grupos_todos
        ]);
    }

}
