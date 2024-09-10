<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checkout>
 */
class CheckoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => fake()->text(),
            'user_id' => User::all()->random()->id,
            'room_id' => Room::where('room_type_id', 1)->get()->random()->id,
        ];
    }
}
