<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ScheduleImport implements ToArray, WithMapping
{
    public function array(array $array)
    {
        return $array;
    }

    public function map($row): array
    {
        // Map the row data to the desired format
        return array_map(function ($value, $index) {
            // Check if the value is in column 3 or 4 and is a date
            if (($index == 2 || $index == 3) && is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            return $value;
        }, $row, array_keys($row));
    }
}
