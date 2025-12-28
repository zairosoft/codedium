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
        Schema::table('expenses', function (Blueprint $table) {
            // Remove category field (moved to items only)
            $table->dropColumn('category');

            // Add new FlowAccount-style fields
            $table->date('due_date')->nullable()->after('date'); // วันครบกำหนดชำระ
            $table->string('currency', 10)->default('THB')->after('payment_method'); // สกุลเงิน
            $table->string('branch')->nullable()->after('payee'); // สาขาเจ้าหนี้
            $table->unsignedBigInteger('project_id')->nullable()->after('branch'); // โปรเจค

            // Financial calculations
            $table->boolean('vat_exempt')->default(false)->after('currency'); // ยกเว้น VAT
            $table->decimal('subtotal', 15, 2)->default(0)->after('total'); // ยอดรวมก่อนหัก
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('subtotal'); // ส่วนลด %
            $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_percentage'); // จำนวนส่วนลด
            $table->decimal('vat_percentage', 5, 2)->default(7)->after('discount_amount'); // VAT %
            $table->decimal('vat_amount', 15, 2)->default(0)->after('vat_percentage'); // จำนวน VAT
            $table->decimal('withholding_tax_percentage', 5, 2)->nullable()->after('vat_amount'); // หัก ณ ที่จ่าย %
            $table->decimal('withholding_tax_amount', 15, 2)->default(0)->after('withholding_tax_percentage'); // จำนวนหัก ณ ที่จ่าย
            $table->decimal('grand_total', 15, 2)->default(0)->after('withholding_tax_amount'); // ยอดรวมสุทธิ

            // Indexes for better query performance
            $table->index(['due_date', 'status']);
            $table->index(['project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Remove new fields
            $table->dropIndex(['due_date', 'status']);
            $table->dropIndex(['project_id']);

            $table->dropColumn([
                'due_date',
                'currency',
                'branch',
                'project_id',
                'vat_exempt',
                'subtotal',
                'discount_percentage',
                'discount_amount',
                'vat_percentage',
                'vat_amount',
                'withholding_tax_percentage',
                'withholding_tax_amount',
                'grand_total'
            ]);

            // Add back category field
            $table->string('category')->nullable()->after('reference_number');
        });
    }
};
