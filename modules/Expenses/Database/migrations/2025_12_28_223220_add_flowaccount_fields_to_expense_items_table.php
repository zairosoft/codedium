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
        Schema::table('expense_items', function (Blueprint $table) {
            // Add new fields
            $table->unsignedBigInteger('chart_of_account_id')->nullable()->after('category_id'); // บัญชีรายจ่าย
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('unit_price'); // ส่วนลด %
            $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_percentage'); // จำนวนส่วนลด
            $table->decimal('amount', 15, 2)->default(0)->after('discount_amount'); // จำนวนเงินก่อนลด
            $table->decimal('total', 15, 2)->default(0)->after('amount'); // จำนวนเงินหลังลด

            // Drop old sub_total as it will be calculated
            $table->dropColumn('sub_total');

            // Add index
            $table->index(['chart_of_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->dropIndex(['chart_of_account_id']);

            $table->dropColumn([
                'chart_of_account_id',
                'discount_percentage',
                'discount_amount',
                'amount',
                'total'
            ]);

            // Add back sub_total
            $table->float('sub_total')->nullable()->after('unit_price');
        });
    }
};
