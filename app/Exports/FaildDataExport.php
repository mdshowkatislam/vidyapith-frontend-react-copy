<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FaildDataExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $batch_id;

    public function __construct($batch_id){
        $this->batch_id = $batch_id;
    }


    public function collection()
    {
        $failedData = DB::table('students_import_faild_data')->where('batch_id', $this->batch_id)->get();
        $data = [];
        foreach ($failedData as $key => $value) {
            $removedData = json_decode($value->imported_data, true);
            // Define an array of keys to unset
            $keysToRemove = ['branch', 'version','shift','class','section','registration_year'];
            // Unset each key from the array
            foreach ($keysToRemove as $key) {
                unset($removedData[$key]);
            }
            $data[] = $removedData;
        }

        return collect($data);
    }

    public function headings(): array
    {
        // Define the headers for your Excel file
        return [
            'roll',
            'student_name_bn',
            'student_name_en',
            'brid',
            'date_of_birth',
            'gender',
            'religion',
            'disability_status',
            'student_mobile_no',
            'father_name_bn',
            'father_mobile_no',
            'mother_name_bn',
            'mother_mobile_no',
            'guardian_name_bn',
            'guardian_mobile_no',
            // Add more headers as needed
        ];
    }
}
