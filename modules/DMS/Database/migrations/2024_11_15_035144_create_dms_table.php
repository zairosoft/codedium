<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('device_name')->nullable();
            $table->string('device_id')->nullable();
            $table->string('tel')->nullable();
            $table->string('car_type')->nullable();
            $table->string('car_plate_number')->nullable();
            $table->string('car_plate_number_sub')->nullable();
            $table->string('img')->nullable();
            $table->string('sim_number')->nullable();
            $table->string('sim_network')->nullable();
            $table->integer('sim_type')->nullable();

            $table->string('alarm')->nullable();
            $table->string('status')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('other')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'name']);
            $table->unique(['device_id']);
        });

        Schema::create('dms_sims', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'name']);
        });

        Schema::create('dms_sim_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'name']);
        });

        Schema::create('dms_alarms', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('type')->nullable();
            $table->string('detail')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('speed')->nullable();
            $table->string('img')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'device_id']);
        });

        Permission::create(['name' => 'dms view']);
        Permission::create(['name' => 'dms create']);
        Permission::create(['name' => 'dms update']);
        Permission::create(['name' => 'dms delete']);

        // $roleSuper = Role::findByName('super-admin');
        // $roleSuper->givePermissionTo(['dms create', 'dms view', 'dms update', 'dms delete']);

        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->givePermissionTo(['dms create', 'dms view', 'dms update', 'dms delete']);

        // $roleStaff = Role::findByName('staff');
        // $roleStaff->givePermissionTo(['dms view']);

        // insert data
        DB::table('dms_sims')->insert(
            array(
                [
                    'name' => 'ais'
                ],
                [
                    'name' => 'true',
                ],
                [
                    'name' => 'dtac',
                ],
                [
                    'name' => 'nt',
                ],
            )
        );
        DB::table('dms_sim_types')->insert(
            array(
                [
                    'name' => 'รายปี'
                ],
                [
                    'name' => 'รายเดือน',
                ],
                [
                    'name' => 'รายสัปดาห์',
                ],
                [
                    'name' => 'รายวัน',
                ],
                [
                    'name' => 'ตลอดอายุการใช้งาน',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dms');
        Schema::dropIfExists('dms_sim');
        Schema::dropIfExists('dms_sim_type');
        Schema::dropIfExists('dms_alarms');
    }
};
