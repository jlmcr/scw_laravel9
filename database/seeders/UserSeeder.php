<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//!
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            'name'=>'Jose Luis Machicado',
            'email'=>'machicadocruzjose@gmail.com',
            'password'=>'12925193',
        ];
    }
}
