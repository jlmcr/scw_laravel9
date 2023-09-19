<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//!
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //! 2da opcion - con factory
        Empresa::factory(20)->create();

        //! 1ra opcion - solo con seeder

        /*$data =[
            'nombreEmpresa'=>'empresa',
            'representanteLegal'=>'abc',
            'nit'=>'123456',
            'fechaInicial'=>'2022/01/01',
            'fechaCierre'=>'2022/01/01',
            'ejercicioFiscal'=>2022,
            'direccion'=>'abc',
            'actividadComercial'=>'abc',
            'tipoSociedad'=>'abc',
        ];

         DB::table('empresas')->insert($data); */
    }
}
