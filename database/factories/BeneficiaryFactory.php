<?php

namespace Database\Factories;

use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Beneficiary;
use Laragear\Rut\Generator;


class BeneficiaryFactory extends Factory
{
    protected $model = Beneficiary::class;

    public function definition(): array
    {
        $sector = Sector::inRandomOrder()->first();
        $rut_generator = new Generator();

        return [
            'name' => $this->faker->name(),
            'rut' => $rut_generator->asPeople()->makeOne(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'sector_id' => $sector,
            'city' => function () use ($sector) {
                return $sector->name === "Fuera de la comuna" ? $this->faker->city() : null;
            },
            'notes' => '',
        ];
    }
}

