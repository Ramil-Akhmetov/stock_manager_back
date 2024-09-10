<?php

namespace Database\Seeders;

use App\Models\Checkout;
use App\Models\Item;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $checkouts = Checkout::factory(10)->create();

        foreach ($checkouts as $checkout) {
            $items = Item::factory(5)->create();

            foreach ($items as $item) {
                $fullCheckout = $faker->boolean();

                $quantity = $fullCheckout ? $item->quantity : $faker->numberBetween(1, $item->quantity);

                $checkout->items()->attach($item, [
                    'rack_id' => $item->rack_id,
                    'fullCheckout' => $fullCheckout,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
