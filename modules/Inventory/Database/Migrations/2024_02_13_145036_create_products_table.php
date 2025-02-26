<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('category_id');
            $table->integer('discount_id');
            $table->string('barcode')->nullable();
            $table->integer('price');
            $table->string('cost')->nullable();
            $table->string('model');
            $table->string('img');
            $table->string('sku')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('status')->nullable(); // 0 = ซ่อน || 1 = เผยแพร่ || 2 = ร่าง
            $table->date('publish_schedule')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'company_id']);
        });

        Schema::create('product_langs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('code'); // Lang en, th
            $table->string('name');
            $table->string('manufacturer_name')->nullable();
            $table->string('manufacturer_brand')->nullable();
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->index(['id', 'product_id']);
        });


        Permission::create(['name' => 'inventory view']);
        Permission::create(['name' => 'inventory create']);
        Permission::create(['name' => 'inventory update']);
        Permission::create(['name' => 'inventory delete']);

        // $roleSuper = Role::findByName('super-admin');
        // $roleSuper->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);

        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);

        // $roleStaff = Role::findByName('staff');
        // $roleStaff->givePermissionTo(['calendar create', 'calendar view', 'calendar update', 'calendar delete']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_langs');
    }
};
