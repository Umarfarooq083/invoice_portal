<?php

namespace Database\Factories;

use App\Models\AppType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppTypeFactory extends Factory
{
    protected $model = AppType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['New Booking', 'Transfer', 'Exchange']),
        ];
    }
}
