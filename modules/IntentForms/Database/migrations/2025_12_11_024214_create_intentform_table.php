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
        Schema::create('intentforms', function (Blueprint $table) {
            $table->id();
            $table->integer('volume')->nullable(); // เล่มที่
            $table->integer('number')->nullable(); // เลขที่
            $table->string('account_name')->nullable(); // ชื่อบัญชี
            $table->string('account_number')->nullable(); // เลขที่บัญชี
            $table->string('account_bank')->nullable(); // ธนาคาร
            $table->string('refer')->nullable(); // อ้างอิง
            $table->string('name')->nullable(); //บัตรนี้แสดงว่า
            $table->integer('status')->default(0); // สถานะ
            $table->date('date')->nullable(); //วันที่ทำรายการ
            $table->string('other')->nullable(); //อื่นๆที่บริจาก
            $table->string('payee')->nullable(); //ผู้รับเงิน
            $table->string('payment_methods')->default('เงินสด'); //ช่องทางการชำระเงิน
            $table->float('total')->nullable(); //จำนวนเงินรวม
            $table->text('notes')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'name']);
        });

        Schema::create('intentform_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('intentform_donations', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id')->nullable();
            $table->integer('intentform_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
        });

        //app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::create(['name' => 'intentform view']);
        Permission::create(['name' => 'intentform create']);
        Permission::create(['name' => 'intentform update']);
        Permission::create(['name' => 'intentform allow']);
        Permission::create(['name' => 'intentform delete']);

        // $roleSuper = Role::findByName('super-admin');
        // $roleSuper->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform allow', 'intentform delete']);

        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform delete']);

        // $roleStaff = Role::findByName('staff');
        // $roleStaff->givePermissionTo(['intentform create', 'intentform view', 'intentform update', 'intentform delete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentform');
    }
};
