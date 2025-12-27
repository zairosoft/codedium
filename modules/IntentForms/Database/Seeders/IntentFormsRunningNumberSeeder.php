<?php

namespace Modules\IntentForms\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IntentForms\App\Models\RunningNumber;

class IntentFormsRunningNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if exists first to prevent duplicates
        if (RunningNumber::where('type', 'เงินสด')->doesntExist()) {
            RunningNumber::create([
                'type' => 'เงินสด',
                'volume' => 1,
                'number' => 0,
            ]);
        }

        if (RunningNumber::where('type', 'เงินโอน')->doesntExist()) {
            RunningNumber::create([
                'type' => 'เงินโอน',
                'volume' => 1,
                'number' => 0,
            ]);
        }
    }
}
