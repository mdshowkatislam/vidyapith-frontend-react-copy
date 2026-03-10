<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentBulkImport implements ToArray, WithHeadingRow
{
    public function array(array $rows)
    {
        // Process the rows and collect the results in an array
        $processedRows = collect($rows)
            ->skip(1) // Skip the first row if it contains headings
            ->filter(function ($row) {
                return !empty(array_filter($row, function ($value) {
                    return $value !== null;
                }));
            })
            ->map(function ($row) {
                return $this->replaceKeys($row);
            })
            ->toArray();

        return $processedRows;
    }

    public static function replaceKeys(array $arrayDATA): array
    {
        if (empty($arrayDATA)) {
            return [];
        }

        $keys = [
            'roll',
            'student_name_bn',
            'student_name_en',
            'brid',
            'date_of_birth',
            'gender',
            'religion',
            'disability_status',
            'student_mobile_no',
            'father_name_en',
            'father_name_bn',
            'father_mobile_no',
            'mother_name_bn',
            'mother_mobile_no',
            'guardian_name_bn',
            'guardian_mobile_no'
        ];

        $arrayKeys = array_keys($arrayDATA);
        $arrayValues = array_values($arrayDATA);

        $renameMap = array();
        foreach ($arrayKeys as $item) {
            $renameMap[$item] = strtolower($item);
        }

        return array_combine(array_map(function ($el) use ($renameMap) {
            return $renameMap[$el];
        }, $arrayKeys), $arrayValues);
    }
}
