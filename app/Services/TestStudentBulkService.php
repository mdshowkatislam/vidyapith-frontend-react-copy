<?php

namespace App\Services;

use App\Models\Student;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestStudentBulkService
{
    private $value,$request,$batch_id,$auth;

    public function InsertData($value,$request,$batch_id,$auth){
        $this->value = $value;
        $this->request = $request;
        $this->batch_id = $batch_id;
        $this->auth = $auth;
        $row = $this->value;
        if (isset($row['date_of_birth']) && !empty($row['date_of_birth'])) {
            $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $birthday)) { $birthday = NULL; }
        } else {
            $birthday = NULL;
        }

        $eiin = $this->auth->eiin;
            $branch = $this->request['branch'];
            $shift = $this->request['shift'];
            $version = $this->request['version'];
            $class = $this->request['class'];
            $section = $this->request['section'];
            $registration_year = $this->request['registration_year'];
            $roll = $row['roll'];
            $studentExist =  Student::where(function ($query) use ($eiin, $branch, $shift, $version, $class, $section, $registration_year, $roll) {
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
            'branch' => $this->request['branch'],
            'version' => $this->request['version'],
            'shift' => $this->request['shift'],
            'class' => $this->request['class'],
            'section' => $this->request['section'],
            'registration_year' => $this->request['registration_year'],
        ];

        $makeValidation = Validator::make($row,[
            'roll' => 'required',
            'student_name_en' => 'required',
            'father_name_bn' => 'required',
        ]);

        if ($makeValidation->fails()) {
            $errors = $makeValidation->errors();
            // Get all error messages as an array
            $errorMessagesArray = $errors->all();
            DB::table('students_import_faild_data')->insert([
                'imported_data'=>json_encode($std_obj),
                'error_description'=>json_encode($errorMessagesArray),
                'batch_id' => $this->batch_id,
                'status' => 0,
                'created_at' =>now(),
                'updated_at' =>now(),
            ]);

        } else {
            if($studentExist) {  
                $std_obj['uid']  =  $studentExist->uid;   
                $studentExist->update($std_obj);
            } else {
                $authRequest = AuthService::studentsImport($row, request('class'), request('registration_year'));
                if (@$authRequest->status == true) {
                    $authData = (object) $authRequest->data;
                    $studentCaid = Student::where('caid', @$authData->caid)->first();
                    $std_obj['caid']=$authData->caid;
                    $std_obj['eiin']=$authData->eiin;
                    if(!$studentCaid) {
                        $student = Student::create($std_obj);
                    } else {
                        $studentCaid->update($std_obj);
                    }
                } else {
                    DB::table('students_import_faild_data')->insert([
                        'imported_data'=>json_encode($std_obj),
                        'error_description'=>json_encode([
                            'message'=>'The auth process failed'
                        ]),
                        'batch_id' => $this->batch_id,
                        'status' => 0,
                        'created_at' =>now(),
                        'updated_at' =>now(),
                    ]);
                }
            }
        }
    }
}
