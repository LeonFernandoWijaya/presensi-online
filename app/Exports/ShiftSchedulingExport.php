<?php
namespace App\Exports;

use App\Models\ShiftScheduling;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShiftSchedulingExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang ingin diambil
        return ShiftScheduling::select('user_id', 'shift_id', 'start_date', 'end_date')->orderBy('user_id')->orderBy('start_date')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'USER ID',
            'SHIFT ID',
            'START DATE',
            'END DATE',
        ];
    }
}
?>