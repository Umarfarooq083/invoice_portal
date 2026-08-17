<?php

namespace Database\Factories;

use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhaseFactory extends Factory
{
    protected $model = Phase::class;

    public function definition(): array
    {
        return [
            'name' => 'Phase '.fake()->numberBetween(1, 10),
        ];
    }
}
