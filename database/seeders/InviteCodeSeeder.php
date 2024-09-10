<?php

namespace Database\Seeders;

use App\Models\InviteCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InviteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InviteCode::factory(5)->create();
    }
}
