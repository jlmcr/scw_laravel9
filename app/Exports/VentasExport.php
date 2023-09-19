<?php

namespace App\Exports;

use App\Models\Venta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class VentasExport implements FromView
{

    use Exportable; //hace exportable a la clase

    private $idSucursalBuscada;
    private $ventasEncontradas;

    public function __construct($ventasEncontradas, $idSucursalBuscada)
    {
        $this->ventasEncontradas = $ventasEncontradas;
        $this->idSucursalBuscada = $idSucursalBuscada;
    }

    public function view(): View
    {
        return view('modulos.reportes_excel.ventas_excel',['ventasEncontradas'=> $this->ventasEncontradas, 'idSucursalBuscada'=> $this->idSucursalBuscada]);   
    }
}
