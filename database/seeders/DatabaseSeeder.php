<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            CategorySeeder::class,
            TypeSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,

            RackSeeder::class,

            ItemSeeder::class,
            ConfirmationSeeder::class,
            SupplierSeeder::class,
            CheckinSeeder::class,
            CheckoutSeeder::class,
            TransferStatusSeeder::class,
            TransferSeeder::class,
            ResponsibilitySeeder::class,
            InviteCodeSeeder::class,
        ]);
    }
}
