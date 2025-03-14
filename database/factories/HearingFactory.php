<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use App\Models\Hearing;
use App\Models\RequestType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hearing>
 */
class HearingFactory extends Factory
{
    protected $model = Hearing::class;

    public function definition(): array
    {
        $beneficiary = Beneficiary::inRandomOrder()->first();
        $request_type = RequestType::inRandomOrder()->first();

        return [
            'requested_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'beneficiary_id' => $beneficiary,
            'request_type_id' => $request_type,
            'details' => $this->faker->text(200),
            'hearing_date' => $this->faker->dateTimeBetween('-1 year', '+6 weeks')->format('Y-m-d'),
            'hearing_time' => $this->faker->time(),
            'did_assist' => $this->faker->boolean(),
        ];
    }
}
