<?php

namespace App\Exports;

use App\Models\Compra;
//use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ComprasExport implements FromView
{

    use Exportable; //hace exportable a la clase


    private $idSucursalBuscada;
    private $comprasEncontradas;


    public function __construct($comprasEncontradas, $idSucursalBuscada)
    {
        $this->comprasEncontradas = $comprasEncontradas;
        $this->idSucursalBuscada = $idSucursalBuscada;
    }


    public function view(): View
    {
        //$comprasEncontradas = Compra::all(); //no se puede usar DB

        //var_dump($comprasEncontradas);

        return view('modulos.reportes_excel.compras_excel',['comprasEncontradas'=> $this->comprasEncontradas, 'idSucursalBuscada'=> $this->idSucursalBuscada]);   
    }
}
