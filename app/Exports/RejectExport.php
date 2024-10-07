<?php

namespace App\Exports;

use App\Models\Overtime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RejectExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithDrawings, WithEvents
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
                return $query->where('overtimeStart', '>=', $startDate . ' 00:00:00');
            })
            ->when($this->endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate . ' 23:59:59');
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
            'H' => 20,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 30,
            // Add more columns as needed
        ];
    }

    /**
     * Memetakan kolom yang ingin diekspor
     * @var Overtime $overtime
     */
    public function map($overtime): array
    {
        $data = [
            $overtime->user->first_name . ' ' . $overtime->user->last_name,
            $overtime->overtimeStart,
            $overtime->overtimeEnd,
            $overtime->overtimeTotal,
            $overtime->rejectDate ? 'Rejected' : 'Approved',
            $overtime->attendance->activitytype->name ?? '-',
            $overtime->attendance->activitycategory->name ?? '-',
            $overtime->attendance->customer ?? $overtime->customer ?? '-',
            $overtime->attendance->clockInLocation ?? 'Request Manual',
            $overtime->attendance->clockOutLocation ?? 'Request Manual',
        ];

        if ($overtime->attendance && file_exists(storage_path('app/public/photos/' . $overtime->attendance->clockInPhoto))) {
            $data[] = null; // Add a placeholder for the image
        } else {
            $data[] = 'Manual Request'; // Add a "-" if the image does not exist
        }

        if ($overtime->attendance && file_exists(storage_path('app/public/photos/' . $overtime->attendance->clockOutPhoto))) {
            $data[] = null; // Add a placeholder for the image
        } else {
            $data[] = 'Manual Request'; // Add a "-" if the image does not exist
        }

        return $data;
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
            'Total Overtime (Minutes)',
            'Status',
            'Activity Type',
            'Activity Category',
            'Customer',
            'Clock In Location',
            'Clock Out Location',
            'Clock In Picture',
            'Clock Out Picture',
            // Add more headers as needed
        ];
    }

    /**
     * @return array
     */
    public function drawings()
    {
        $drawings = [];
        $overtimes = $this->collection();

        foreach ($overtimes as $index => $overtime) {
            if ($overtime->attendance && file_exists(storage_path('app/public/photos/' . $overtime->attendance->clockInPhoto))) {
                $clockInDrawing = new Drawing();
                $clockInDrawing->setName('Clock In Picture');
                $clockInDrawing->setDescription('Clock In Picture');
                $clockInDrawing->setPath(storage_path('app/public/photos/' . $overtime->attendance->clockInPhoto));
                $clockInDrawing->setHeight(100);
                $clockInDrawing->setCoordinates('K' . ($index + 2));
                $clockInDrawing->setOffsetX(5);
                $clockInDrawing->setOffsetY(5);
                $drawings[] = $clockInDrawing;
            }

            if ($overtime->attendance && file_exists(storage_path('app/public/photos/' . $overtime->attendance->clockOutPhoto))) {
                $clockOutDrawing = new Drawing();
                $clockOutDrawing->setName('Clock Out Picture');
                $clockOutDrawing->setDescription('Clock Out Picture');
                $clockOutDrawing->setPath(storage_path('app/public/photos/' . $overtime->attendance->clockOutPhoto));
                $clockOutDrawing->setHeight(100);
                $clockOutDrawing->setCoordinates('L' . ($index + 2));
                $clockOutDrawing->setOffsetX(5);
                $clockOutDrawing->setOffsetY(5);
                $drawings[] = $clockOutDrawing;
            }
        }

        return $drawings;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $overtimes = $this->collection();

                foreach ($overtimes as $index => $overtime) {
                    $sheet->getRowDimension($index + 2)->setRowHeight(100); // Set row height to match image height
                }

                $cellRange = 'A1:W5000'; // All cells
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
