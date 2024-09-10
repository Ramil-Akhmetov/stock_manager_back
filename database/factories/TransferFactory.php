<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\TransferStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reason' => fake()->text(),
            'user_id' => User::all()->random()->id,
            'transfer_status_id' => TransferStatus::all()->random()->id,
            'from_room_id' => Room::all()->random()->id,
            'to_room_id' => Room::all()->random()->id,
        ];
    }
}
