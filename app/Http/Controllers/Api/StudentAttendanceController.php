<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Exception;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class StudentAttendanceController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function storeAttendance(Request $request)
    {
        try {
            $request_data = $request->all();
            foreach($request_data['attendance'] as $list){
                $data = StudentAttendance::where('student_uid', $list['student_uid'])
                ->where('session', $request_data['session'])
                ->first();

                if($data){
                    $data = $data;
                }else{
                    $data = new StudentAttendance();
                }
                $data->student_uid = $list['student_uid'];
                $data->session = $request_data['session'];
                $month_column_name = strtolower(date('F', strtotime($request_data['date'])));
                $attendance = collect(json_decode($data->$month_column_name,true));
                $exist_attendance_key = $attendance->where('date', (date('d', strtotime($request_data['date']))))->where('teacher_uid',$request_data['teacher_uid'])->keys()->first();
                if($exist_attendance_key){
                    $attendance[$exist_attendance_key]=[
                        "date" => date('d', strtotime($request_data['date'])),
                        "time" => date('H:i:s', strtotime($request_data['date'])),
                        "is_present" => $list['is_present'],
                        "teacher_uid" => $request_data['teacher_uid'],
                        // "class_room_uid" => $request_data['class_room_uid'],
                        "subject_uid" => $request_data['subject_uid']
                    ];
                }else{
                    $attendance[]=[
                        "date" => date('d', strtotime($request_data['date'])),
                        "time" => date('H:i:s', strtotime($request_data['date'])),
                        "is_present" => $list['is_present'],
                        "teacher_uid" => $request_data['teacher_uid'],
                        // "class_room_uid" => $request_data['class_room_uid'],
                        "subject_uid" => $request_data['subject_uid']
                    ];
                }
                $data->$month_column_name = json_encode($attendance);
                $data->save();
            }
            return $this->successMessage("Attendance has been stored successfully!", Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    
    public function getAttendance(Request $request)
    {
        try {
            $attendance = DB::table('vw_student_attendance')
                        ->where('class_room_uid', $request->class_room_uid)
                        ->whereDate('attendance_date', $request->date)
                        ->get();
            return $this->successResponse($attendance, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
