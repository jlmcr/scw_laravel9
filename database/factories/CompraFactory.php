<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sucursal;
use App\Models\Proveedor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compra>
 */
class CompraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $importe = $this->faker->numberBetween(5,10000);
        //$importe = $this->faker->number_format(6,2);

        return [
            'especificacion'=> '1',
            'nitProveedor'=> $this->faker->numberBetween(1000000,99999999),
            'razonSocialProveedor'=>$this->faker->text(20),
            'codigoAutorizacion'=> $this->faker->numberBetween(1000000,99999999),
            'numeroFactura'=> $this->faker->unique()->numberBetween(300,1000),
            'dim'=> $this->faker->word(),
            // 'fecha'=> $this->faker->date('Y-m-d'),
            'fecha'=> '2022-9-1',
            'importeTotal'=> $importe,
            'ice'=> '0',
            'iehd'=> '0',
            'ipj'=> '0',
            'tasas'=>'0',
            'otrosNoSujetosaCF'=>'0',
            'excentos'=>'0',
            'tasaCero'=>'0',
            'subtotal'=>$importe,
            'descuentos'=>'0',
            'gifCard'=>'0',
            'baseParaCF'=>$importe,
            'creditoFiscal'=>$importe * 0.13,
            'tipoCompra'=>'1',
            'codigoControl'=>$this->faker->text(10),
            'combustible'=> $this->faker->randomElement([0,1]),
            'sucursal_id'=>Sucursal::all()->random()->id,
        ];
    }
}
