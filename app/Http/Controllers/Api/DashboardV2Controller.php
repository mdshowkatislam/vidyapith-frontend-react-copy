<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BiEvaluation;
use App\Models\BiReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\ExamConfigure;
use App\Models\Institute;
use App\Models\PiBiReview;
use App\Models\PiEvaluation;
use App\Models\PiReview;
use App\Models\Section;
use App\Models\Shift;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Version;
use App\Services\ClassService;
use App\Services\SubjectService;
use App\Services\SubjectTeacherService\SubjectTeacherService;
use App\Services\TeacherService;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class DashboardV2Controller extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $teacherService;
    private $classService;
    private $subjectService;
    private $subjectTeacherService;

    public function __construct(TeacherService $teacherService, ClassService $classService, SubjectService $subjectService, SubjectTeacherService $subjectTeacherService)
    {
        $this->teacherService = $teacherService;
        $this->classService = $classService;
        $this->subjectService = $subjectService;
        $this->subjectTeacherService = $subjectTeacherService;
    }

    public function teacherDashboard(Request $request)
    {
        $data['user'] = app('sso-auth')->user();
        $data['institute'] = Institute::select(['uid', 'eiin', 'caid', 'division_uid', 'district_uid', 'upazila_uid', 'unions', 'institute_name', 'institute_name_bn'])->where('eiin', $data['user']->eiin)->get();
        $data['branches'] = Branch::select(['uid', 'eiin', 'branch_name', 'branch_location'])->where('eiin', $data['user']->eiin)->get();
        $data['shifts'] = Shift::select(['uid', 'eiin', 'shift_name', 'shift_details'])->where('eiin', $data['user']->eiin)->get();
        $data['versions'] = Version::select(['uid', 'eiin', 'version_name'])->where('eiin', $data['user']->eiin)->get();
        $data['teachers'] = Teacher::select(['id', 'uid', 'caid', 'eiin', 'pdsid', 'name_bn', 'name_en', 'designation', 'designation_id', 'email', 'mobile_no', 'date_of_birth', 'gender', 'nid', 'division_id', 'district_id', 'upazilla_id', 'image', 'signature', 'joining_date', 'blood_group', 'emergency_contact'])
            ->where('caid', $data['user']->caid)->get();
        $data['teachers'][0]['is_class_teacher'] = ClassRoom::select('uid', 'eiin', 'branch_id', 'version_id', 'shift_id', 'class_id', 'section_id', 'session_year')->where('class_teacher_id', $data['teachers'][0]->uid)->where('session_year', date('Y'))->first();
        $data['sections'] = Section::select(['uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_year', 'section_name'])->where('eiin', $data['user']->eiin)->get();
        $data['classes'] = $this->classService->getAll();

        $data['subjects'] = $this->subjectTeacherService->getOwnSubjectByTeacherId($data['teachers'][0]['uid'], date('Y'))->toArray();
        // foreach ($data['subjects'] as $key => $item) {
        //     $data['subjects'][$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
        // }

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    public function classList()
    {
        $classes = $this->classService->getAll();

        try {
            return $this->successResponse($classes, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }


    public function dashboardStatistics()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $data['teacher_count'] = Teacher::where('eiin', $eiinId)->count();
            $data['student_count'] = Student::where('eiin', $eiinId)->count();
            $data['class_room_count'] = ClassRoom::where('eiin', $eiinId)->where('session_year', date('Y'))->count();

            $data['present_today'] = Attendance::where('eiin', $eiinId)
                                        ->whereDate('date', date('Y-m-d'))
                                        ->where('period', 1)
                                        ->whereIn('status', ['Present', 'Late'])
                                        ->count();

            $data['present_yesterday'] = Attendance::where('eiin', $eiinId)
                                                ->whereDate('date', now()->subDay())
                                                ->where('period', 1)
                                                ->whereIn('status', ['Present', 'Late'])
                                                ->count();

            $data['present_last_week'] = Attendance::where('eiin', $eiinId)
                                                ->whereBetween('date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
                                                ->where('period', 1)
                                                ->whereIn('status', ['Present', 'Late'])
                                                ->count();

            $data['present_last_month'] = Attendance::where('eiin', $eiinId)
                                              ->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                                              ->where('period', 1)
                                              ->whereIn('status', ['Present', 'Late'])
                                              ->count();

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

   public function dashboardSummary()
    {
        $eiinId = getAuthInfo()['eiin'];
        
        // Use Promise/parallel queries to reduce database round trips
        $queries = [
            'attendance' => $this->getTodayAttendance($eiinId),
            'gender' => $this->getGenderDistribution($eiinId),
            'religion' => $this->getReligionDistribution($eiinId),
            'exam' => $this->getExamDistribution($eiinId),
            'teacher' => $this->getTeacherGenderDistribution($eiinId),
            'classCount' => $this->getClassStudentCount($eiinId),
        ];
        
        // Execute all queries in parallel (if using async drivers)
        // For synchronous execution, this still organizes the code better
        $results = [];
        foreach ($queries as $key => $query) {
            $results[$key] = $query;
        }
        
        return response()->json($results);
    }

    private function getTodayAttendance($eiinId)
    {
        // return Cache::remember("attendance_summary_{$eiinId}_" . today()->format('Y-m-d'), 60, function () use ($eiinId) {
            return Attendance::where('attendances.eiin', $eiinId)
                ->whereDate('date', today())
                ->join('class_names', 'class_names.uid', '=', 'attendances.class_id')
                ->selectRaw('
                    class_names.id,
                    class_names.class_name_en as class, 
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent
                ')
                ->groupBy('class_names.id', 'class_names.class_name_en')
                ->orderBy('class_names.id')
                ->get()
                ->map(function($item) {
                    return [
                        'class' => $item->class,
                        'present' => (int)$item->present,
                        'absent' => (int)$item->absent,
                    ];
                });
        // });
    }

    private function getGenderDistribution($eiinId)
    {
        // return Cache::remember("gender_distribution_{$eiinId}", 7200, function () use ($eiinId) {
            return Student::where('eiin', $eiinId)
                ->whereNotNull('gender')
                ->selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->orderByDesc('count')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => ucfirst($item->gender),
                        'value' => (int)$item->count,
                    ];
                });
        // });
    }

    private function getReligionDistribution($eiinId)
    {
        // return Cache::remember("religion_distribution_{$eiinId}", 7200, function () use ($eiinId) {
            return Student::where('eiin', $eiinId)
                ->whereNotNull('religion')
                ->selectRaw('religion, COUNT(*) as count')
                ->groupBy('religion')
                ->orderByDesc('count')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => ucfirst($item->religion),
                        'value' => (int)$item->count,
                    ];
                });
        // });
    }

    private function getExamDistribution($eiinId)
    {
        // return Cache::remember("exam_distribution_{$eiinId}", 3600, function () use ($eiinId) {
            return ExamConfigure::where('eiin', $eiinId)
                ->whereNotNull('exam_type')
                ->selectRaw('exam_type, COUNT(*) as count')
                ->groupBy('exam_type')
                ->orderByDesc('count')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => ucwords(str_replace('_', ' ', $item->exam_type)),
                        'value' => (int)$item->count,
                    ];
                });
        // });
    }

    private function getTeacherGenderDistribution($eiinId)
    {
        // return Cache::remember("teacher_gender_distribution_{$eiinId}", 20, function () use ($eiinId) {
            return Teacher::where('eiin', $eiinId)
                ->whereNotNull('gender')
                ->selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->orderByDesc('count')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => ucwords($item->gender),
                        'value' => (int)$item->count,
                    ];
                });
        // });
    }

    private function getClassStudentCount($eiinId)
    {
        // return Cache::remember("class_student_count_{$eiinId}", 3600, function () use ($eiinId) {
            return Student::where('students.eiin', $eiinId)
                ->join('class_names', 'class_names.uid', '=', 'students.class')
                ->selectRaw('
                    class_names.id,
                    class_names.class_name_en as class, 
                    COUNT(*) as students
                ')
                ->groupBy('class_names.id', 'class_names.class_name_en')
                ->orderBy('class_names.id')
                ->get()
                ->map(function($item) {
                    return [
                        'class' => $item->class,
                        'students' => (int)$item->students,
                    ];
                });
        // });
    }

    public function piBiReviewList(Request $request)
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $pi_review = PiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
                ->whereHas('teacher', function ($query) use ($eiinId) {
                    $query->where('eiin', $eiinId);
                })
                ->whereHas('class_room', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->where(function ($query) use ($request) {
                    if (!empty($request['is_approved'])) {
                        $query->where('is_approved', $request['is_approved']);
                    }
                })
                // ->where('is_approved', 0)
                ->where('session', date('Y'))
                ->get();

            $bi_review = BiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
                ->whereHas('teacher', function ($query) use ($eiinId) {
                    $query->where('eiin', $eiinId);
                })
                ->whereHas('class_room', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->where(function ($query) use ($request) {
                    if ($request['is_approved']) {
                        $query->where('is_approved', $request['is_approved']);
                    }
                })
                // ->where('is_approved', 0)
                ->where('session', date('Y'))
                ->get();
            $data['reviews'] = array_merge($pi_review->toArray(), $bi_review->toArray());

            $data['subject_reviews'] = PiBiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
                ->whereHas('teacher', function ($query) use ($eiinId) {
                    $query->where('eiin', $eiinId);
                })
                ->whereHas('class_room', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->where(function ($query) use ($request) {
                    if ($request['is_approved']) {
                        $query->where('is_approved', $request['is_approved']);
                    }
                })
                // ->where('is_approved', 0)
                ->where('session', date('Y'))
                ->get();

            foreach ($data['subject_reviews'] as $key => $review) {
                $data['subject_reviews'][$key]['subject'] = $this->subjectService->getSubjectInfo($review->subject_uid);
            }

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function changePiBiApproveStatus($uid, Request $request)
    {
        $submitStatus = (int) $request->submit_status;

        $review = PiReview::where('uid', $uid)->first();
        if (!$review) {
            $review = BiReview::where('uid', $uid)->first();
        }

        if ($review) {
            $evaluations = PiEvaluation::on('db_evaluation')->where('evaluate_type', $review->evaluate_type)
                ->when($review instanceof PiReview, function ($query) use ($review) {
                    return $query->where('oviggota_uid', $review->oviggota_uid)
                        ->where('pi_uid', $review->pi_uid);
                })
                ->when($review instanceof BiReview, function ($query) use ($review) {
                    return $query->where('subject_uid', $review->subject_uid);
                })
                ->where('teacher_uid', $review->teacher_uid)
                ->where('class_room_uid', $review->class_room_uid)
                ->get();

            foreach ($evaluations as $evaluation) {
                $evaluation->submit_status = $submitStatus;
                $evaluation->save();
            }

            $review->is_approved = $submitStatus;
            $review->save();
        }
        if($review->is_approved == 1){
            $message = 'আবেদনটি অনুমোদন করা হয়েছে।';
        }
        elseif($review->is_approved == 1){
            $message = 'আবেদনটি বাতিল করা হয়েছে।';
        }
        else{
            $message = 'দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।';
        }
        return $this->successResponse($message, Response::HTTP_OK);
    }

    public function changePiBiApproveStatusSubjectWise($uid, Request $request)
    {
        $submitStatus = (int) $request->submit_status;
        $review = PiBiReview::where('uid', $uid)->first();

        $pi_evaluations = DB::connection('db_evaluation')->table('vw_pi_evolation')->select('uid', 'teacher_uid', 'class_room_uid')
            ->where('teacher_uid', $review->teacher_uid)
            ->where('subject_uid', $review->subject_uid)
            ->where('class_room_uid', $review->class_room_uid)
            ->get();
        $bi_evaluations = BiEvaluation::on('db_evaluation')->select('uid', 'teacher_uid', 'class_room_uid')
            ->where('teacher_uid', $review->teacher_uid)
            ->where('subject_uid', $review->subject_uid)
            ->where('class_room_uid', $review->class_room_uid)
            ->get();


        if ($submitStatus === 1) {
            foreach ($pi_evaluations as $item) {
                $pi = PiEvaluation::on('db_evaluation')->where('uid', $item->uid)->first();
                $pi->submit_status = 1;
                $pi->save();
            }
            foreach ($bi_evaluations as $item) {
                $bi = BiEvaluation::on('db_evaluation')->where('uid', $item->uid)->first();
                $bi->submit_status = 1;
                $bi->save();
            }
            $review->is_approved = 1;
        } else {
            foreach ($pi_evaluations as $item) {
                $pi = PiEvaluation::on('db_evaluation')->where('uid', $item->uid)->first();
                $pi->submit_status = 2;
                $pi->save();
            }
            foreach ($bi_evaluations as $item) {
                $bi = BiEvaluation::on('db_evaluation')->where('uid', $item->uid)->first();
                $bi->submit_status = 2;
                $bi->save();
            }
            $review->is_approved = 2;
        }
        $review->save();

        if($review->is_approved == 1){
            $message = 'আবেদনটি অনুমোদন করা হয়েছে।';
        }
        elseif($review->is_approved == 1){
            $message = 'আবেদনটি বাতিল করা হয়েছে।';
        }
        else{
            $message = 'দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।';
        }
        return $this->successResponse($message, Response::HTTP_OK);
    }
}
