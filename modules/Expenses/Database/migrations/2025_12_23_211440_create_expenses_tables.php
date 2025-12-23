<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Modules\Expenses\Database\Seeders\ExpensesDatabaseSeeder;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Main expenses table
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable(); // วันที่ทำรายการ
            $table->string('reference_number')->nullable(); // เลขที่อ้างอิง
            $table->string('category')->nullable(); // หมวดหมู่
            $table->string('payee')->nullable(); // ผู้รับเงิน/ผู้จ่าย
            $table->string('payment_method')->default('เงินสด'); // ช่องทางการชำระเงิน
            $table->float('total')->nullable(); // จำนวนเงินรวม
            $table->text('description')->nullable(); // รายละเอียด
            $table->text('notes')->nullable(); // หมายเหตุ
            $table->integer('status')->default(1); // สถานะ
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->index(['id', 'date']);
        });

        // Expense categories table
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // ชื่อหมวดหมู่
            $table->string('description')->nullable(); // รายละเอียด
            $table->integer('status')->default(1); // สถานะ
            $table->timestamps();
        });

        // Expense items (line items) table
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->integer('expense_id')->nullable(); // FK to expenses
            $table->integer('category_id')->nullable(); // FK to expense_categories
            $table->string('description')->nullable(); // รายละเอียด
            $table->integer('quantity')->default(1); // จำนวน
            $table->float('unit_price')->nullable(); // ราคาต่อหน่วย
            $table->float('sub_total')->nullable(); // ยอดรวม
            $table->timestamps();
        });

        // Create permissions only if they don't exist
        $permissions = ['expenses view', 'expenses create', 'expenses update', 'expenses allow', 'expenses delete'];
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Run seeder for initial data
        $seeder = new ExpensesDatabaseSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('expenses');
    }
};
