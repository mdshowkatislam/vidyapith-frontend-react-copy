<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Branch;
use App\Models\Institute;
use App\Models\Section;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\Version;
use App\Services\ClassService;
use App\Services\SubjectService;
use App\Services\TeacherService;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Exception;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $teacherService;
    private $classService;
    private $subjectService;

    public function __construct(TeacherService $teacherService, ClassService $classService, SubjectService $subjectService)
    {
        $this->teacherService = $teacherService;
        $this->classService = $classService;
        $this->subjectService = $subjectService;
    }

    public function teacherDashboard(Request $request)
    {
        $data['user'] = app('sso-auth')->user();
        $data['institute'] = Institute::select(['uid', 'eiin', 'caid', 'division_uid', 'district_uid', 'upazila_uid', 'unions', 'institute_name', 'institute_name_bn'])->where('eiin', $data['user']->eiin)->get();
        $data['branches'] = Branch::select(['uid', 'eiin', 'branch_name', 'branch_location'])->where('eiin', $data['user']->eiin)->get();
        $data['shifts'] = Shift::select(['uid', 'eiin', 'shift_name', 'shift_details'])->where('eiin', $data['user']->eiin)->get();
        $data['versions'] = Version::select(['uid', 'eiin', 'version_name'])->where('eiin', $data['user']->eiin)->get();
        $data['teachers'] = Teacher::select(['id', 'uid', 'caid', 'eiin', 'pdsid', 'name_bn', 'name_en', 'designation', 'designation_id', 'email', 'mobile_no', 'date_of_birth', 'gender', 'nid'])
        ->with(['assigned_subjects'=>function($q){
            $q->select('teacher_id','subject_id')
            ->groupBy('teacher_id','subject_id');
        }])
        ->where('eiin', $data['user']->eiin)->get();

        $data['sections'] = Section::select(['uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_year', 'section_name'])->where('eiin', $data['user']->eiin)->get();

        $data['classes'] = $this->classService->getAll();
        $data['subjects'] = $this->subjectService->getAll();


        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
