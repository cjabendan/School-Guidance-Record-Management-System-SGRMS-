<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;
    protected $columns;

    public function __construct(array $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row: bold, blue background, white text, center alignment
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:' . $highestColumn . '1')->getFill()
            ->setFillType('solid')
            ->getStartColor()->setARGB('FF2563EB');
        $sheet->getStyle('A1:' . $highestColumn . '1')->getAlignment()->setHorizontal('center');
        // Optionally, set column widths
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}