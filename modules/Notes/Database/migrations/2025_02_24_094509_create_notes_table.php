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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('tag')->nullable();
            $table->string('title')->nullable();
            $table->string('favorite')->nullable();
            $table->text('content')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'title']);
        });

        Permission::create(['name' => 'note view']);
        Permission::create(['name' => 'note create']);
        Permission::create(['name' => 'note update']);
        Permission::create(['name' => 'note delete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
