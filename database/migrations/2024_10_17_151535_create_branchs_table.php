<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branchs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('tel')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->timestamps();
            $table->index(['id', 'company_id']);
        });


        Schema::create('branch_langs', function (Blueprint $table) {
            $table->integer('branch_id');
            $table->string('code')->default('th');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();
            $table->index(['branch_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branchs');
        Schema::dropIfExists('branch_langs');
    }
};
