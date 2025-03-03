<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\UserSettings;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Settings;
use App\Models\Company;
use App\Models\CompanyLang;
use Spatie\Permission\Models\Permission;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /// Create Company
        Company::create([ 'email' => 'email@company.com', 'created_by' => 1, 'updated_by' => 1]);
        CompanyLang::create(['company_id' => 1, 'name' => 'My Company', 'code' => 'th']);

        // Create Settings
        Settings::create(['key' => 'logo', 'value' => 'logo.svg', 'description' => 'Logo Website']);
        Settings::create(['key' => 'favicon', 'value' => 'favicon.ico', 'description' => 'Logo favicon Website']);

        // Create Permissions
        Permission::create(['name' => 'role view']);
        Permission::create(['name' => 'role create']);
        Permission::create(['name' => 'role update']);
        Permission::create(['name' => 'role delete']);

        Permission::create(['name' => 'permission view']);
        Permission::create(['name' => 'permission create']);
        Permission::create(['name' => 'permission update']);
        Permission::create(['name' => 'permission delete']);

        Permission::create(['name' => 'user view']);
        Permission::create(['name' => 'user create']);
        Permission::create(['name' => 'user update']);
        Permission::create(['name' => 'user delete']);

        Permission::create(['name' => 'dashboard view']);

        Permission::create(['name' => 'app view']);
        Permission::create(['name' => 'app create']);
        Permission::create(['name' => 'app update']);
        Permission::create(['name' => 'app delete']);

        Permission::create(['name' => 'setting view']);
        Permission::create(['name' => 'setting create']);
        Permission::create(['name' => 'setting update']);
        Permission::create(['name' => 'setting delete']);

        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['role create', 'role view', 'role update']);
        $adminRole->givePermissionTo(['permission create', 'permission view']);
        $adminRole->givePermissionTo(['user create', 'user view', 'user update']);


        // Let's Create User and assign Role to it.
        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        Account::create([
            'user_id' => $superAdminUser->id,
            'company_id' => 1,
        ]);
        UserSettings::create([
            'user_id' => $superAdminUser->id,
            'theme' => 'light',
        ]);
        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);
        Account::create([
            'user_id' => $adminUser->id,
            'company_id' => 1,
        ]);
        UserSettings::create([
            'user_id' => $adminUser->id,
            'theme' => 'light',
        ]);
        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
            'email' => 'staff@gmail.com',
        ], [
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('staff'),
        ]);
        Account::create([
            'user_id' => $staffUser->id,
            'company_id' => 1,
        ]);
        UserSettings::create([
            'user_id' => $staffUser->id,
            'theme' => 'light',
        ]);
        $staffUser->assignRole($staffRole);
    }
}
