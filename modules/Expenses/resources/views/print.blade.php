<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกค่าใช้จ่าย - {{ $expense->reference_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: white;
            }

            .page {
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
                padding: 15mm 15mm !important;
                /* Reduced padding */
                margin: 0 !important;
            }
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            /* Reduced font size */
            line-height: 1.3;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px solid #D3D3D3;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Header Section */
        .doc-title {
            color: #1a73e8;
            font-size: 22px;
            /* Reduced */
            font-weight: 700;
            margin-bottom: 2px;
        }

        .doc-subtitle {
            font-size: 13px;
            /* Reduced */
            color: #1a73e8;
            font-weight: 500;
            margin-bottom: 15px;
            /* Reduced */
        }

        .header-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            /* Reduced */
        }

        .company-name {
            font-size: 15px;
            /* Reduced */
            font-weight: 700;
            margin-bottom: 3px;
        }

        .company-address {
            font-size: 11px;
            /* Reduced */
            color: #555;
            line-height: 1.3;
        }

        .doc-meta {
            width: 35%;
            text-align: right;
        }

        .meta-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 3px;
            /* Reduced */
        }

        .meta-label {
            color: #1a73e8;
            font-weight: 600;
            margin-right: 10px;
        }

        .meta-value {
            font-weight: 600;
            min-width: 100px;
            text-align: right;
        }

        /* Vendor Section */
        .vendor-section {
            margin-bottom: 20px;
        }

        .vendor-label {
            color: #1a73e8;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .vendor-box {
            /* border: 1px solid #e0e0e0; */
            /* padding: 10px; */
            border-radius: 4px;
        }

        .vendor-name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 2px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #1a73e8;
            color: white;
            padding: 8px 10px;
            font-weight: 500;
            text-align: center;
            font-size: 12px;
        }

        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .items-table tr.last-item td {
            border-bottom: 2px solid #1a73e8;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        /* Footer / Totals */
        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .baht-text-area {
            width: 50%;
            padding-top: 10px;
        }

        .baht-text {
            font-style: italic;
            color: #555;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
        }

        .totals-area {
            width: 40%;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-right: 10px;
        }

        .total-label {
            color: #1a73e8;
            font-weight: 600;
        }

        .total-value {
            font-weight: 600;
            text-align: right;
        }

        .grand-total {
            margin-top: 10px;
            /* border-top: 2px solid #1a73e8; */
            /* border-bottom: 2px solid #1a73e8; */
            padding: 8px 0;
            font-size: 15px;
            color: #1a73e8;
        }

        /* Payment Details */
        .payment-section {
            margin-top: 30px;
            border-top: 1px solid #1a73e8;
            padding-top: 15px;
        }

        .section-header {
            color: #1a73e8;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .payment-options {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox-box {
            width: 14px;
            height: 14px;
            border: 1px solid #333;
            display: inline-block;
            position: relative;
        }

        .checkbox-box.checked::after {
            content: "✓";
            position: absolute;
            top: -4px;
            left: 1px;
            font-size: 14px;
            font-weight: bold;
        }

        .payment-info-line {
            display: flex;
            gap: 30px;
            margin-top: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-group {
            display: flex;
            gap: 10px;
        }

        .info-ul {
            text-decoration: underline;
            text-decoration-style: dotted;
        }

        /* Signatures */
        .signatures {
            margin-top: 30px;
            /* Reduced from 50px */
            display: flex;
            justify-content: space-between;
        }

        .sig-box {
            width: 30%;
            text-align: center;
        }

        .sig-line {
            border-bottom: 1px solid #aaa;
            margin: 25px 10px 5px;
            /* Reduced margin */
            height: 1px;
        }

        .sig-label {
            font-size: 11px;
            color: #666;
        }

        /* Print Button */
        .print-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1a73e8;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.4);
            border: none;
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.2s;
        }

        .print-fab:hover {
            transform: scale(1.1);
        }

        .print-fab svg {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="print-fab no-print" title="พิมพ์เอกสาร">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
    </button>

    @php
        // Baht Text Helper (Same as before)
        if (!function_exists('baht_text')) {
            function baht_text($number, $include_unit = true, $display_zero = true)
            {
                // ... (keeping the logic from previous implemented block)
                if (!is_numeric($number))
                    return null;
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
                    $text .= $satang == 0 ? $BAHT_TEXT_INTEGER : baht_text($satang, false) . $BAHT_TEXT_SATANG;
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

    <div class="page">
        <!-- Title & Header -->
        <div class="doc-title">บันทึกค่าใช้จ่าย</div>
        <div class="doc-subtitle">Expense Note</div>

        <div class="header-grid">
            <div class="company-details">
                <div class="company-name">{{ $company->name ?? 'บริษัท ตัวอย่าง จำกัด' }}</div>
                <div class="company-address">
                    {{ $company->address }}<br>
                    @if($company->tax_id) เลขประจำตัวผู้เสียภาษี: {{ $company->tax_id }} @endif
                    @if($company->tel_no) โทร: {{ $company->tel_no }} @endif
                </div>
            </div>
            <div class="doc-meta">
                <div class="meta-row">
                    <span class="meta-label">เลขที่/Document No.:</span>
                    <span class="meta-value">{{ $expense->reference_number }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">วันที่/Date:</span>
                    <span class="meta-value">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</span>
                </div>
                @if($expense->reference_number)
                    <div class="meta-row">
                        <span class="meta-label">อ้างอิง/Reference:</span>
                        <span class="meta-value">{{ $expense->reference_number }}</span>
                    </div>
                @endif
                <div class="meta-row">
                    <span class="meta-label">ผู้จัดทำ/Preparer:</span>
                    <span class="meta-value">{{ $expense->creator->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Vendor Section -->
        <div class="vendor-section">
            <div class="vendor-label">ผู้จำหน่าย/Vendor:</div>
            <div class="vendor-box">
                <div class="vendor-name">{{ $expense->payee }}</div>
                <div class="company-address">
                    <!-- Placeholder if vendor has address field, assuming description might contain it or just name -->
                    {{ $expense->description }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ลำดับ<br>No.</th>
                    <th class="text-left">รายละเอียด<br>Description</th>
                    <th style="width: 150px;">หมวดหมู่<br>Category</th>
                    <th style="width: 80px;">จำนวน<br>Qty</th>
                    <th class="text-right" style="width: 100px;">ราคา/หน่วย<br>Unit Price</th>
                    <th class="text-right" style="width: 120px;">ยอดรวม<br>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expense->items as $index => $item)
                    <tr class="{{ $loop->last ? 'last-item' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->description ?? '-' }}</td>
                        <td class="text-center">{{ $item->category->name ?? '-' }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->sub_total, 2) }}</td>
                    </tr>
                @empty
                    <tr class="last-item">
                        <td colspan="6" class="text-center" style="padding: 20px;">ไม่มีรายการ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary & Totals -->
        <div class="footer-grid">
            <div class="baht-text-area">
                <div class="baht-text">
                    ({{ baht_text($expense->grand_total) }})
                </div>
                @if($expense->notes)
                    <div style="margin-top: 15px; font-size: 11px; color: #666;">
                        <strong>หมายเหตุ:</strong> {{ $expense->notes }}
                    </div>
                @endif
            </div>
            <div class="totals-area">
                <div class="total-row">
                    <span class="total-label">รวมเป็นเงิน:</span>
                    <span class="total-value">{{ number_format($expense->total, 2) }} บาท</span>
                </div>
                @if($expense->discount_amount > 0)
                <div class="total-row">
                    <span class="total-label">ส่วนลด {{ number_format($expense->discount_percentage) }}%:</span>
                    <span class="total-value">{{ number_format($expense->discount_amount, 2) }} บาท</span>
                </div>
                <div class="total-row">
                    <span class="total-label">หลังหักส่วนลด:</span>
                    <span class="total-value">{{ number_format($expense->total - $expense->discount_amount, 2) }} บาท</span>
                </div>
                @endif
                
                @if(!$expense->vat_exempt)
                <div class="total-row">
                    <span class="total-label">ภาษีมูลค่าเพิ่ม {{ number_format($expense->vat_percentage) }}%:</span>
                    <span class="total-value">{{ number_format($expense->vat_amount, 2) }} บาท</span>
                </div>
                @else
                <div class="total-row">
                    <span class="total-label">ภาษีมูลค่าเพิ่ม (ยกเว้น):</span>
                    <span class="total-value">0.00 บาท</span>
                </div>
                @endif

                @if($expense->withholding_tax_amount > 0)
                <div class="total-row">
                    <span class="total-label">หัก ณ ที่จ่าย {{ number_format($expense->withholding_tax_percentage) }}%:</span>
                    <span class="total-value">{{ number_format($expense->withholding_tax_amount, 2) }} บาท</span>
                </div>
                @endif

                <div class="total-row grand-total">
                    <span class="total-label">จำนวนเงินรวมทั้งสิ้น:</span>
                    <span class="total-value">{{ number_format($expense->grand_total, 2) }} บาท</span>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
            <div class="section-header">รายละเอียดการชำระเงิน/Payment details</div>
            <div class="payment-options">
                <div class="payment-option">
                    <div class="checkbox-box {{ $expense->payment_method == 'เงินสด' ? 'checked' : '' }}"></div>
                    เงินสด/Cash
                </div>
                <div class="payment-option">
                    <div class="checkbox-box {{ $expense->payment_method == 'เช็ค' ? 'checked' : '' }}"></div>
                    เช็ค/Cheque
                </div>
                <div class="payment-option">
                    <div class="checkbox-box {{ $expense->payment_method == 'เงินโอน' ? 'checked' : '' }}"></div>
                    โอนเงิน/Transfer
                </div>
                <div class="payment-option">
                    <div class="checkbox-box {{ $expense->payment_method == 'บัตรเครดิต' ? 'checked' : '' }}"></div>
                    บัตรเครดิต/Credit card
                </div>
            </div>
            <div class="payment-info-line">
                <div class="info-group">
                    <span>ชื่อบัญชี/Account Name:</span>
                    <span style="min-width: 150px; border-bottom: 1px dotted #ccc;">
                        @if($expense->account_name)
                            {{ $expense->account_name }}
                        @elseif($expense->payment_method == 'เงินสด')
                            -
                        @else
                            ................................................
                        @endif
                    </span>
                </div>
                <div class="info-group">
                    <span>เลขที่/No.:</span>
                    <span style="min-width: 100px; border-bottom: 1px dotted #ccc;">
                        @if($expense->account_number)
                            {{ $expense->account_number }}
                        @elseif($expense->payment_method == 'เงินสด')
                            -
                        @else
                            ................................
                        @endif
                    </span>
                </div>
                <div class="info-group">
                    <span>วันที่/Date:</span>
                    <span
                        style="min-width: 100px; border-bottom: 1px dotted #ccc;">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="payment-info-line" style="border:none; margin-top: 5px;">
                <div class="info-group" style="width: 100%; justify-content: space-between;">
                    <div>
                        <span>ยอดชำระ/Amount Paid:</span>
                        <strong>{{ number_format($expense->grand_total, 2) }}</strong>
                    </div>
                    <div>
                        <span>หัก ณ ที่จ่าย/WHT:</span>
                        <strong>{{ number_format($expense->withholding_tax_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-box">
                <div class="sig-label">ผู้รับเงิน/Received by</div>
                <div class="sig-line"></div>
                <div class="sig-label">วันที่/Date: _____/_____/_____</div>
            </div>
            <div class="sig-box">
                <div class="sig-label">ผู้จัดทำ/Prepared by</div>
                <div class="sig-line"></div>
                <div style="margin-top: -20px; margin-bottom: 5px;">{{ $expense->creator->name ?? '-' }}</div>
                <div class="sig-label">วันที่/Date: _____/_____/_____</div>
            </div>
            <div class="sig-box">
                <div class="sig-label">ผู้อนุมัติ/Approved by</div>
                <div class="sig-line"></div>
                <div class="sig-label">วันที่/Date: _____/_____/_____</div>
            </div>
        </div>
    </div>
</body>

</html>