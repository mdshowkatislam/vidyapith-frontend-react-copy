<?php

namespace App\Jobs;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\Api\AuthService;
// use App\Services\ClassRoomService\ClassRoomService;
// use App\Services\StudentService;
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
            // 'date_of_birth' => $birthday,
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
            // 'branch' => $this->request['branch'],
            // 'version' => $this->request['version'],
            // 'shift' => $this->request['shift'],
            // 'class' => $this->request['class'],
            // 'section' => $this->request['section'],
            // 'registration_year' => $this->request['registration_year'],
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

            // $class_room_info = ClassRoom::where('branch_id', $branch)
            //     ->where('version_id', $version)
            //     ->where('shift_id', $shift)
            //     ->where('class_id', $class)
            //     ->where('section_id', $section)
            //     ->where('session_year', $registration_year)
            //     ->first();

            // if (!$class_room_info) {
            //     $class_room_info = new ClassRoom();
            //     $class_room_info->eiin = $eiin;
            //     $class_room_info->class_id = $class;
            //     $class_room_info->section_id = $section;
            //     $class_room_info->session_year = $registration_year;
            //     $class_room_info->branch_id = $branch;
            //     $class_room_info->shift_id = $shift;
            //     $class_room_info->version_id = $version;
            //     $class_room_info->status = 1;
            //     $class_room_info->save();
            // }
            // $studentRollExist = StudentClassInfo::where('class_room_uid', $class_room_info->uid)->where('roll', $roll)->where('session_year', $registration_year)->first();
            
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
                    // $student = Student::where('uid', $studentRollExist->student_uid)->first();
                    // $student->update($std_obj);
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
                    // start without auth check
                    // $student = Student::create($std_obj);
                    $student = $this->studentService->create($std_obj,  $class_room_info->uid);

                    // $student_class_info = new StudentClassInfo();
                    // $student_class_info->student_uid = $student->uid;
                    // $student_class_info->roll = $roll;
                    // $student_class_info->class_room_uid = @$class_room_info->uid;
                    // $student_class_info->session_year = @$registration_year;
                    // $student_class_info->save();
                    // echo $student_class_info->roll."5".PHP_EOL;
                    // end without auth check

                    // start with auth check
                    // $authRequest = AuthService::studentsImport1($row, $class, $registration_year, $this->auth, $this->token);
                    // if (@$authRequest->status == true) {
                    //     $authData = (object) $authRequest->data;
                    //     $studentCaid = Student::where('caid', @$authData->caid)->first();
                    //     $std_obj['caid'] = $authData->caid;
                    //     $std_obj['eiin'] = $authData->eiin;
                    //     if (!$studentCaid) {
                    // $student = Student::create($std_obj);

                    //     } else {
                    //         $studentCaid->update($std_obj);
                    //     }
                    // } else {

                    // DB::table('students_import_faild_data')->insert([
                    //     'imported_data' => json_encode($std_obj),
                    //     'error_description' => json_encode([
                    //         'message' => 'The auth process failed'
                    //     ]),
                    //     'batch_id' => $this->batch_id,
                    //     'status' => 0,
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
                    // }
                    // end with auth check
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
