<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Responsibility>
 */
class ResponsibilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => fake()->date(),
            'end_date' => date('2025_01_01'),
            'user_id' => User::all()->random()->id,
            'room_id' => Room::all()->random()->id,
        ];
    }
}
