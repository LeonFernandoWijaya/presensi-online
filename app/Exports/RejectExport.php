<?php

namespace App\Exports;

use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class RejectExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths
{
    protected $staffId;
    protected $startDate;
    protected $endDate;
    protected $departmentName;

    public function __construct($staffId, $startDate, $endDate, $departmentName)
    {
        $this->staffId = $staffId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->departmentName = $departmentName;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Overtime::with('user', 'attendance.activitytype', 'attendance.activitycategory')
            ->when($this->staffId, function ($query, $staffId) {
                return $query->where('user_id', $staffId);
            })
            ->when($this->startDate, function ($query, $startDate) {
                return $query->where('overtimeStart', '>=', $startDate);
            })
            ->when($this->endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate);
            })
            ->where('rejectDate', null)
            ->whereHas('user.department', function ($query) {
                $query->where('department_name', $this->departmentName);
            })
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
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            // Add more columns as needed
        ];
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
            $overtime->overtimeTotal . ' minutes',
            $overtime->rejectDate ? 'Rejected' : 'Approved',
            $overtime->attendance->activitytype->name,
            $overtime->attendance->activitycategory->name,
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
            'Overtime Start',
            'Overtime End',
            'Total Overtime',
            'Status',
            'Activity Type',
            'Activity Category',
            // Add more headers as needed
        ];
    }
}
