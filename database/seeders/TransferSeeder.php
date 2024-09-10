<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Rack;
use App\Models\Transfer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $transfers = Transfer::factory(10)->create();

        foreach ($transfers as $transfer) {
            $items = [];
            for ($i = 0; $i < 5; $i++) {
                $items[] = Item::factory()->create([
                    'rack_id' => $transfer->from_room->room_type_id == 1 ? Rack::where('room_id', $transfer->from_room_id)->get()->random()->id : null,
                ]);
            }

            foreach ($items as $item) {
                $fullTransfer = $faker->boolean();
                $quantity = $fullTransfer ? $item->quantity : $faker->numberBetween(1, $item->quantity);

                if ($transfer->to_room->room_type_id == 1) {
                    $ro_rack_id = Rack::where('room_id', $transfer->to_room_id)->get()->random()->id;
                } else {
                    $ro_rack_id = null;
                }

                $transfer->items()->attach($item, [
                    'from_rack_id' => $item->rack_id,
                    'to_rack_id' => $ro_rack_id,
                    'fullTransfer' => $fullTransfer,
                    'newCode' => $fullTransfer ? null : Str::random(5),
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
