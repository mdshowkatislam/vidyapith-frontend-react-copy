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
use App\Services\StudentService;

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
        if (isset($row['date_of_birth']) && !empty($row['date_of_birth'])) {
            $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $birthday)) {
                $birthday = NULL;
            }
        } else {
            $birthday = NULL;
        }

        $eiin = auth()->user()->eiin;
        $branch = request()->branch;
        $shift = request()->shift;
        $version = request()->version;
        $class = request()->class;
        $section = request()->section;
        $registration_year = request()->registration_year;
        $roll = $row['roll'];

        $studentExist =  Student::on('db_read')->where(function ($query) use ($eiin, $branch, $shift, $version, $class, $section, $registration_year, $roll) {
            if (!empty($eiin)) {
                $query->where('eiin', $eiin);
            }
            if (!empty($branch)) {
                $query->where('branch', $branch);
            }
            if (!empty($shift)) {
                $query->where('shift', $shift);
            }
            if (!empty($version)) {
                $query->where('version', $version);
            }
            if (!empty($class)) {
                $query->where('class', $class);
            }
            if (!empty($section)) {
                $query->where('section', $section);
            }
            if (!empty($registration_year)) {
                $query->where('registration_year', $registration_year);
            }
            if (!empty($roll)) {
                $query->where('roll', $roll);
            }
        })
            ->first();

        $std_obj = [
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
        ];

        if ($studentExist) {
            $studentExist->update([
                $studentExist->uid,
                ...$std_obj,
            ]);
        } else {

            $authRequest = AuthService::studentsImport($row, request('class'), request('registration_year'));
            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $studentCaid = Student::where('caid', @$authData->caid)->first();
                if (!$studentCaid) {
                    return new Student([
                        ...$std_obj,
                        'caid' => $authData->caid,
                        'eiin' => $authData->eiin,
                    ]);
                } else {
                    $studentCaid->update([
                        $studentCaid->uid,
                        ...$std_obj,
                    ]);
                }
            } else {
                return false;
            }
        }
    }

    public function rules(): array
    {
        return [
            'roll' => 'required',
            '*.roll' => 'required',
            // 'student_name_bn' => 'required',
            // '*.student_name_bn' => 'required',
            'student_name_en' => 'required',
            '*.student_name_en' => 'required',
            // 'brid' => 'required',
            // '*.brid' => 'required',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable',
            'religion' => 'nullable',
            'disability_status' => 'nullable',
            'student_mobile_no' => 'nullable',
            'father_name_bn' => 'required',
            '*.father_name_bn' => 'required',
            // 'father_mobile_no' => 'required',
            // '*.father_mobile_no' => 'required',
            // 'mother_name_bn' => 'required',
            // '*.mother_name_bn' => 'required',
            'mother_mobile_no' => 'nullable',
            'guardian_name_bn' => 'nullable',
            'guardian_mobile_no' => 'nullable',
        ];
    }
}
