<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithUpserts;

use App\Services\Api\AuthService;

class StudentsImport implements ToModel, WithValidation, WithHeadingRow, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            new StudentsImport()
        ];
    }

    public function model(array $row)
    {
        $authRequest = AuthService::studentsImport($row, request('class'), request('registration_year'));

        if (@$authRequest->status == true) {

            $authData = (object) $authRequest->data;

            if (isset($row['date_of_birth']) && !empty($row['date_of_birth'])) {
                $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
            } else {
                $birthday = NULL;
            }

            Student::updateOrInsert(
                [
                    'brid' => $row['brid']
                ],
                [
                    'uid' => hexdec(uniqid()),
                    'roll' => $row['roll'],
                    'student_name_bn' => $row['student_name_bn'],
                    'student_name_en' => $row['student_name_en'],
                    'brid' => $row['brid'],
                    'date_of_birth' => $birthday,
                    'gender' => $row['gender'],
                    'religion' => $row['religion'],
                    'disability_status' => $row['disability_status'],
                    'student_mobile_no' => $row['student_mobile_no'],
                    'father_name_bn' => $row['father_name_bn'],
                    'father_mobile_no' => $row['father_mobile_no'],
                    'mother_name_bn' => $row['mother_name_bn'],
                    'mother_mobile_no' => $row['mother_mobile_no'],
                    'guardian_name_bn' => $row['guardian_name_bn'],
                    'guardian_mobile_no' => $row['guardian_mobile_no'],
                    'branch' => request('branch'),
                    'version' => request('version'),
                    'shift' => request('shift'),
                    'class' => request('class'),
                    'section' => request('section'),
                    'registration_year' => request('registration_year'),
                    'caid' => @$authData->caid,
                    'eiin' => @$authData->eiin,
                ]
            );

            // return new Student([
            //     'roll' => $row['roll'],
            //     'student_name_bn' => $row['student_name_bn'],
            //     'student_name_en' => $row['student_name_en'],
            //     'brid' => $row['brid'],
            //     'date_of_birth' => $birthday,
            //     'gender' => $row['gender'],
            //     'religion' => $row['religion'],
            //     'disability_status' => $row['disability_status'],
            //     'student_mobile_no' => $row['student_mobile_no'],
            //     'father_name_bn' => $row['father_name_bn'],
            //     'father_mobile_no' => $row['father_mobile_no'],
            //     'mother_name_bn' => $row['mother_name_bn'],
            //     'mother_mobile_no' => $row['mother_mobile_no'],
            //     'guardian_name_bn' => $row['guardian_name_bn'],
            //     'guardian_mobile_no' => $row['guardian_mobile_no'],
            //     'branch' => request('branch'),
            //     'version' => request('version'),
            //     'shift' => request('shift'),
            //     'class' => request('class'),
            //     'section' => request('section'),
            //     'registration_year' => request('registration_year'),
            //     'caid' => $authData->caid,
            //     'eiin' => $authData->eiin,
            // ]);
            return null;
        } else {
            return false;
        }
    }

    public function rules(): array
    {
        return [
            'roll' => 'required|numeric',
            '*.roll' => 'required|numeric',
            'student_name_bn' => 'required',
            '*.student_name_bn' => 'required',
            'student_name_en' => 'required',
            '*.student_name_en' => 'required',
            'brid' => 'required',
            '*.brid' => 'required',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable',
            'religion' => 'nullable',
            'disability_status' => 'nullable',
            'student_mobile_no' => 'nullable',
            'father_name_bn' => 'required',
            '*.father_name_bn' => 'required',
            'father_mobile_no' => 'required',
            '*.father_mobile_no' => 'required',
            'mother_name_bn' => 'required',
            '*.mother_name_bn' => 'required',
            'mother_mobile_no' => 'nullable',
            'guardian_name_bn' => 'nullable',
            'guardian_mobile_no' => 'nullable',
        ];
    }

}
