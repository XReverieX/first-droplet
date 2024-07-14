<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
          Permission::create(['name' => 'view users']);
          Permission::create(['name' => 'edit users']);

          Permission::create(['name' => 'view roles']);
          Permission::create(['name' => 'edit roles']);
          Permission::create(['name' => 'delete roles']);

          Permission::create(['name' => 'view permissions']);
          Permission::create(['name' => 'edit permissions']);
          Permission::create(['name' => 'delete permissions']);

//        Permission::create(['name' => 'edit articles']);
//        Permission::create(['name' => 'delete articles']);
//        Permission::create(['name' => 'publish articles']);
//        Permission::create(['name' => 'unpublish articles']);

        // create roles and assign created permissions

        // or may be done by chaining
//        $role = Role::create(['name' => 'moderator'])
//            ->givePermissionTo(['publish articles', 'unpublish articles']);

        Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
    }
}
