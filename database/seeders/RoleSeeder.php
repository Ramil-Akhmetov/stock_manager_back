<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::create(['name' => 'Администратор']);
        $user_role = Role::create(['name' => 'Ответственное лицо']);
        $keeper_role = Role::create(['name' => 'Кладовщик']);

//        $role = Role::create(['name' => 'Admin']);

//        foreach ([$admin_role, $user_role, $keeper_role] as $role) {
//            $role->givePermissionTo('users.create');
//            $role->givePermissionTo('users.read');
//            $role->givePermissionTo('users.update');
//            $role->givePermissionTo('users.delete');
//
//            $role->givePermissionTo('categories.create');
//            $role->givePermissionTo('categories.read');
//            $role->givePermissionTo('categories.update');
//            $role->givePermissionTo('categories.delete');
//
//            $role->givePermissionTo('items.create');
//            $role->givePermissionTo('items.read');
//            $role->givePermissionTo('items.update');
//            $role->givePermissionTo('items.delete');
//
//            $role->givePermissionTo('types.create');
//            $role->givePermissionTo('types.read');
//            $role->givePermissionTo('types.update');
//            $role->givePermissionTo('types.delete');
//
//            $role->givePermissionTo('room_types.create');
//            $role->givePermissionTo('room_types.read');
//            $role->givePermissionTo('room_types.update');
//            $role->givePermissionTo('room_types.delete');
//
//            $role->givePermissionTo('rooms.create');
//            $role->givePermissionTo('rooms.read');
//            $role->givePermissionTo('rooms.update');
//            $role->givePermissionTo('rooms.delete');
//
//            $role->givePermissionTo('confirmations.create');
//            $role->givePermissionTo('confirmations.read');
//            $role->givePermissionTo('confirmations.update');
//            $role->givePermissionTo('confirmations.delete');
//
//            $role->givePermissionTo('customers.create');
//            $role->givePermissionTo('customers.read');
//            $role->givePermissionTo('customers.update');
//            $role->givePermissionTo('customers.delete');
//
//            $role->givePermissionTo('suppliers.create');
//            $role->givePermissionTo('suppliers.read');
//            $role->givePermissionTo('suppliers.update');
//            $role->givePermissionTo('suppliers.delete');
//
//            $role->givePermissionTo('checkins.create');
//            $role->givePermissionTo('checkins.read');
//            $role->givePermissionTo('checkins.update');
//            $role->givePermissionTo('checkins.delete');
//
//            $role->givePermissionTo('checkouts.create');
//            $role->givePermissionTo('checkouts.read');
//            $role->givePermissionTo('checkouts.update');
//            $role->givePermissionTo('checkouts.delete');
//
//            $role->givePermissionTo('transfers.create');
//            $role->givePermissionTo('transfers.read');
//            $role->givePermissionTo('transfers.update');
//            $role->givePermissionTo('transfers.delete');
//
//            $role->givePermissionTo('permissions.read');
//
//            $role->givePermissionTo('activities.read');
//
//            $role->givePermissionTo('roles.create');
//            $role->givePermissionTo('roles.read');
//            $role->givePermissionTo('roles.update');
//            $role->givePermissionTo('roles.delete');
//
//            $role->givePermissionTo('responsibilities.create');
//            $role->givePermissionTo('responsibilities.read');
//            $role->givePermissionTo('responsibilities.update');
//            $role->givePermissionTo('responsibilities.delete');
//        }
    }
}
