<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithDrawings, WithEvents
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
            ->whereNotNull('clockOutTime')
            ->with('user', 'activitytype', 'activitycategory')
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
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
            // Add more columns as needed
        ];
    }

    /**
     * Memetakan kolom yang ingin diekspor
     * @var Attendance $attendance
     */
    public function map($attendance): array
    {
        $clockInTime = new Carbon($attendance->clockInTime);
        $clockOutTime = new Carbon($attendance->clockOutTime);
        $diffInMinutes = $clockInTime->diffInMinutes($clockOutTime);
        return [
            $attendance->user->first_name . ' ' . $attendance->user->last_name,
            $attendance->clockInTime,
            $attendance->clockOutTime,
            $diffInMinutes,
            $attendance->activitytype->name,
            $attendance->activitycategory->name,
            $attendance->customer,
            $attendance->clockInLocation,
            $attendance->clockOutLocation,
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
            'Total Attendance (Minutes)',
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
        $attendances = $this->collection();

        foreach ($attendances as $index => $attendance) {
            // Drawing for Clock In Photo
            $clockInDrawing = new Drawing();
            $clockInDrawing->setName('Clock In Picture');
            $clockInDrawing->setDescription('Clock In Picture');
            $clockInDrawing->setPath(storage_path('app/public/photos/' . $attendance->clockInPhoto)); // Adjust the path as needed
            $clockInDrawing->setHeight(100);
            $clockInDrawing->setCoordinates('j' . ($index + 2)); // Assuming the first row is the header
            $clockInDrawing->setOffsetX(5);
            $clockInDrawing->setOffsetY(5);

            // Drawing for Clock Out Photo
            $clockOutDrawing = new Drawing();
            $clockOutDrawing->setName('Clock Out Picture');
            $clockOutDrawing->setDescription('Clock Out Picture');
            $clockOutDrawing->setPath(storage_path('app/public/photos/' . $attendance->clockOutPhoto)); // Adjust the path as needed
            $clockOutDrawing->setHeight(100);
            $clockOutDrawing->setCoordinates('K' . ($index + 2)); // Assuming the first row is the header
            $clockOutDrawing->setOffsetX(5);
            $clockOutDrawing->setOffsetY(5);

            $drawings[] = $clockInDrawing;
            $drawings[] = $clockOutDrawing;
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
                $attendances = $this->collection();

                foreach ($attendances as $index => $attendance) {
                    $sheet->getRowDimension($index + 2)->setRowHeight(100); // Set row height to match image height
                }

                $cellRange = 'A1:W5000'; // All cells
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
