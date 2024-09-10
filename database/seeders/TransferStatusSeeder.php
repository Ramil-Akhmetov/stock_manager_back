<?php

namespace Database\Seeders;

use App\Models\TransferStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransferStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferStatus::factory()->create([
            'name' => 'В ожидании',
        ]);
        TransferStatus::factory()->create([
            'name' => 'Отменен',
        ]);
        TransferStatus::factory()->create([
            'name' => 'Подтвержден',
        ]);
    }
}
