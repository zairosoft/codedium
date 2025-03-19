<?php

namespace Modules\Invoices\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InvoicesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::create(['name' => 'invoice view']);
        Permission::create(['name' => 'invoice create']);
        Permission::create(['name' => 'invoice update']);
        Permission::create(['name' => 'invoice delete']);

        $roleSuper = Role::findByName('super-admin');
        $roleSuper->givePermissionTo(['invoice create', 'invoice view', 'invoice update', 'invoice delete']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo(['invoice create', 'invoice view', 'invoice update']);
    }
}
