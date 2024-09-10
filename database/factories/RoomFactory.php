<?php

namespace Database\Factories;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->numberBetween(1, 300),
            'name' => fake()->word(),
            'user_id' => User::all()->random()->id,
            'room_type_id' => RoomType::all()->random()->id,
        ];
    }
}
