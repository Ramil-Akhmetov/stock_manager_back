<?php

namespace Database\Factories;

use App\Http\Controllers\InviteCodeController;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InviteCode>
 */
class InviteCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        return [
            'code' => InviteCodeController::generateCode(),
            'user_id' => $user->id,
        ];
    }
}
