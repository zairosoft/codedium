<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->date('date')->nullable();
            $table->string('refer')->nullable();
            $table->string('status')->nullable();
            $table->string('contact_code')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('unit')->nullable();
            $table->double('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('pre_tax')->nullable();
            $table->string('tax')->nullable();
            $table->double('total_vat')->nullable();
            $table->double('total')->nullable();
            $table->string('sale_name')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_product_targets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('sale_name')->nullable();
            $table->integer('category_id')->nullable(); // categories id
            $table->string('target')->nullable();
            $table->string('q1')->nullable();
            $table->string('q2')->nullable();
            $table->string('q3')->nullable();
            $table->string('q4')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_product_pipelines', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('sale_name')->nullable();
            $table->integer('category_id')->nullable(); // categories id
            $table->string('target')->nullable();
            $table->string('q1')->nullable();
            $table->string('q2')->nullable();
            $table->string('q3')->nullable();
            $table->string('q4')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Permission::create(['name' => 'sale view']);
        Permission::create(['name' => 'sale create']);
        Permission::create(['name' => 'sale update']);
        Permission::create(['name' => 'sale delete']);

        DB::table('sale_product_categories')->insert(
            array(
                [
                    'name' => 'Digital Platform'
                ],
                [
                    'name' => 'Digital Services'
                ],
                [
                    'name' => 'Digital Devices'
                ],
                [
                    'name' => 'Digital Development'
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sale_product_targets');
        Schema::dropIfExists('sale_product_pipelines');
        Schema::dropIfExists('sale_product_categories');
        Schema::dropIfExists('sale_products');
    }
};
