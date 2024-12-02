<?php

namespace App\Exports;

use App\Models\Overtime;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OvertimeExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithDrawings, WithEvents
{
    protected $staffId;
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $overtimeType;

    public function __construct($staffId, $startDate, $endDate, $status, $overtimeType)
    {
        $this->staffId = $staffId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->overtimeType = $overtimeType;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Overtime::with('user', 'attendance', 'attendance.activitytypeclockin', 'attendance.activitycategoryclockin', 'attendance.activitytypeclockout', 'attendance.activitycategoryclockout')
            ->when($this->staffId, function ($query, $staffId) {
                return $query->where('user_id', $staffId);
            })
            ->when($this->startDate, function ($query, $startDate) {
                return $query->where('overtimeStart', '>=', $startDate . ' 00:00:00');
            })
            ->when($this->endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate . ' 23:59:59');
            })
            ->when($this->status == 1, function ($query) {
                return $query->whereNull('rejectDate');
            })
            ->when($this->status == 2, function ($query) {
                return $query->whereNotNull('rejectDate');
            })
            ->when($this->overtimeType == 1, function ($query) {
                return $query->whereNotNull('attendance_id');
            })
            ->when($this->overtimeType == 2, function ($query) {
                return $query->whereNull('attendance_id');
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
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
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
            $overtime->attendance->clockInLocation ?? 'Manual Request',
            $overtime->attendance->activitytypeclockin->name ?? '-',
            $overtime->attendance->activitycategoryclockin->name ?? '-',
            $overtime->attendance->clock_in_customer ?? $overtime->customer ?? '-',
            $overtime->overtimeEnd,
            $overtime->attendance->clockOutLocation ?? 'Manual Request',
            $overtime->attendance->activitytypeclockout->name ?? '-',
            $overtime->attendance->activitycategoryclockout->name ?? '-',
            $overtime->attendance->clock_out_customer ?? $overtime->customer ?? '-',
            $overtime->overtimeTotal,
            $overtime->attendance ? (new Carbon($overtime->attendance->clockInTime))->diffInMinutes(new Carbon($overtime->attendance->clockOutTime)) : "Request Manual",
            $overtime->attendance ? ($overtime->attendance->shift ?? "Manual Request") : 'Manual Request',
            $overtime->rejectDate ? 'Rejected' : 'Approved',

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
            'Clock In Location',
            'Clock In Activity Type',
            'Clock In Activity Category',
            'Clock In Customer',
            'Overtime End',
            'Clock Out Location',
            'Clock Out Activity Type',
            'Clock Out Activity Category',
            'Clock Out Customer',
            'Total Overtime (Minutes)',
            'Total Attendance (Minutes)',
            'Shift',
            'Status',
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
                $clockInDrawing->setCoordinates('P' . ($index + 2));
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
                $clockOutDrawing->setCoordinates('Q' . ($index + 2));
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
