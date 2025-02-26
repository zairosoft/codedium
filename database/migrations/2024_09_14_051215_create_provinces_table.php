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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id']);
        });
        DB::table('provinces')->insert(
            array(
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ],
                [
                    'created_by' => 1,
                    'updated_by' => 1
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
