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
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ผ้าดิบ',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ถุงห่อศพ',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'หลุมศพไร้ญาติ',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'บำรุง',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'โคม',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ข้าวสาร',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'ปีชง',
            'price' => 100.00,
            'created_by' => 1,
        ]);

        Type::create([
            'name' => 'อื่นๆ',
            'price' => 100.00,
            'created_by' => 1,
        ]);

    }
}
