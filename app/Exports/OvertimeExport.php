<?php

namespace App\Exports;

use App\Models\Overtime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeExport implements FromCollection
{
    protected $overtime;

    public function __construct($overtime)
    {
        $this->overtime = $overtime;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->overtime;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Header 1',
            'Header 2',
            'Header 3',
            // Add more headers as needed
        ];
    }
}
