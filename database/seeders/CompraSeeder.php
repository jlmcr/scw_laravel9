<?php

namespace Database\Seeders;

use App\Models\Compra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            //! con factory
			Compra::factory(100)->create();
    }
}
