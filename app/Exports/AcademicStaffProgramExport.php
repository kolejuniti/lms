<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AcademicStaffProgramExport implements FromView, WithEvents
{
    private array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('admin.report.export.academicStaffProgram', [
            'rows' => $this->rows,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A:N')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A:N')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            },
        ];
    }
}

