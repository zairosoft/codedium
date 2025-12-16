<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุโมทนาบัตร - {{ $intentform->volume }}/{{ $intentform->number }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header .company-name {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .receipt-info .left,
        .receipt-info .right {
            width: 48%;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-row .label {
            font-weight: bold;
            min-width: 120px;
        }

        .info-row .value {
            flex: 1;
            border-bottom: 1px dotted #999;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding: 5px 10px;
            background: #f5f5f5;
            border-left: 4px solid #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        table td.text-center {
            text-align: center;
        }

        table td.text-right {
            text-align: right;
        }

        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 40%;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .print-button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">พิมพ์</button>

    <div class="container">
        <div class="header">
            <h1>ใบอนุโมทนาบัตร</h1>
            <div class="company-name">{{ $company->name ?? 'มูลนิธิ' }}</div>
            @if($company->address)
                <div style="font-size: 12px; color: #666;">{{ $company->address }}</div>
            @endif
        </div>

        <div class="receipt-info">
            <div class="left">
                <div class="info-row">
                    <div class="label">เล่มที่ / เลขที่:</div>
                    <div class="value">{{ $intentform->volume }} / {{ $intentform->number }}</div>
                </div>
                <div class="info-row">
                    <div class="label">วันที่:</div>
                    <div class="value">{{ \Carbon\Carbon::parse($intentform->date)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="right">
                <div class="info-row">
                    <div class="label">สถานะ:</div>
                    <div class="value">{{ $intentform->status == 1 ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">ช่องทางการชำระ:</div>
                    <div class="value">{{ $intentform->payment_methods }}</div>
                </div>
            </div>
        </div>

        <div class="section-title">ข้อมูลผู้บริจาค</div>
        
        <div class="receipt-info">
            <div class="left">
                <div class="info-row">
                    <div class="label">บัตรนี้แสดงว่า:</div>
                    <div class="value">{{ $intentform->name }}</div>
                </div>
                <div class="info-row">
                    <div class="label">ชื่อบัญชี:</div>
                    <div class="value">{{ $intentform->account_name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">เลขบัญชี:</div>
                    <div class="value">{{ $intentform->account_number ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">ธนาคาร:</div>
                    <div class="value">{{ $intentform->account_bank ?? '-' }}</div>
                </div>
            </div>
            <div class="right">
                <div class="info-row">
                    <div class="label">ผู้รับเงิน:</div>
                    <div class="value">{{ $intentform->payee ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">อ้างอิง:</div>
                    <div class="value">{{ $intentform->refer ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">มูลนิธิ:</div>
                    <div class="value">{{ $intentform->foundation ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="section-title">รายการบริจาค</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">ลำดับ</th>
                    <th>รายการ</th>
                    <th style="width: 100px; text-align: center;">จำนวน</th>
                    <th style="width: 120px; text-align: right;">ราคา</th>
                    <th style="width: 120px; text-align: right;">รวม</th>
                </tr>
            </thead>
            <tbody>
                @forelse($intentform->donations as $index => $donation)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div>{{ $donation->type->name ?? '-' }}</div>
                            @if($donation->description)
                                <div style="font-size: 12px; color: #666; margin-top: 3px;">{{ $donation->description }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($donation->quantity) }}</td>
                        <td class="text-right">{{ number_format($donation->type->price ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($donation->sub_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีรายการบริจาค</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right">รวมทั้งสิ้น:</td>
                    <td class="text-right">{{ number_format($intentform->total, 2) }} บาท</td>
                </tr>
            </tfoot>
        </table>

        @if($intentform->notes)
            <div class="notes-section">
                <div class="notes-title">หมายเหตุ:</div>
                <div>{{ $intentform->notes }}</div>
            </div>
        @endif

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">
                    ผู้บริจาค
                </div>
                <div style="margin-top: 5px; font-size: 12px;">
                    วันที่ ......./......./.......
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    ผู้รับเงิน
                </div>
                <div style="margin-top: 5px; font-size: 12px;">
                    วันที่ ......./......./.......
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
