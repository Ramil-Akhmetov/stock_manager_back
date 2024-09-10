<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.read']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.delete']);

        Permission::create(['name' => 'categories.create']);
        Permission::create(['name' => 'categories.read']);
        Permission::create(['name' => 'categories.update']);
        Permission::create(['name' => 'categories.delete']);

        Permission::create(['name' => 'items.create']);
        Permission::create(['name' => 'items.read']);
        Permission::create(['name' => 'items.update']);
        Permission::create(['name' => 'items.delete']);

        Permission::create(['name' => 'types.create']);
        Permission::create(['name' => 'types.read']);
        Permission::create(['name' => 'types.update']);
        Permission::create(['name' => 'types.delete']);

        Permission::create(['name' => 'room_types.create']);
        Permission::create(['name' => 'room_types.read']);
        Permission::create(['name' => 'room_types.update']);
        Permission::create(['name' => 'room_types.delete']);

        Permission::create(['name' => 'rooms.create']);
        Permission::create(['name' => 'rooms.read']);
        Permission::create(['name' => 'rooms.update']);
        Permission::create(['name' => 'rooms.delete']);

        Permission::create(['name' => 'confirmations.create']);
        Permission::create(['name' => 'confirmations.read']);
        Permission::create(['name' => 'confirmations.update']);
        Permission::create(['name' => 'confirmations.delete']);

        Permission::create(['name' => 'customers.create']);
        Permission::create(['name' => 'customers.read']);
        Permission::create(['name' => 'customers.update']);
        Permission::create(['name' => 'customers.delete']);

        Permission::create(['name' => 'suppliers.create']);
        Permission::create(['name' => 'suppliers.read']);
        Permission::create(['name' => 'suppliers.update']);
        Permission::create(['name' => 'suppliers.delete']);

        Permission::create(['name' => 'checkins.create']);
        Permission::create(['name' => 'checkins.read']);
        Permission::create(['name' => 'checkins.update']);
        Permission::create(['name' => 'checkins.delete']);

        Permission::create(['name' => 'checkouts.create']);
        Permission::create(['name' => 'checkouts.read']);
        Permission::create(['name' => 'checkouts.update']);
        Permission::create(['name' => 'checkouts.delete']);

        Permission::create(['name' => 'transfers.create']);
        Permission::create(['name' => 'transfers.read']);
        Permission::create(['name' => 'transfers.update']);
        Permission::create(['name' => 'transfers.delete']);

        Permission::create(['name' => 'permissions.read']);

        Permission::create(['name' => 'activities.read']);

        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.read']);
        Permission::create(['name' => 'roles.update']);
        Permission::create(['name' => 'roles.delete']);

        Permission::create(['name' => 'responsibilities.create']);
        Permission::create(['name' => 'responsibilities.read']);
        Permission::create(['name' => 'responsibilities.update']);
        Permission::create(['name' => 'responsibilities.delete']);
    }
}
