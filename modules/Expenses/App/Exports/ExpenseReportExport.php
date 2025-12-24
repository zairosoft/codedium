<?php

namespace Modules\Expenses\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ExpenseReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping, WithTitle, WithEvents
{
    protected $expenses;
    protected $rowNumber = 0;
    protected $month;
    protected $year;

    public function __construct($expenses, $month = null, $year = null)
    {
        $this->expenses = $expenses;
        $this->month = $month ?? Carbon::now()->month;
        $this->year = $year ?? Carbon::now()->year;
    }

    public function title(): string
    {
        return 'รายงาน';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->expenses;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ลำดับ',
            'วันที่',
            'เลขที่อ้างอิง',
            'หมวดหมู่',
            'ผู้รับเงิน',
            'ช่องทางการชำระ',
            'รายละเอียด',
            'จำนวนเงิน',
            'หมายเหตุ',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($expense): array
    {
        $this->rowNumber++;

        $itemDetails = '';
        if ($expense->items && $expense->items->count() > 0) {
            $details = [];
            foreach ($expense->items as $item) {
                $categoryName = $item->category ? $item->category->name : '-';
                $details[] = $categoryName . ' (' . $item->quantity . ' x ' . number_format($item->unit_price, 2) . ')';
            }
            $itemDetails = implode(', ', $details);
        }

        return [
            $this->rowNumber,
            Carbon::parse($expense->date)->format('d/m/Y'),
            $expense->reference_number,
            $expense->category ?? '-',
            $expense->payee,
            $expense->payment_method,
            $itemDetails,
            $expense->total,
            $expense->notes ?? '',
        ];
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // ลำดับ
            'B' => 12, // วันที่
            'C' => 15, // เลขที่อ้างอิง
            'D' => 15, // หมวดหมู่
            'E' => 20, // ผู้รับเงิน
            'F' => 15, // ช่องทางการชำระ
            'G' => 30, // รายละเอียด
            'H' => 15, // จำนวนเงิน
            'I' => 25, // หมายเหตุ
        ];
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->expenses->count() + 2; // +1 for header, +1 for 0-index

        // Header style
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Data rows style
        $sheet->getStyle('A2:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center alignment for specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Right alignment for amount column
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Register events
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->expenses->count() + 2;

                // Insert title row at top
                $sheet->insertNewRowBefore(1, 1);

                $thaiMonths = [
                    1 => 'มกราคม',
                    2 => 'กุมภาพันธ์',
                    3 => 'มีนาคม',
                    4 => 'เมษายน',
                    5 => 'พฤษภาคม',
                    6 => 'มิถุนายน',
                    7 => 'กรกฎาคม',
                    8 => 'สิงหาคม',
                    9 => 'กันยายน',
                    10 => 'ตุลาคม',
                    11 => 'พฤศจิกายน',
                    12 => 'ธันวาคม'
                ];

                $thaiYear = $this->year + 543;
                $title = 'รายงานค่าใช้จ่ายประจำเดือน ' . $thaiMonths[$this->month] . ' พ.ศ. ' . $thaiYear;

                $sheet->setCellValue('A1', $title);
                $sheet->mergeCells('A1:I1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Add total row
                $totalRow = $lastRow + 1;
                $sheet->setCellValue('G' . $totalRow, 'รวมทั้งหมด');

                // Calculate grand total
                $grandTotal = $this->expenses->sum('total');
                $sheet->setCellValue('H' . $totalRow, $grandTotal);

                // Style total row
                $sheet->getStyle('A' . $totalRow . ':I' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);
            },
        ];
    }
}
