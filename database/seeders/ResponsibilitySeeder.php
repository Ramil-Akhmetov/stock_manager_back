<?php

namespace Database\Seeders;

use App\Models\Responsibility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Responsibility::factory(30)->create();
    }
}
