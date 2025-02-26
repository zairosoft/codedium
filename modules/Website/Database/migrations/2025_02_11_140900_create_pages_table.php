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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id']);
        });

        Schema::create('page_langs', function (Blueprint $table) {
            $table->integer('page_id')->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('slug');
            $table->json('data')->nullable();
            $table->timestamps();
        });


        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id']);
        });

        Schema::create('page_block_langs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('data');
            $table->timestamps();
        });


        Permission::create(['name' => 'website view']);
        Permission::create(['name' => 'website create']);
        Permission::create(['name' => 'website update']);
        Permission::create(['name' => 'website delete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_langs');

        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('page_block_langs');
    }
};
