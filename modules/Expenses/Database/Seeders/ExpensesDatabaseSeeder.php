<?php

namespace Modules\Expenses\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExpensesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();



            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id']);
        });






        Permission::create(['name' => 'expenses view']);
        Permission::create(['name' => 'expenses create']);
        Permission::create(['name' => 'expenses update']);
        Permission::create(['name' => 'expenses allow']);
        Permission::create(['name' => 'expenses delete']);
    }
}
