<?php

namespace App\Jobs;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\Api\AuthService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImportStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    private $value, $request, $batch_id, $auth, $token;
    private $classRoomService;
    private $studentService;

    public function __construct($value, $request, $batch_id, $auth, $token, $classRoomService, $studentService)
    {
        $this->value = $value;
        $this->request = $request;
        $this->batch_id = $batch_id;
        $this->auth = $auth;
        $this->token = $token;
        $this->classRoomService = $classRoomService;
        $this->studentService = $studentService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $row = $this->value;

        $eiin = $this->auth->eiin;
        $branch = $this->request['branch'];
        $shift = $this->request['shift'];
        $version = $this->request['version'];
        $class = $this->request['class'];
        $section = $this->request['section'];
        $registration_year = $this->request['registration_year'];
        $roll = $row['roll'];

        $std_obj = [
            'roll' => $row['roll'],
            'student_name_bn' => $row['student_name_bn'],
            'student_name_en' => $row['student_name_en'],
            'brid' => $row['brid'],
            'date_of_birth' => $row['date_of_birth'],
            'gender' => $row['gender'],
            'religion' => $row['religion'],
            'disability_status' => $row['disability_status'],
            'student_mobile_no' => $row['student_mobile_no'],
            'father_name_en' => $row['father_name_en'],
            'father_name_bn' => $row['father_name_bn'],
            'father_mobile_no' => $row['father_mobile_no'],
            'mother_name_bn' => $row['mother_name_bn'],
            'mother_mobile_no' => $row['mother_mobile_no'],
            'guardian_name_bn' => $row['guardian_name_bn'],
            'guardian_mobile_no' => $row['guardian_mobile_no'],
            'eiin' => $eiin
        ];

        try {
            $class_room_payload = [
                'eiin' => @$eiin,
                'branch' => @$branch,
                'shift' => @$shift,
                'version' => @$version,
                'class' => @$class,
                'section' => @$section,
                'session_year' => $registration_year
            ];
            
            $class_room_info = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $studentRollExist = $this->studentService->isRollExists($class_room_info->uid, $roll);

            $makeValidation = Validator::make($row, [
                'roll' => 'required',
                'student_name_en' => 'required',
                'father_name_en' => 'required',
                'gender' => 'required',
                'religion' => 'required',
            ]);

            if ($makeValidation->fails()) {
                $errors = $makeValidation->errors();
                // Get all error messages as an array
                $errorMessagesArray = $errors->all();
                DB::table('students_import_faild_data')->insert([
                    'imported_data' => json_encode($std_obj),
                    'error_description' => json_encode($errorMessagesArray),
                    'batch_id' => $this->batch_id,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                if ($studentRollExist) {
                    DB::table('students_import_faild_data')->insert([
                        'imported_data' => json_encode($std_obj),
                        'error_description' => json_encode([
                            'message' => 'Roll number already exists in this section!'
                        ]),
                        'batch_id' => $this->batch_id,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                } else {
                    $student = $this->studentService->create($std_obj,  $class_room_info->uid);
                }
            }
        } catch (\Exception $e) {
            DB::table('students_import_faild_data')->insert([
                'imported_data' => json_encode(@$std_obj),
                'error_description' => json_encode(@$e->getMessage()),
                'batch_id' => $this->batch_id,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
