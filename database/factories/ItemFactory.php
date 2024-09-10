<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Rack;
use App\Models\Room;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $room = Room::all()->random();

        $rack = null;
        if ($room->room_type_id == 1) {
            $rack = Rack::where('room_id', $room->id)->get()->random()->id;
        }
        return [
            'name' => fake()->word(),
            'code' => Str::random(5),
            'quantity' => fake()->numberBetween(0, 1000),
            'category_id' => Category::all()->random()->id,
            'type_id' => Type::all()->random()->id,
            'room_id' => $room->id,
            'rack_id' => $rack,
            'unit' => 'шт',
        ];
    }
}
