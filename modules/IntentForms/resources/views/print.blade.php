<!DOCTYPE html>
<html lang="th">
@php
    if (!function_exists('baht_text')) {
        function baht_text($number, $include_unit = true, $display_zero = true)
        {
            if (!is_numeric($number)) {
                return null;
            }

            $BAHT_TEXT_NUMBERS = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
            $BAHT_TEXT_UNITS = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
            $BAHT_TEXT_ONE_IN_TENTH = 'เอ็ด';
            $BAHT_TEXT_TWENTY = 'ยี่';
            $BAHT_TEXT_INTEGER = 'ถ้วน';
            $BAHT_TEXT_BAHT = 'บาท';
            $BAHT_TEXT_SATANG = 'สตางค์';
            $BAHT_TEXT_POINT = 'จุด';

            $log = floor(log($number, 10));
            if ($log > 5) {
                $millions = floor($log / 6);
                $million_value = pow(1000000, $millions);
                $normalised_million = floor($number / $million_value);
                $rest = $number - ($normalised_million * $million_value);
                $millions_text = '';
                for ($i = 0; $i < $millions; $i++) {
                    $millions_text .= $BAHT_TEXT_UNITS[6];
                }
                return baht_text($normalised_million, false) . $millions_text . baht_text($rest, true, false);
            }

            $number_str = (string) floor($number);
            $text = '';
            $unit = 0;

            if ($display_zero && $number_str == '0') {
                $text = $BAHT_TEXT_NUMBERS[0];
            } else
                for ($i = strlen($number_str) - 1; $i > -1; $i--) {
                    $current_number = (int) $number_str[$i];

                    $unit_text = '';
                    if ($unit == 0 && $i > 0) {
                        $previous_number = isset($number_str[$i - 1]) ? (int) $number_str[$i - 1] : 0;
                        if ($current_number == 1 && $previous_number > 0) {
                            $unit_text .= $BAHT_TEXT_ONE_IN_TENTH;
                        } else if ($current_number > 0) {
                            $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
                        }
                    } else if ($unit == 1 && $current_number == 2) {
                        $unit_text .= $BAHT_TEXT_TWENTY;
                    } else if ($current_number > 0 && ($unit != 1 || $current_number != 1)) {
                        $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
                    }

                    if ($current_number > 0) {
                        $unit_text .= $BAHT_TEXT_UNITS[$unit];
                    }

                    $text = $unit_text . $text;
                    $unit++;
                }

            if ($include_unit) {
                $text .= $BAHT_TEXT_BAHT;

                $satang = explode('.', number_format($number, 2, '.', ''))[1];
                $text .= $satang == 0
                    ? $BAHT_TEXT_INTEGER
                    : baht_text($satang, false) . $BAHT_TEXT_SATANG;
            } else {
                $exploded = explode('.', $number);
                if (isset($exploded[1])) {
                    $text .= $BAHT_TEXT_POINT;
                    $decimal = (string) $exploded[1];
                    for ($i = 0; $i < strlen($decimal); $i++) {
                        $text .= $BAHT_TEXT_NUMBERS[$decimal[$i]];
                    }
                }
            }

            return $text;
        }
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุโมทนาบัตร - {{ $intentform->volume }}/{{ $intentform->number }}</title>
    <style>
        @page {
            size: 20.3cm 15.3cm;
            margin: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', 'TH Sarabun New', sans-serif;
            width: 20.3cm;
            height: 15.3cm;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .container {
            width: 20.3cm;
            height: 15.3cm;
            display: flex;
            flex-direction: column;
            /* background-image: url('https://zairosoft.dev/codedium/test2.png'); */
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-button {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            cursor: pointer;
            font-family: 'Sarabun', sans-serif;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            -webkit-font-smoothing: antialiased;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
        }

        .print-button:active {
            transform: translateY(0);
        }

        .print-button svg {
            width: 20px;
            height: 20px;
        }

        /* Controls Container */
        .controls {
            position: fixed;
            top: 30px;
            right: 30px;
            display: flex;
            align-items: center;
            gap: 16px;
            z-index: 1000;
        }

        /* Update button position to be relative to controls */
        .print-button {
            position: static;
            /* Reset fixed position as parent is fixed */
        }



        .toggle-switch input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #4f46e5;
        }

        /* No Background Class */
        .container.no-bg {
            background-image: none !important;
        }
    </style>
</head>

<body>
    <div class="controls no-print">
        <!-- <label class="toggle-switch">
            <input type="checkbox" id="bgToggle" checked onchange="toggleBackground()">
            <span>แสดงรูปพื้นหลัง</span>
        </label> -->

        <button onclick="window.print()" class="print-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>พิมพ์ใบอนุโมทนาบัตร</span>
        </button>
    </div>

    <script>
        function toggleBackground() {
            const container = document.querySelector('.container');
            const checkbox = document.getElementById('bgToggle');

            if (checkbox.checked) {
                container.classList.remove('no-bg');
            } else {
                container.classList.add('no-bg');
            }
        }
    </script>








    <div class="container">








        <div class="volume"
            style="margin-top: 26mm;margin-left: 40mm;position: absolute;font-size: 20px;font-weight: bold;">
            {{ $intentform->volume }}
        </div>
        <div class="number"
            style="margin-top: 26mm;margin-left: 175mm;position: absolute;font-size: 20px;font-weight: bold;">
            {{ $intentform->number }}
        </div>


        <div class="account_name" style="margin-top: 35mm;margin-left: 40mm;position: absolute;">
            @if ($intentform->payment_methods == 'เงินโอน')
                {{ $intentform->account_name }}
            @else
                -
            @endif
        </div>
        <div class="account_number" style="margin-top: 35mm;margin-left: 132mm;position: absolute;">
            @if ($intentform->payment_methods == 'เงินโอน')
                {{ $intentform->account_bank }}
            @else
                -
            @endif
        </div>
        <div class="account_number" style="margin-top: 42mm;margin-left: 42mm;position: absolute;">
            @if ($intentform->payment_methods == 'เงินโอน')
                {{ $intentform->account_number }}
            @else
                -
            @endif
        </div>

        <div class="refer" style="margin-top: 42mm;margin-left: 132mm;position: absolute;">
            @if ($intentform->payment_methods == 'เงินโอน')
                {{ $intentform->refer }}
            @else
                -
            @endif
        </div>

        <div class="name" style="margin-top: 86mm; margin-left: 66mm;position: absolute;">
            {{ $intentform->name }}
        </div>


        <div class="foundation" style="margin-top: 107mm;margin-left: 52mm;position: absolute;">
            {{ number_format($intentform->total, 2) }}
        </div>

        <div class="foundation-province" style="margin-top: 107mm;margin-left: 128mm;position: absolute;">
            {{ baht_text($intentform->total) }}
        </div>


        @php
            $date = \Carbon\Carbon::parse($intentform->date)->locale('th');
            $day = $date->format('d');
            $month = $date->monthName;
            $year = $date->year + 543;
        @endphp
        <div class="date-day" style="margin-top: 122mm; margin-left: 116mm; position: absolute; font-size: 13px;">
            {{ $day }}
        </div>
        <div class="date-month" style="margin-top: 122mm; margin-left: 135mm;position: absolute; font-size: 13px;">
            {{ $month }}
        </div>
        <div class="date-year" style="margin-top: 122mm; margin-left: 165mm;position: absolute; font-size: 13px;">
            {{ $year }}
        </div>
        <div class="payee" style="margin-top: 130mm;margin-left: 136mm;position: absolute;">
            {{ $intentform->payee }}
        </div>







        @foreach($intentform->donations as $index => $donation)

            @if($donation->type->id == 1)
                <div style="margin-top: 93mm;margin-left: 66mm;position: absolute;">
                    ✓
                </div>
            @endif

            @if($donation->type->id == 2)
                <div style="margin-top: 93mm;margin-left: 90mm;position: absolute;">
                    ✓
                </div>
            @endif

            @if($donation->type->id == 3)
                <div style="margin-top: 93mm;margin-left: 112mm;position: absolute;">
                    ✓
                </div>
            @endif

            @if($donation->type->id == 4)
                <div style="margin-top: 93mm;margin-left: 140mm;position: absolute;">
                    ✓
                </div>
            @endif
            @if($donation->type->id == 5)
                <div style="margin-top: 93mm; margin-left: 179mm; position: absolute;">
                    ✓
                </div>
            @endif
            @if($donation->type->id == 6)
                <div style="margin-top: 100mm;margin-left: 20mm;position: absolute;">
                    ✓
                </div>
                <div style="margin-top: 100mm;margin-left: 40mm;position: absolute;">
                    {{ $donation->description }}
                </div>
            @endif

            @if($donation->type->id == 7)
                <div style="margin-top: 100mm;margin-left: 61mm;position: absolute;">
                    ✓
                </div>
            @endif
            @if($donation->type->id == 8)
                <div style="margin-top: 100mm;margin-left: 90mm;position: absolute;">
                    ✓
                </div>
                <div style="margin-top: 100mm;margin-left: 106mm;position: absolute;">
                    {{ $donation->description }}
                </div>
            @endif
            @if($donation->type->id == 9)
                <div style="margin-top: 100mm;margin-left: 141mm;position: absolute;">
                    ✓
                </div>
                <div style="margin-top: 100mm;margin-left: 156mm;position: absolute;">
                    {{ $donation->description }}
                </div>
            @endif

        @endforeach




    </div>
</body>

</html>