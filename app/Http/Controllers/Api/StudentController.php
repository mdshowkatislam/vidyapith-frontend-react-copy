<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Student;
use App\Models\Version;
use App\Models\Shift;
use App\Models\Section;
use App\Services\ClassRoomService\ClassRoomService;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\StudentService;

class StudentController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $studentService;
    private $classRoomService;

    public function __construct(StudentService $studentService, ClassRoomService $classRoomService)
    {
        $this->studentService = $studentService;
        $this->classRoomService = $classRoomService;
    }

    public function index(Request $request)
    {
        try {
            $students = $this->studentService->list($request);
            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eiin' => 'required|numeric',
            'student_name_en' => 'nullable|max:150',
            'student_name_bn' => 'nullable|max:150',
            'is_regular' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }
        try {
            $class_room_payload = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];
            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $student = $this->studentService->create($request->all(), $class_room->uid);
            $message = 'শিক্ষার্থী সফলভাবে যুক্ত করা হয়েছে।';
            return $this->successResponseWithData($student, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'শিক্ষার্থী যুক্ত করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getAllRequiredDropdownForStudents(Request $request)
    {
        $versionList = Version::on('db_read')->where('branch_id', $request->branchId)->get();
        $shiftList = Shift::on('db_read')->where('branch_id', $request->branchId)->get();
        $sectionList = Section::on('db_read')->where('branch_id', $request->branchId)->get();

        $response = [
            'status' => 'ok',
            'data' => [
                'versionList' => $versionList,
                'shiftList' => $shiftList,
                'sectionList' => $sectionList,
            ]
        ];

        return $response;
    }

    public function upazillaTotalStudents(Request $request)
    {
        try {
            $student = $this->studentService->upazillaTotalStudents($request);
            return $this->successResponse($student, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function foreignTotalStudents()
    {
        try {
            $student = $this->studentService->foreignTotalStudents();
            return $this->successResponse($student, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
