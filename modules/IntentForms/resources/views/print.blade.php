<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุโมทนาบัตร - {{ $intentform->volume }}/{{ $intentform->number }}</title>
    <style>
        @page {
            size: 19.3cm 14.3cm;
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
            width: 19.3cm;
            height: 14.3cm;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .container {
            width: 19.3cm;
            height: 14.3cm;
            display: flex;
            flex-direction: column;
            background-image: url('https://zairosoft.dev/codedium/88_page-0001.jpg');
            background-size: 19.3cm 14.3cm;
            background-position: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">พิมพ์</button>

    <div class="container">

    </div>
</body>

</html>