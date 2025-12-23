<?php

namespace Modules\Expenses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Expenses\App\Models\ExpenseCategory;

class ExpensesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some default expense categories
        $categories = [
            ['name' => 'เงินเดือน', 'description' => 'ค่าจ้างพนักงานและเงินเดือน', 'status' => 1],
            ['name' => 'ค่าเช่า', 'description' => 'ค่าเช่าสถานที่และอุปกรณ์', 'status' => 1],
            ['name' => 'ค่าไฟฟ้า', 'description' => 'ค่าสาธารณูปโภค - ไฟฟ้า', 'status' => 1],
            ['name' => 'ค่าน้ำ', 'description' => 'ค่าสาธารณูปโภค - น้ำประปา', 'status' => 1],
            ['name' => 'ค่าอินเทอร์เน็ต', 'description' => 'ค่าสื่อสาร - อินเทอร์เน็ต', 'status' => 1],
            ['name' => 'ค่าโทรศัพท์', 'description' => 'ค่าสื่อสาร - โทรศัพท์', 'status' => 1],
            ['name' => 'ค่าวัสดุสำนักงาน', 'description' => 'วัสดุสิ้นเปลืองในสำนักงาน', 'status' => 1],
            ['name' => 'ค่าน้ำมันเชื้อเพลิง', 'description' => 'ค่าน้ำมันรถและยานพาหนะ', 'status' => 1],
            ['name' => 'ค่าซ่อมแซม', 'description' => 'ค่าซ่อมแซมและบำรุงรักษา', 'status' => 1],
            ['name' => 'อื่นๆ', 'description' => 'ค่าใช้จ่ายอื่นๆ', 'status' => 1],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
