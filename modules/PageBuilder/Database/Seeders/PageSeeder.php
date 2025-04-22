<?php

namespace Modules\PageBuilder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PageBuilder\App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample page
        Page::create([
            'title' => 'หน้าแรก',
            'slug' => 'home',
            'html_content' => '
<div class="container my-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>ยินดีต้อนรับสู่เว็บไซต์ของเรา</h1>
            <p class="lead">สร้างด้วย vvvebJs Page Builder</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">บริการของเรา</h5>
                    <p class="card-text">เราให้บริการด้านการพัฒนาเว็บไซต์ที่ทันสมัยและตอบโจทย์ธุรกิจของคุณ</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ผลงานของเรา</h5>
                    <p class="card-text">ดูผลงานที่ผ่านมาของเราได้ที่นี่ เรามีประสบการณ์ในการทำงานมาอย่างยาวนาน</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ติดต่อเรา</h5>
                    <p class="card-text">สนใจบริการของเรา สามารถติดต่อเราได้ผ่านช่องทางต่างๆ</p>
                </div>
            </div>
        </div>
    </div>
</div>',
            'css_content' => '
body {
    font-family: "Kanit", sans-serif;
}
.card {
    transition: all 0.3s;
    margin-bottom: 20px;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}',
            'js_content' => '',
            'is_published' => true,
            'meta_description' => 'หน้าแรกของเว็บไซต์ สร้างด้วย vvvebJs Page Builder',
            'meta_keywords' => 'vvvebjs, page builder, laravel, bitgrid',
        ]);
    }
}
