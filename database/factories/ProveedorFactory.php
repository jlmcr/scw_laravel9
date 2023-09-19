<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nit'=> $this->faker->unique()->numberBetween(1000000,99999999),
            'nombreProveedor'=> $this->faker->text(50),
            'combustible'=> $this->faker->randomElement([0,1]),
            'ultimoCodigoAutorizacion'=> $this->faker->numberBetween(1000000,99999999)
            // 'ultimoCodigoAutorizacion'=> $this->faker->number_format('##',0),
        ];
    }
}
