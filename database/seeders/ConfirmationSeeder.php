<?php

namespace Database\Seeders;

use App\Models\Confirmation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Confirmation::factory(30)->create();
    }
}
