<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('intentform_types', function (Blueprint $table) {
            $table->integer('status')->default(1)->after('description')->comment('0=ไม่ใช้งาน, 1=ใช้งาน');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intentform_types', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
