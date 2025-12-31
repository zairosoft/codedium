<?php

namespace Modules\IntentForms\App\Exports;

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
use Modules\IntentForms\App\Models\Intentform;
use Modules\IntentForms\App\Models\Type;
use Carbon\Carbon;

class IntentFormReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping, WithTitle, WithEvents
{
    protected $intentforms;
    protected $rowNumber = 0;
    protected $month;
    protected $year;
    protected $types;

    public function __construct($intentforms, $month = null, $year = null)
    {
        $this->intentforms = $intentforms;
        $this->month = $month ?? Carbon::now()->month;
        $this->year = $year ?? Carbon::now()->year;
        $this->types = Type::where('status', 1)->orderBy('id')->get();
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
        return $this->intentforms;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headers = [
            'ลำดับ',
            'ชื่อ - สกุล',
            'เล่มที่/เลขที่',
        ];

        // Add type names as columns
        foreach ($this->types as $type) {
            $headers[] = $type->name;
        }

        $headers[] = 'ช่องทางการชำระ';
        $headers[] = 'ผู้รับเงิน';
        $headers[] = 'หมายเหตุ';

        return $headers;
    }

    /**
     * Map data for each row
     */
    public function map($intentform): array
    {
        $this->rowNumber++;

        $row = [
            $this->rowNumber,
            $intentform->name,
            sprintf('%03d', $intentform->volume) . '/' . $intentform->number,
        ];

        // Add donation amounts for each type
        foreach ($this->types as $type) {
            $donation = $intentform->donations->where('type_id', $type->id)->first();
            $row[] = $donation ? $donation->sub_total : '';
        }

        $row[] = $intentform->payment_methods;
        $row[] = $intentform->payee;
        $row[] = $intentform->notes ?? '';

        return $row;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        $widths = [
            'A' => 8,  // ลำดับ
            'B' => 25, // ชื่อ-สกุล
            'C' => 12, // เล่มที่/เลขที่
        ];

        // Set width for type columns (D onwards)
        $column = 'D';
        foreach ($this->types as $type) {
            $widths[$column] = 12;
            $column++;
        }

        // Payment Method column
        $widths[$column] = 15;
        $column++;

        // Payee column
        $widths[$column] = 20;
        $column++;

        // Notes column
        $widths[$column] = 20;

        return $widths;
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->intentforms->count() + 2; // +1 for header, +1 for 0-index
        $lastColumn = chr(67 + $this->types->count() + 3); // C + types + payment + payee + notes

        // Header style
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
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
        $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center alignment for specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Right alignment for number columns (type amounts)
        $typeStartCol = 'D';
        $typeEndCol = chr(67 + $this->types->count()); // C + count
        $sheet->getStyle($typeStartCol . '2:' . $typeEndCol . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

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
                $lastRow = $this->intentforms->count() + 2;
                $lastColumn = chr(67 + $this->types->count() + 3);

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
                $title = 'รายงานรายรับประจำเดือน ' . $thaiMonths[$this->month] . ' พ.ศ. ' . $thaiYear;

                $sheet->setCellValue('A1', $title);
                $sheet->mergeCells('A1:' . $lastColumn . '1');
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
                $sheet->setCellValue('B' . $totalRow, 'รวม');

                // Calculate totals for each type column
                $typeCol = 'D';
                $grandTotal = 0;
                foreach ($this->types as $type) {
                    $sum = $this->intentforms->sum(function ($intentform) use ($type) {
                        $donation = $intentform->donations->where('type_id', $type->id)->first();
                        return $donation ? $donation->sub_total : 0;
                    });
                    $sheet->setCellValue($typeCol . $totalRow, $sum);
                    $grandTotal += $sum;
                    $typeCol++;
                }

                // Grand total in notes column
                $sheet->setCellValue($lastColumn . $totalRow, number_format($grandTotal));

                // Style total row
                $sheet->getStyle('A' . $totalRow . ':' . $lastColumn . $totalRow)->applyFromArray([
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
