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
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            padding: 0.5cm 0.8cm;
            position: relative;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">พิมพ์</button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>ใบอนุโมทนาบัตร</h1>
            <div class="company-name">{{ $company->name ?? 'มูลนิธิ' }}</div>
            @if($company->address)
                <div class="address">{{ $company->address }}</div>
            @endif
        </div>

        <!-- Document Info -->
        <div class="doc-info">
            <div class="left">
                <strong>เล่มที่/เลขที่:</strong> {{ $intentform->volume }}/{{ $intentform->number }}
            </div>
            <div class="right">
                <strong>วันที่:</strong> {{ \Carbon\Carbon::parse($intentform->date)->format('d/m/Y') }}
            </div>
        </div>

        <!-- Donor Information -->
        <div class="two-columns">
            <div class="column">
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">บัตรนี้แสดงว่า:</span>
                        <span class="value">{{ $intentform->name }}</span>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">ชื่อบัญชี:</span>
                        <span class="value">{{ $intentform->account_name ?? '-' }}</span>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">เลขบัญชี:</span>
                        <span class="value">{{ $intentform->account_number ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">ผู้รับเงิน:</span>
                        <span class="value">{{ $intentform->payee ?? '-' }}</span>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">ธนาคาร:</span>
                        <span class="value">{{ $intentform->account_bank ?? '-' }}</span>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-inline">
                        <span class="label">การชำระ:</span>
                        <span class="value">{{ $intentform->payment_methods }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ลำดับ</th>
                    <th>รายการ</th>
                    <th style="width: 50px;">จำนวน</th>
                    <th style="width: 70px;">ราคา</th>
                    <th style="width: 80px;">รวม</th>
                </tr>
            </thead>
            <tbody>
                @forelse($intentform->donations as $index => $donation)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $donation->type->name ?? '-' }}
                            @if($donation->description)
                                <span style="font-size: 9px; color: #666;">({{ $donation->description }})</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($donation->quantity) }}</td>
                        <td class="text-right">{{ number_format($donation->type->price ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($donation->sub_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีรายการ</td>
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
            <div class="notes">
                <strong style="font-size: 10px;">หมายเหตุ:</strong> {{ $intentform->notes }}
            </div>
        @endif

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">
                    ผู้บริจาค
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    ผู้รับเงิน
                </div>
            </div>
        </div>
    </div>
</body>

</html>