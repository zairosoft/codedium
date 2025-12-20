<!DOCTYPE html>
<html lang="th">

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
            background-image: url('https://zairosoft.dev/codedium/88_page-0001.jpg');
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
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 6 2 18 2 18 9"></polyline>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
        <span>พิมพ์ใบอนุโมทนาบัตร</span>
    </button>








    <div class="container">








        <div class="volume" style=" margin-top: 16mm; margin-left: 49mm; position: absolute; ">
            {{ $intentform->volume }}
        </div>
        <div class="number" style=" margin-top: 16mm; margin-left: 158mm; position: absolute; ">
            {{ $intentform->number }}
        </div>


        <div class="account_name" style="margin-top: 27mm; margin-left: 33mm; position: absolute;">
            {{ $intentform->account_name }}
        </div>
        <div class="account_number" style="margin-top: 27mm; margin-left: 151mm; position: absolute;">
            {{ $intentform->account_number }}
        </div>
        <div class="account_number" style="margin-top: 36mm; margin-left: 33mm; position: absolute;">
            {{ $intentform->account_number }}
        </div>

        <div class="refer" style="margin-top: 36mm; margin-left: 137mm; position: absolute;">
            {{ $intentform->refer }}
        </div>




    </div>
</body>

</html>