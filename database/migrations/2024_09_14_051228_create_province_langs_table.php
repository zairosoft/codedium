<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('province_langs', function (Blueprint $table) {
            $table->id();
            $table->integer('province_id');
            $table->string('code')->default('th');
            $table->string('name');
            $table->timestamps();
            $table->index(['id', 'province_id']);
        });

        // Insert some stuff
        DB::table('province_langs')->insert(
            array(
                [
                    'province_id' => '1',
                    'code' => 'th',
                    'name' => 'เชียงใหม่'
                ],
                [
                    'province_id' => '2',
                    'code' => 'th',
                    'name' => 'กระบี่'
                ],
                [
                    'province_id' => '3',
                    'code' => 'th',
                    'name' => 'กรุงเทพมหานคร'
                ],
                [
                    'province_id' => '4',
                    'code' => 'th',
                    'name' => 'กาญจนบุรี'
                ],
                [
                    'province_id' => '5',
                    'code' => 'th',
                    'name' => 'กาฬสินธุ์'
                ],
                [
                    'province_id' => '6',
                    'code' => 'th',
                    'name' => 'กำแพงเพชร'
                ],
                [
                    'province_id' => '7',
                    'code' => 'th',
                    'name' => 'ขอนแก่น'
                ],
                [
                    'province_id' => '8',
                    'code' => 'th',
                    'name' => 'จันทบุรี'
                ],
                [
                    'province_id' => '9',
                    'code' => 'th',
                    'name' => 'ฉะเชิงเทรา'
                ],
                [
                    'province_id' => '10',
                    'code' => 'th',
                    'name' => 'ชลบุรี'
                ],
                [
                    'province_id' => '11',
                    'code' => 'th',
                    'name' => 'ชัยนาท'
                ],
                [
                    'province_id' => '12',
                    'code' => 'th',
                    'name' => 'ชัยภูมิ'
                ],
                [
                    'province_id' => '13',
                    'code' => 'th',
                    'name' => 'ชุมพร'
                ],
                [
                    'province_id' => '14',
                    'code' => 'th',
                    'name' => 'เชียงราย'
                ],
                [
                    'province_id' => '15',
                    'code' => 'th',
                    'name' => 'เชียงใหม่'
                ],
                [
                    'province_id' => '16',
                    'code' => 'th',
                    'name' => 'ตรัง'
                ],
                [
                    'province_id' => '17',
                    'code' => 'th',
                    'name' => 'ตราด'
                ],
                [
                    'province_id' => '18',
                    'code' => 'th',
                    'name' => 'ตาก'
                ],
                [
                    'province_id' => '19',
                    'code' => 'th',
                    'name' => 'นครนายก'
                ],
                [
                    'province_id' => '20',
                    'code' => 'th',
                    'name' => 'นครปฐม'
                ],
                [
                    'province_id' => '21',
                    'code' => 'th',
                    'name' => 'นครพนม'
                ],
                [
                    'province_id' => '22',
                    'code' => 'th',
                    'name' => 'นครราชสีมา'
                ],
                [
                    'province_id' => '23',
                    'code' => 'th',
                    'name' => 'นครศรีธรรมราช'
                ],
                [
                    'province_id' => '24',
                    'code' => 'th',
                    'name' => 'นครสวรรค์'
                ],
                [
                    'province_id' => '25',
                    'code' => 'th',
                    'name' => 'นนทบุรี'
                ],
                [
                    'province_id' => '26',
                    'code' => 'th',
                    'name' => 'นราธิวาส'
                ],
                [
                    'province_id' => '27',
                    'code' => 'th',
                    'name' => 'น่าน'
                ],
                [
                    'province_id' => '28',
                    'code' => 'th',
                    'name' => 'บึงกาฬ'
                ],
                [
                    'province_id' => '29',
                    'code' => 'th',
                    'name' => 'บุรีรัมย์'
                ],
                [
                    'province_id' => '30',
                    'code' => 'th',
                    'name' => 'ปทุมธานี'
                ],
                [
                    'province_id' => '31',
                    'code' => 'th',
                    'name' => 'ประจวบคีรีขันธ์'
                ],
                [
                    'province_id' => '32',
                    'code' => 'th',
                    'name' => 'ปราจีนบุรี'
                ],
                [
                    'province_id' => '33',
                    'code' => 'th',
                    'name' => 'ปัตตานี'
                ],
                [
                    'province_id' => '34',
                    'code' => 'th',
                    'name' => 'พระนครศรีอยุธยา'
                ],
                [
                    'province_id' => '35',
                    'code' => 'th',
                    'name' => 'พะเยา'
                ],
                [
                    'province_id' => '36',
                    'code' => 'th',
                    'name' => 'พังงา'
                ],
                [
                    'province_id' => '37',
                    'code' => 'th',
                    'name' => 'พัทลุง'
                ],
                [
                    'province_id' => '38',
                    'code' => 'th',
                    'name' => 'พิจิตร'
                ],
                [
                    'province_id' => '39',
                    'code' => 'th',
                    'name' => 'พิษณุโลก'
                ],
                [
                    'province_id' => '40',
                    'code' => 'th',
                    'name' => 'เพชรบุรี'
                ],
                [
                    'province_id' => '41',
                    'code' => 'th',
                    'name' => 'เพชรบูรณ์'
                ],
                [
                    'province_id' => '42',
                    'code' => 'th',
                    'name' => 'แพร่'
                ],
                [
                    'province_id' => '43',
                    'code' => 'th',
                    'name' => 'ภูเก็ต'
                ],
                [
                    'province_id' => '44',
                    'code' => 'th',
                    'name' => 'มหาสารคาม'
                ],
                [
                    'province_id' => '45',
                    'code' => 'th',
                    'name' => 'มุกดาหาร'
                ],
                [
                    'province_id' => '46',
                    'code' => 'th',
                    'name' => 'แม่ฮ่องสอน'
                ],
                [
                    'province_id' => '47',
                    'code' => 'th',
                    'name' => 'ยโสธร'
                ],
                [
                    'province_id' => '48',
                    'code' => 'th',
                    'name' => 'ยะลา'
                ],
                [
                    'province_id' => '49',
                    'code' => 'th',
                    'name' => 'ร้อยเอ็ด'
                ],
                [
                    'province_id' => '50',
                    'code' => 'th',
                    'name' => 'ระนอง'
                ],
                [
                    'province_id' => '51',
                    'code' => 'th',
                    'name' => 'ระยอง'
                ],
                [
                    'province_id' => '52',
                    'code' => 'th',
                    'name' => 'ราชบุรี'
                ],
                [
                    'province_id' => '53',
                    'code' => 'th',
                    'name' => 'ลพบุรี'
                ],
                [
                    'province_id' => '54',
                    'code' => 'th',
                    'name' => 'ลำปาง'
                ],
                [
                    'province_id' => '55',
                    'code' => 'th',
                    'name' => 'ลำพูน'
                ],
                [
                    'province_id' => '56',
                    'code' => 'th',
                    'name' => 'เลย'
                ],
                [
                    'province_id' => '57',
                    'code' => 'th',
                    'name' => 'ศรีสะเกษ'
                ],
                [
                    'province_id' => '58',
                    'code' => 'th',
                    'name' => 'สกลนคร'
                ],
                [
                    'province_id' => '59',
                    'code' => 'th',
                    'name' => 'สงขลา'
                ],
                [
                    'province_id' => '60',
                    'code' => 'th',
                    'name' => 'สตูล'
                ],
                [
                    'province_id' => '61',
                    'code' => 'th',
                    'name' => 'สมุทรปราการ'
                ],
                [
                    'province_id' => '62',
                    'code' => 'th',
                    'name' => 'สมุทรสงคราม'
                ],
                [
                    'province_id' => '63',
                    'code' => 'th',
                    'name' => 'สมุทรสาคร'
                ],
                [
                    'province_id' => '64',
                    'code' => 'th',
                    'name' => 'สระแก้ว'
                ],
                [
                    'province_id' => '65',
                    'code' => 'th',
                    'name' => 'สระบุรี'
                ],
                [
                    'province_id' => '66',
                    'code' => 'th',
                    'name' => 'สิงห์บุรี'
                ],
                [
                    'province_id' => '67',
                    'code' => 'th',
                    'name' => 'สุโขทัย'
                ],
                [
                    'province_id' => '68',
                    'code' => 'th',
                    'name' => 'สุพรรณบุรี'
                ],
                [
                    'province_id' => '69',
                    'code' => 'th',
                    'name' => 'สุราษฎร์ธานี'
                ],
                [
                    'province_id' => '70',
                    'code' => 'th',
                    'name' => 'สุรินทร์'
                ],
                [
                    'province_id' => '71',
                    'code' => 'th',
                    'name' => 'หนองคาย'
                ],
                [
                    'province_id' => '72',
                    'code' => 'th',
                    'name' => 'หนองบัวลำภู'
                ],
                [
                    'province_id' => '73',
                    'code' => 'th',
                    'name' => 'อ่างทอง'
                ],
                [
                    'province_id' => '74',
                    'code' => 'th',
                    'name' => 'อำนาจเจริญ'
                ],
                [
                    'province_id' => '75',
                    'code' => 'th',
                    'name' => 'อุดรธานี'
                ],
                [
                    'province_id' => '76',
                    'code' => 'th',
                    'name' => 'อุตรดิตถ์'
                ],
                [
                    'province_id' => '77',
                    'code' => 'th',
                    'name' => 'อุทัยธานี'
                ],
                [
                    'province_id' => '78',
                    'code' => 'th',
                    'name' => 'อุบลราชธานี'
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province_langs');
    }
};
