<?php

namespace Database\Factories;

use App\Models\Rack;
use App\Models\Room;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checkin>
 */
class CheckinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $room_id = Room::where('room_type_id', 1)->get()->random()->id;
        return [
            'note' => fake()->text(),
            'supplier_id' => Supplier::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'room_id' => $room_id,
        ];
    }
}
