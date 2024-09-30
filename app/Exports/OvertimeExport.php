<?php

namespace App\Exports;

use App\Models\Overtime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeExport implements FromCollection, WithMapping, WithHeadings
{
    protected $staffId;
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($staffId, $startDate, $endDate, $status)
    {
        $this->staffId = $staffId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }
  
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Overtime::when($this->staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
        ->when($this->startDate, function ($query, $startDate) {
            return $query->where('overtimeStart', '>=', $startDate);
        })
        ->when($this->endDate, function ($query, $endDate) {
            return $query->where('overtimeEnd', '<=', $endDate);
        })
        ->when($this->status == 1, function ($query) {
            return $query->whereNull('rejectDate');
        })
        ->when($this->status == 2, function ($query) {
            return $query->whereNotNull('rejectDate');
        })
        ->with('user', 'attendance')
        ->get();
    }

     /**
    * Memetakan kolom yang ingin diekspor
    * @var Overtime $overtime
    */
    public function map($overtime): array
    {
        return [
            $overtime->user->first_name . ' ' . $overtime->user->last_name,
            $overtime->overtimeStart,
            $overtime->overtimeEnd,
            // Add more columns as needed
        ];
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Full Name',
            'Overtime Start',
            'Overtime End',
            // Add more headers as needed
        ];
    }
}
