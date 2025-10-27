<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CasesExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
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
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();

        // Title (row 1)
        $sheet->mergeCells('A1:' . $highestColumn . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => 'center']
        ]);

        // Subtitle (row 2)
        $sheet->mergeCells('A2:' . $highestColumn . '2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => 'center']
        ]);

        // Date row (row 3)
        $sheet->mergeCells('A3:' . $highestColumn . '3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF555555']],
            'alignment' => ['horizontal' => 'center']
        ]);

        // Header row (row 5)
        $sheet->getStyle('A5:' . $highestColumn . '5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF2563EB']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
        ]);

        // Freeze header (row 6)
        $sheet->freezePane('A6');

        // Wrap text for all cells
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
        }
    }

    public function columnFormats(): array
    {
        return [];
    }
}
