<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AttendanceExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths
{
    protected $staffId;
    protected $startDate;
    protected $endDate;

    public function __construct($staffId, $startDate, $endDate)
    {
        $this->staffId = $staffId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Attendance::when($this->staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
        ->when($this->startDate, function ($query, $startDate) {
            return $query->where('clockInTime', '>=', $startDate);
        })
        ->when($this->endDate, function ($query, $endDate) {
            return $query->where('clockOutTime', '<=', $endDate);
        })
        ->with('user')
        ->get();
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            // Add more columns as needed
        ];
    }

    /**
    * Memetakan kolom yang ingin diekspor
    * @var Attendance $attendance
    */
    public function map($attendance): array
    {
        return [
            $attendance->user->first_name . ' ' . $attendance->user->last_name,
            $attendance->clockInTime,
            $attendance->clockOutTime,
            // Add more columns as needed
        ];
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Staff',
            'Clock In',
            'Clock Out',
            // Add more headers as needed
        ];
    }
}
