<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nit'=> $this->faker->unique->numberBetween(12900000, 99999999),
            'denominacionSocial'=> $this->faker->word(),
            'representanteLegal'=> $this->faker->word(),
            'sociedad'=> $this->faker->word(),
            'actividad'=> $this->faker->word(),
            'representanteLegal'=> $this->faker->word(),
            'ci'=> $this->faker->word(),
            'complemento'=> 'LP',
            'extension'=> 'LP',
            'clasificacion'=> $this->faker->word(),
        ];
    }
}
