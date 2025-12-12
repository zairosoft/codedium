<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('intentform', function (Blueprint $table) {
            $table->id();

            $table->timestamps();






            Schema::create('intentforms', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->integer('status')->default(0);
                $table->integer('volume')->nullable();
                $table->integer('number')->nullable();
                $table->date('date')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();
                $table->index(['id', 'name']);
            });

            Schema::create('intentform_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });

            //app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            Permission::create(['name' => 'intentform view']);
            Permission::create(['name' => 'intentform create']);
            Permission::create(['name' => 'intentform update']);
            Permission::create(['name' => 'intentform delete']);

            $roleSuper = Role::findByName('super-admin');
            $roleSuper->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform delete']);

            $roleAdmin = Role::findByName('admin');
            $roleAdmin->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform delete']);

            $roleStaff = Role::findByName('staff');
            $roleStaff->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform delete']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentform');
    }
};
