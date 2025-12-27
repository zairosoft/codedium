<?php

namespace Modules\IntentForms\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\IntentForms\App\Models\Type;

class IntentFormsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Type::create([
            'name' => 'โลงศพ',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ผ้าดิบ',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ถุงห่อศพ',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'หลุมศพไร้ญาติ',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'บำรุง',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'โคมไฟประทีป - เล็ก',
            'status' => 1,
            'price' => 240.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'โคมไฟประทีป - กลาง',
            'status' => 1,
            'price' => 480.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'โคมไฟประทีป - ใหญ่',
            'status' => 1,
            'price' => 1200.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ข้าวสาร',
            'status' => 1,
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ปีชง',
            'status' => 1,
            'price' => 200.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'อื่นๆ',
            'status' => 1,
            'price' => 0.00,
            'created_by' => 1,
        ]);

    }
}