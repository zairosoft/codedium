<?php

namespace Modules\Sales\Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class SalesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::create(['name' => 'sales view']);
        Permission::create(['name' => 'sales create']);
        Permission::create(['name' => 'sales update']);
        Permission::create(['name' => 'sales delete']);

        $roleSuper = Role::findByName('super-admin');
        $roleSuper->givePermissionTo(['sales create', 'sales view', 'sales update', 'sales delete']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo(['sales create', 'sales view', 'sales update']);
    }
}
