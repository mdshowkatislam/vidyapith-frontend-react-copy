<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentPromote\StudentPromoteListRequest;
use App\Http\Requests\StudentPromote\StudentPromoteStoreRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\StudentService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentPromotionController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function studentPromoteList(StudentPromoteListRequest $request)
    {
        try {
            $request_data = $request->all();
            $eiinId = app('sso-auth')->user()->eiin;
            if ($request_data) {
                $students = $this->studentService->getStudentListByAcademicDetails($request_data, $eiinId, 2023);
            } else {
                $students = [];
            }

            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
    public function studentPromoteStore(StudentPromoteStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $class_room = ClassRoom::where('branch_id', $request->branch_uid)
                ->where('version_id', $request->version_uid)
                ->where('shift_id', $request->shift_uid)
                ->where('class_id', $request->class_uid)
                ->where('section_id', $request->section_uid)
                ->where('session_year', date('Y'))
                ->first();
            if (!$class_room) {
                $class_room = new ClassRoom();

                $class_room->eiin = app('sso-auth')->user()->eiin;
                $class_room->class_id = $request->class_uid;
                $class_room->section_id = $request->section_uid;
                $class_room->session_year = date('Y');
                $class_room->branch_id = $request->branch_uid;
                $class_room->shift_id = $request->shift_uid;
                $class_room->version_id = $request->version_uid;
                $class_room->status = 1;
                $class_room->save();
            }

            foreach ($request->roll as $student_uid => $roll) {
                if (!$roll) {
                    DB::rollBack();
                    $message = 'অবশ্যই শিক্ষার্থীর রোল নম্বর প্রদান করতে হবে।';
                    return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
                }
                $roll_exixt = StudentClassInfo::where('class_room_uid', $class_room->uid)->where('roll', $roll)->where('session_year', date('Y'))->first();
                if ($roll_exixt) {
                    $message = 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।';
                    return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
                }
                $student_class_info = new StudentClassInfo();
                $student_class_info->student_uid = $student_uid;
                $student_class_info->roll = $roll;
                $student_class_info->class_room_uid = $class_room->uid;
                $student_class_info->session_year = date('Y');
                $student_class_info->save();

                $student = Student::where('uid', $student_uid)->first();
                $student->incremental_no = '1';
                $student->save();
            }
            DB::commit();
            $message = 'শিক্ষার্থীদের সফলভাবে নতুন শ্রেণিতে উন্নীত হয়েছে।';
            return $this->successResponse($message, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $message = 'শিক্ষার্থীদের নতুন শ্রেণিতে উন্নীত করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }
}
