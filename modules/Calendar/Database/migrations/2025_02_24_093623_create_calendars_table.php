<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->enum('badge', ['work', 'remind', 'training', 'holiday', 'personal', 'meeting']);
            $table->text('link')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('is_seen')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'title']);
        });

        Schema::create('event_users', function (Blueprint $table) {
            $table->integer('event_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->index(['event_id']);
        });

        //app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::create(['name' => 'calendar view']);
        Permission::create(['name' => 'calendar create']);
        Permission::create(['name' => 'calendar update']);
        Permission::create(['name' => 'calendar delete']);

        // $roleSuper = Role::findByName('super-admin');
        // $roleSuper->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);

        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);

        // $roleStaff = Role::findByName('staff');
        // $roleStaff->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_users');
    }
};
