<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('img')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'company_id']);
        });

		DB::table('product_categories')->insert(
            array(
                [
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1
                ],
            )
        );

        Schema::create('product_category_langs', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id'); // categories id
            $table->string('code')->nullable(); // Lang en, th
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('description')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'category_id']);
        });

		DB::table('product_category_langs')->insert(
            array(
                [
                    'category_id' => '1',
                    'code' => 'th',
                    'name' => 'ไม่มีหมวดหมู่'
                ],
                [
                    'category_id' => '1',
                    'code' => 'en',
                    'name' => 'No category'
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_category_langs');
    }
};
