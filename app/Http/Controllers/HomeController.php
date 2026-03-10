<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Shift;
use App\Models\Branch;
use App\Models\Section;
use App\Models\Student;

use App\Models\Teacher;
use App\Models\Version;
// use App\Rules\TimeNotLessThan;
use App\Models\BiReview;
use App\Models\District;
use App\Models\PiReview;
use App\Models\Upazilla;
use App\Models\ClassRoom;
use App\Models\Institute;
use App\Models\PiBiReview;
use App\Models\BiEvaluation;
use App\Models\PiEvaluation;
use Illuminate\Http\Request;
use App\Models\SubjectTeacher;
use App\Services\ClassService;
use App\Services\ShiftService;
use App\Services\BranchService;
use Illuminate\Validation\Rule;
use App\Models\StudentClassInfo;
use App\Services\StudentService;
use App\Services\SubjectService;
use App\Services\TeacherService;
use App\Services\VersionService;
use App\Services\InstituteService;
use Illuminate\Support\Facades\DB;
use App\Services\BiEvaluationService;
use App\Services\PiEvaluationService;
use App\RolePermission\RolePermission;
use App\Models\BoardRegistationProcess;
use Illuminate\Support\Facades\Session;
use App\Services\BoardRegistrationService;
use App\Services\ClassRoomService\ClassRoomServiceInterface;
use App\Services\SubjectTeacherService\SubjectTeacherServiceInterface;

class HomeController extends Controller
{
    private $teacherService;
    private $studentService;
    private $branchService;
    private $classRoomService;
    private $classService;
    private $subjectService;
    private $instituteService;
    private $subjectTeacherService;
    private $shiftService;
    private $versionService;
    private $piEvaluationService;
    private $biEvaluationService;
    private $boardRegistrationService;

    public function __construct(
        studentService $studentService,
        TeacherService $teacherService,
        BranchService $branchService,
        ClassRoomServiceInterface $classRoomService,
        ClassService $classService,
        SubjectService $subjectService,
        InstituteService $instituteService,
        SubjectTeacherServiceInterface $subjectTeacherService,
        ShiftService $shiftService,
        VersionService $versionService,
        PiEvaluationService $piEvaluationService,
        BiEvaluationService $biEvaluationService,
        BoardRegistrationService $boardRegistrationService
    ) {
        $this->teacherService = $teacherService;
        $this->branchService = $branchService;
        $this->classRoomService = $classRoomService;
        $this->classService = $classService;
        $this->subjectService = $subjectService;
        $this->instituteService = $instituteService;
        $this->subjectTeacherService = $subjectTeacherService;
        $this->shiftService = $shiftService;
        $this->versionService = $versionService;
        $this->studentService = $studentService;
        $this->piEvaluationService = $piEvaluationService;
        $this->biEvaluationService = $biEvaluationService;
        $this->boardRegistrationService = $boardRegistrationService;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function noipunnoDashboard()
    {

        // dd(app('role')->roles());
        // (app('role')->setRole(RolePermission::$TEACHER));
        // dd(app('role')->permissions()->category->can_view);
        $user = auth()->user();
        $institute = $this->teacherService->getInstituteByEiin($user->eiin, 1);
        return view('frontend/noipunno/dashboard/index', compact('institute', 'user'));
    }

    public function noipunnoDashboard3_old()
    {
        // dd(app('role')->roles());
        // (app('role')->setRole(RolePermission::$TEACHER));
        // dd(app('role')->permissions()->category->can_view);
        $user = auth()->user();

        $eiinId = auth()->user()->eiin;
        // dd($eiinId);
        $institute = $this->teacherService->getInstituteByEiin($user->eiin, 1);
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1);
        $students = $this->studentService->getByEiinId($eiinId, 1);
        $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
        $count_section = Section::where('eiin', $eiinId)->count();

        $pi_review = PiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->get();
        $bi_review = BiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->get();
        $reviews = array_merge($pi_review->toArray(), $bi_review->toArray());

        $subject_reviews = PiBiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->get();

        foreach ($subject_reviews as $key => $review) {
            $subject_reviews[$key]['subject'] = $this->subjectService->getSubjectInfo($review->subject_uid);
        }

        return view('frontend/noipunno/dashboard/index2', compact('myTeachers', 'students', 'branchs', 'count_section', 'institute', 'user', 'reviews', 'subject_reviews'));
    }

    // public function noipunnoDashboard3(Request $request)
    // {
    //     if (mobileBrowserTurnOff($request->userAgent())) {
    //         return view('layouts.mobile_browser_message');
    //     }
    //     $user = auth()->user();
    //     $eiinId = auth()->user()->eiin;
    //     $institute = $this->teacherService->getInstituteByEiin($user->eiin, 1);

    //     $classes = [6, 7, 8, 9];

    //     $no_of_total_students = [];
    //     $no_of_payment_students = [];
    //     $no_of_temp_students = [];
    //     $no_of_registered_students = [];

    //     foreach ($classes as $class) {
    //         $no_of_total_students[$class] = $this->boardRegistrationService->totalStudentCount($class);
    //         $no_of_payment_students[$class] = BoardRegistationProcess::where('eiin', $eiinId)->where('class', $class)->where('session_year', date('Y'))->sum('no_of_payment_students');
    //         $no_of_temp_students[$class] = BoardRegistationProcess::where('eiin', $eiinId)->where('class', $class)->where('session_year', date('Y'))->sum('no_of_temp_students');
    //         $no_of_registered_students[$class] = BoardRegistationProcess::where('eiin', $eiinId)->where('class', $class)->where('session_year', date('Y'))->sum('no_of_registered_students');
    //     }
    //     // return view('frontend/noipunno/dashboard/index2', compact('myTeachers', 'students', 'branchs', 'count_class_room', 'institute', 'user', 'reviews', 'subject_reviews'));
    //     return view('frontend/noipunno/dashboard/index3', compact('institute', 'user', 'no_of_total_students', 'no_of_payment_students', 'no_of_temp_students', 'no_of_registered_students'));
    // }

    public function noipunnoDashboard3(Request $request)
    {
        if (mobileBrowserTurnOff($request->userAgent())) {
            return view('layouts.mobile_browser_message');
        }
        // dd(app('role')->roles());
        // (app('role')->setRole(RolePermission::$TEACHER));
        // dd(app('role')->permissions()->category->can_view);
        $user = auth()->user();
        $eiinId = auth()->user()->eiin;

        $institute = $this->teacherService->getInstituteByEiin($user->eiin, 1);
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1);
        $students = $this->studentService->getTotalStudentByEiinId($eiinId, 1);
        $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
        $count_class_room = ClassRoom::where('eiin', $eiinId)->where('session_year', date('Y'))->count();

        $pi_review = PiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->where('session', date('Y'))
            ->get();
        $bi_review = BiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->where('session', date('Y'))
            ->get();
        $reviews = array_merge($pi_review->toArray(), $bi_review->toArray());

        $subject_reviews = PiBiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('is_approved', 0)
            ->where('session', date('Y'))
            ->get();

        foreach ($subject_reviews as $key => $review) {
            $subject_reviews[$key]['subject'] = $this->subjectService->getSubjectInfo($review->subject_uid);
        }
        return view('frontend/noipunno/dashboard/index2', compact('myTeachers', 'students', 'branchs', 'count_class_room', 'institute', 'user', 'reviews', 'subject_reviews'));
    }
    public function noipunnoDashboard2()
    {
        $user = auth()->user();
        $institute = $this->teacherService->getInstituteByEiin($user->eiin);
        return view('frontend/noipunno/dashboard/dashborad-other', compact('institute', 'user'));
    }

    public function noipunnoDashboardUpazilla()
    {
        return view('frontend/noipunno/dashboard/upazilla-dashboard');
    }

    public function noipunnoDashboardSchoolDetails()
    {
        return view('frontend/noipunno/school-details/index');
    }

    public function noipunnoDashboardSchoolFocal()
    {
        return view('frontend/noipunno/school-focal/index');
    }

    public function noipunnoDashboardTeacherEdit()
    {
        return view('frontend/noipunno/teacher-add/edit');
    }

    // ClassRoom Start
    public function noipunnoDashboardClassRoomAdd()
    {
        try {
            $eiinId = auth()->user()->eiin;
            $data['branches'] = $this->branchService->getByEiinId($eiinId, 1);
            // $data['classes'] = $this->classService->getAll();
            $data['teachers'] =  $this->teacherService->getByEiinId($eiinId, 1, 1);
            $data['class_rooms'] = $this->classRoomService->getAllClassRoomsByEiin($eiinId, date('Y'));
            // $data['class_rooms'] = $this->classRoomService->getAllClassRoomsByEiinWithPagination($eiinId);

            return view('frontend/noipunno/classroom/index', $data);
        } catch (Exception $e) {
            // return view('errors/404');
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardClassRoomStore(Request $request)
    {
        $request->validate([
            'class_teacher_id' => 'required',
            'branch_id' => 'required',
            'version_id' => 'required',
            'shift_id' => 'required',
            'class_id' => 'required',
            'session_year' => 'required',
            // 'section_id' => 'required',
            'section_id' => [
                'required',
                Rule::unique('class_rooms')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_id', $request->branch_id)
                    ->where('version_id', $request->version_id)
                    ->where('shift_id', $request->shift_id)
                    ->where('class_id', $request->class_id)
                    ->where('session_year', $request->session_year)
                    ->where('deleted_at', null)
            ],
        ], [
            'class_teacher_id.required' => 'অনুগ্রহ করে শ্রেণি শিক্ষক প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন',
            'class_id.required' => 'অনুগ্রহ করে শ্রেণি প্রদান করুন',
            'section_id.required' => 'অনুগ্রহ করে সেকশন প্রদান করুন',
            'session_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন',
            'section_id.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না',
        ]);

        try {
            $this->classRoomService->createClassRoom($request->all());
            $notification = array(
                'message' => 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য সফলভাবে তৈরি করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.classroom.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardClassRoomEdit($id)
    {
        $eiinId = auth()->user()->eiin;
        $data['branches'] = $this->branchService->getByEiinId($eiinId, 1);
        // $data['classes'] = $this->classService->getAll();
        $data['teachers'] = $this->teacherService->getByEiinId($eiinId, 1, 1);
        $data['class_rooms'] = $this->classRoomService->getAllClassRoomsByEiin($eiinId);

        $data['class_room'] = $this->classRoomService->getClassRoomById($id);
        $data['subject_teachers'] = $this->subjectTeacherService->getSubjectByTeacherClassRoomId($id);

        return view('frontend/noipunno/classroom/edit', $data);
    }

    public function noipunnoDashboardClassRoomUpdate($uid, Request $request)
    {
        $request->validate([
            'class_teacher_id' => 'required',
            'branch_id' => 'required',
            'version_id' => 'required',
            'shift_id' => 'required',
            'class_id' => 'required',
            'session_year' => 'required',
            'section_id' => [
                'required',
                Rule::unique('class_rooms')->where(function ($query) use ($request) {
                    return $query
                        ->where('uid', '!=', $request->id)
                        ->where('eiin', auth()->user()->eiin)
                        ->where('branch_id', $request->branch_id)
                        ->where('version_id', $request->version_id)
                        ->where('shift_id', $request->shift_id)
                        ->where('class_id', $request->class_id)
                        ->where('session_year', $request->session_year)
                        ->where('deleted_at', null);
                })
            ],
        ], [
            'class_teacher_id.required' => 'অনুগ্রহ করে শ্রেণি শিক্ষক প্রদান করুন',
            'section_id.required' => 'অনুগ্রহ করে সেকশন প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন',
            'class_id.required' => 'অনুগ্রহ করে শ্রেণি প্রদান করুন',
            'session_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন',
            'section_id.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না',
        ]);

        try {
            $this->classRoomService->updateClassRoom($uid, $request->all());
            $notification = array(
                'message' => 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.classroom.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardClassRoomDelete(Request $request)
    {
        $student_list = StudentClassInfo::where('class_room_uid', $request->id)->get();
        // $subjectTeacher_list = SubjectTeacher::where('class_room_id', $request->id)->whereNotNull('teacher_id')->get();

        $related_items['student_items'] = $student_list;
        // $related_items['subject_teachers'] = $subjectTeacher_list;

        $message = '';

        if (count($related_items['student_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই সেকশন এর অধীনে ' . count($related_items['student_items']) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।</p>';
        }

        // if (count($related_items['subject_teachers']) > 0) {
        //     $message  .= '<p>ইতিমধ্যে এই সেকশন এর অধীনে ' . count($related_items['subject_teachers']) . ' টি বিষয়ে বিষয় শিক্ষক এর তথ্য রয়েছে।</p>';
        // }

        if ((count($related_items['student_items']) > 0)) {
            $message  .= '<p>অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }
        // $pi_evaluation_exists = PiEvaluation::on('db_evaluation')->where('class_room_uid', $request->id)->first();
        // $bi_evaluation_exists = BiEvaluation::on('db_evaluation')->where('class_room_uid', $request->id)->first();

        // $related_items['pi_evaluation_exists'] = $pi_evaluation_exists;
        // $related_items['bi_evaluation_exists'] = $bi_evaluation_exists;

        // $message = '';
        // if ($pi_evaluation_exists) {
        //     $message  .= '<p>ইতিমধ্যে এই বিষয় শিক্ষকের এর অধীনে PI মূল্যায়নের এর তথ্য রয়েছে।</p>';
        // }

        // if ($bi_evaluation_exists) {
        //     $message  .= '<p>ইতিমধ্যে এই বিষয় শিক্ষকের এর অধীনে BI মূল্যায়নের এর তথ্য রয়েছে।</p>';
        // }
        $this->classRoomService->deleteClassRoom($request->id);
        return response()->json(['status' => 'success', 'message' => 'তথ্যটি মুছে ফেলা হয়েছে।']);
    }
    // ClassRoom End

    // Branch Start
    public function noipunnoDashboardBranchAdd()
    {
        $eiinId = auth()->user()->eiin;
        // $branchList = Branch::with(['branchHead'])->select('*')->paginate(5);
        $branchList = $this->branchService->getByEiinId($eiinId);
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1, 1);

        return view('frontend/noipunno/branch/index', compact('branchList', 'myTeachers', 'eiinId'));
    }

    public function noipunnoDashboardBranchStore(Request $request)
    {
        $this->validate(request(), [
            'branch_location' => 'required|string',
            'branch_name' => [
                'required',
                Rule::unique('branches')
                    ->where('eiin', auth()->user()->eiin)
                    ->whereNull('deleted_at')
            ],
            'head_of_branch_id' => 'required',
            // 'head_of_branch_id' => [
            //     'required',
            //     Rule::unique('branches')
            //         ->where('head_of_branch_id', $request->head_of_branch_id)
            //         ->where('eiin', auth()->user()->eiin)
            //         ->whereNull('deleted_at')
            // ],
        ], [
            'branch_location.required' => 'অনুগ্রহ করে ব্রাঞ্চ ঠিকানা প্রদান করুন',
            'branch_name.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন',
            'branch_name.unique' => 'একই নামের ব্রাঞ্চ একই স্কুল এ দেয়া যাবেনা',
            'head_of_branch_id.required' => 'অনুগ্রহ করে প্রধান শিক্ষক এর নাম প্রদান করুন',
            // 'head_of_branch_id.unique' => 'একই প্রধান শিক্ষক একাধিক ব্রাঞ্চ প্রধান হতে পারে না',
        ]);

        // $this->validate(request(), [
        //     'head_of_branch_id' => [
        //         'required',
        //         Rule::unique('branches')
        //             ->where('head_of_branch_id', $request->head_of_branch_id)
        //             ->where('eiin', auth()->user()->eiin)
        //     ],
        // ], [
        //     'head_of_branch_id.required' => 'অনুগ্রহ করে প্রধান শিক্ষক এর নাম প্রদান করুন',
        //     'head_of_branch_id.unique' => 'একই প্রধান শিক্ষক একাধিক ব্রাঞ্চ প্রধান হতে পারে না',
        // ]);

        // $this->validate(request(), [
        //     'branch_location' => 'required|string',
        //     'branch_name' => [
        //         'required',
        //         Rule::unique('branches')
        //             ->where('eiin', auth()->user()->eiin)
        //     ],
        //     'head_of_branch_id' => [
        //         'required',
        //         Rule::unique('branches')
        //             ->where('branch_name', $request->branch_name)
        //             ->where('head_of_branch_id', $request->head_of_branch_id)
        //             ->where('eiin', auth()->user()->eiin)
        //     ],
        // ], [
        //     'branch_location.required' => 'অনুগ্রহ করে ব্রাঞ্চ ঠিকানা প্রদান করুন',
        //     'branch_name.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন',
        //     'branch_name.unique' => 'একই নামের ব্রাঞ্চ একই স্কুল এ দেয়া যাবেনা',
        //     'head_of_branch_id.required' => 'অনুগ্রহ করে প্রধান শিক্ষক এর নাম প্রদান করুন',
        //     'head_of_branch_id.unique' => 'একই প্রধান শিক্ষক একাধিক ব্রাঞ্চ প্রধান হতে পারে না',
        // ]);

        try {
            Branch::create([
                'branch_id' => $request->branch_id,
                'branch_name' => $request->branch_name,
                'branch_location' => $request->branch_location,
                'head_of_branch_id' => $request->head_of_branch_id,
                'eiin' => auth()->user()->eiin,
            ]);

            $notification = array(
                'message' => 'ব্রাঞ্চ সফলভাবে তৈরি করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.branch.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardBranchUpdate(Request $request)
    {
        $branch = Branch::where('uid', $request->id)->first();

        // $validation_rules = [];

        // if ($branch->branch_name != $request->branch_name) {
        //     $validation_rules['branch_name'] = [
        //         'required',
        //         Rule::unique('branches')
        //             ->where('eiin', auth()->user()->eiin)
        //     ];
        // }

        // if ($branch->head_of_branch_id != $request->head_of_branch_id) {
        //     $validation_rules['head_of_branch_id'] = [
        //         'required',
        //         Rule::unique('branches')
        //             ->where('head_of_branch_id', $request->head_of_branch_id)
        //             ->where('eiin', auth()->user()->eiin)
        //     ];
        // }
        $validation_rules['branch_name'] = [
            'required',
            Rule::unique('branches')->where(function ($query) use ($request) {
                return $query
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_name', $request->branch_name)
                    ->where('uid', '!=', $request->id)
                    ->whereNull('deleted_at');
            })
        ];
        $validation_rules['head_of_branch_id'] = 'required';
        // $validation_rules['head_of_branch_id'] = [
        //     'required',
        //     Rule::unique('branches')->where(function ($query) use ($request) {
        //         return $query
        //             ->where('eiin', auth()->user()->eiin)
        //             ->where('head_of_branch_id', $request->head_of_branch_id)
        //             ->where('uid', '!=', $request->id)
        //             ->whereNull('deleted_at');
        //     })
        // ];
        if ($request->branch_location == null) {
            $validation_rules['branch_location'] = 'required|string';
        }

        $this->validate(request(), $validation_rules, [
            'branch_location.required' => 'অনুগ্রহ করে ব্রাঞ্চ ঠিকানা প্রদান করুন',
            'branch_name.required' => 'অনুগ্রহ করে ব্রাঞ্চ নাম প্রদান করুন',
            'branch_name.unique' => 'একই নামের ব্রাঞ্চ একই স্কুল এ দেয়া যাবেনা',
            'head_of_branch_id.required' => 'অনুগ্রহ করে প্রধান শিক্ষক এর নাম প্রদান করুন',
            // 'head_of_branch_id.unique' => 'একই প্রধান শিক্ষক একাধিক ব্রাঞ্চ প্রধান হতে পারে না',
        ]);
        try {
            $branch->update([
                'branch_id' => $request->branch_id,
                'branch_name' => $request->branch_name,
                'branch_location' => $request->branch_location,
                'head_of_branch_id' => $request->head_of_branch_id,
                'eiin' => auth()->user()->eiin,
            ]);

            $branch->save();

            $notification = array(
                'message' => 'ব্রাঞ্চ সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.branch.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardBranchEdit(Request $request)
    {
        $branchData = Branch::on('db_read')->where('uid', $request->id)->get()->first();
        $eiinId = auth()->user()->eiin;
        $branchList = $this->branchService->getByEiinId($eiinId);
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1, 1);

        return view('frontend/noipunno/branch/edit', compact('branchData', 'branchList', 'eiinId', 'myTeachers'));
    }

    public function noipunnoDashboardBranchDelete(Request $request)
    {
        $related_items = [];
        $related_items = $this->getRelatedItemsForBranchRemove($related_items, $request);

        $message = '';
        if (count($related_items['version_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . count($related_items['version_items']) . ' টি ভার্সন এর তথ্য রয়েছে।</p>';
        }
        if (count($related_items['shift_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . count($related_items['shift_items']) . ' টি শিফট এর তথ্য রয়েছে।</p>';
        }
        if (count($related_items['section_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . count($related_items['section_items']) . ' টি সেকশন এর তথ্য রয়েছে।</p>';
        }
        if (count($related_items['student_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . count($related_items['student_items']) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . count($related_items['subject_teachers']) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।</p>';
        }

        if ((count($related_items['version_items']) > 0) || (count($related_items['shift_items']) > 0) || (count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            $message  .= '<p>অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        Branch::where('uid', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'ব্রাঞ্চ এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    private function getRelatedItemsForBranchRemove($related_items, Request $request)
    {
        $related_version_list = Version::where('eiin', auth()->user()->eiin)->where('branch_id', $request->id)->get();
        $related_shift_list = Shift::where('eiin', auth()->user()->eiin)->where('branch_id', $request->id)->get();
        $related_section_list = Section::where('eiin', auth()->user()->eiin)->where('branch_id', $request->id)->get();
        $related_student_list = Student::where('eiin', auth()->user()->eiin)->where('branch', $request->id)->get();
        $related_subject_teacher_list = ClassRoom::where('eiin', auth()->user()->eiin)->where('branch_id', $request->id)->get();

        $related_items['version_items'] = $related_version_list;
        $related_items['shift_items'] = $related_shift_list;
        $related_items['section_items'] = $related_section_list;
        $related_items['student_items'] = $related_student_list;
        $related_items['subject_teachers'] = $related_subject_teacher_list;

        return $related_items;
    }
    // Branch End

    // Version Start
    public function noipunnoDashboardVersionAdd()
    {
        $eiinId = auth()->user()->eiin;
        $versionList = $this->versionService->getByEiinId($eiinId);
        $myBranches = $this->branchService->getByEiinId($eiinId, 1);
        // $versionList = $this->versionService->getByEiinIdWithPagination($eiinId);

        return view('frontend/noipunno/version/index', compact('versionList', 'myBranches', 'eiinId'));
    }

    public function noipunnoDashboardVersionStore(Request $request)
    {
        $eiinId = auth()->user()->eiin;

        $this->validate(request(), [
            'branch_id' => 'required',
            'version_name' => [
                'required',
                Rule::unique('versions')
                    ->where(function ($query) use ($request) {
                        return $query->where('branch_id', $request->branch_id)
                            ->whereNull('deleted_at')
                            ->where('eiin',  auth()->user()->eiin);
                    })
            ],
        ], [
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন',
            'version_name.required' => 'অনুগ্রহ করে ভার্সন নাম প্রদান করুন',
            'version_name.unique' => 'একই ভার্সন একাধিকবার দেয়া সম্ভব না',
        ]);

        try {
            Version::create([
                'branch_id' => $request->branch_id,
                'version_name' => $request->version_name,
                'eiin' => $eiinId,
            ]);

            $notification = array(
                'message' => 'ভার্সন সফলভাবে তৈরি করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.version.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardVersionUpdate(Request $request)
    {

        $eiinId = auth()->user()->eiin;
        $version = Version::where('uid', $request->id)->first();

        if ($version->version_name == $request->version_name && $version->branch_id == $request->branch_id) {
            $notification = array(
                'message' => 'ভার্সন সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.version.add')->with($notification);
        }

        $this->validate(request(), [
            'branch_id' => 'required',
            'version_name' => [
                'required',
                Rule::unique('versions')
                    ->whereNull('deleted_at')
                    ->where('branch_id', $request->branch_id)
                    ->where('eiin', auth()->user()->eiin)
            ],
        ], [
            'version_name.required' => 'অনুগ্রহ করে ভার্সন নাম প্রদান করুন',
            'version_name.unique' => 'একই ভার্সন একাধিকবার দেয়া সম্ভব না',
        ]);

        try {
            $version = Version::where('uid', $request->id)->first();
            $eiinId = auth()->user()->eiin;

            $version->update([
                'branch_id' => $request->branch_id,
                'version_id' => $request->version_id,
                'version_name' => $request->version_name,
                'eiin' => $eiinId,
            ]);

            $version->save();

            $notification = array(
                'message' => 'ভার্সন সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.version.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardVersionEdit(Request $request)
    {
        $versionData = Version::on('db_read')->where('uid', $request->id)->first();
        $eiinId = auth()->user()->eiin;

        $versionList = $this->versionService->getByEiinId($eiinId);
        $myBranches = $this->branchService->getByEiinId($eiinId, 1);

        return view('frontend/noipunno/version/edit', compact('versionData', 'versionList', 'myBranches'));
    }

    public function noipunnoDashboardVersionDelete(Request $request)
    {
        $eiin = auth()->user()->eiin;

        $student_list = Student::where('eiin', $eiin)->where('version', $request->id)->get();
        $section_list = Section::where('eiin', $eiin)->where('version_id', $request->id)->get();
        $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('version_id', $request->id)->get();

        $related_items['section_items'] = $section_list;
        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        $message = '';
        if (count($related_items['section_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ভার্সন এর অধীনে ' . count($related_items['section_items']) . ' টি সেকশন এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['student_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ভার্সন এর অধীনে ' . count($related_items['student_items']) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই ভার্সন এর অধীনে ' . count($related_items['subject_teachers']) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।</p>';
        }

        if ((count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            $message  .= '<p>অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        Version::where('uid', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'ভার্সন এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
    // Version End

    // Shift Start
    public function noipunnoDashboardShiftAdd()
    {
        $eiinId = auth()->user()->eiin;
        $branchList = $this->branchService->getByEiinId($eiinId, 1);
        $shiftList = $this->shiftService->getByEiinId($eiinId);
        // $shiftList = $this->shiftService->getByEiinIdWithPagination($eiinId);

        return view('frontend/noipunno/shift/index', compact('branchList', 'shiftList', 'eiinId'));
    }

    public function noipunnoDashboardShiftStore(Request $request)
    {
        $this->validate(request(), [
            'shift_details' => 'required',
            'branch_id' => 'required',
            'shift_name' => [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_id', $request->branch_id)
                    ->whereNull('deleted_at'),
            ],
            'shift_start_time' => [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_id', $request->branch_id)
                    ->whereNull('deleted_at')
            ],
            'shift_end_time' => [
                'required',
                'date_format:H:i',
                'after:shift_start_time',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_id', $request->branch_id)
                    ->whereNull('deleted_at')
            ],
        ], [
            'shift_details.required' => 'অনুগ্রহ করে শিফট এর বিস্তারিত তথ্য প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন',
            'shift_name.required' => 'অনুগ্রহ করে শিফট নাম প্রদান করুন',
            'shift_name.unique' => 'একই শিফট একাধিকবার দেয়া সম্ভব না',
            'shift_start_time.required' => 'অনুগ্রহ করে শিফট শুরু হওয়ার সময় প্রদান করুন',
            'shift_start_time.unique' => 'এই সময় স্লট আর খালি নেই',
            'shift_end_time.required' => 'অনুগ্রহ করে শেষ হওয়ার সময় প্রদান করুন',
            'shift_end_time.unique' => 'এই সময় স্লট আর খালি নেই',
            'shift_end_time.after' => 'শেষ হওয়ার সময় অবশ্যই শুরুর পরবর্তী সময় হতে হবে',
        ]);

        try {
            Shift::create([
                'shift_name' => $request->shift_name,
                'shift_details' => $request->shift_details,
                'branch_id' => $request->branch_id,
                'shift_start_time' => $request->shift_start_time,
                'shift_end_time' => $request->shift_end_time,
                'eiin' => auth()->user()->eiin,
            ]);

            $notification = array(
                'message' => 'শিফট সফলভাবে তৈরি করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.shift.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardShiftUpdate(Request $request)
    {
        $shift_exists = Shift::where('uid', $request->id)
            ->where('eiin', auth()->user()->eiin)
            ->first();

        $validation_rules = [];
        $validation_rules['branch_id'] = 'required';
        $validation_rules['shift_details'] = 'required';
        if ($shift_exists->shift_name != $request->shift_name) {
            $validation_rules['shift_name'] = [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('branch_id', $request->branch_id),
            ];
        }
        if ($shift_exists->shift_start_time != $request->shift_start_time) {
            $validation_rules['shift_start_time'] = [
                'required',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('shift_start_time', $request->shift_start_time)
                    ->where('branch_id', $request->branch_id)
            ];
        }
        if ($shift_exists->shift_end_time != $request->shift_end_time) {
            $validation_rules['shift_end_time'] = [
                'required',
                'date_format:H:i',
                'after:shift_start_time',
                Rule::unique('shifts')
                    ->where('eiin', auth()->user()->eiin)
                    ->where('shift_end_time', $request->shift_end_time)
                    ->where('branch_id', $request->branch_id),
            ];
        }

        $this->validate(request(), $validation_rules, [
            'shift_details.required' => 'অনুগ্রহ করে শিফট এর বিস্তারিত তথ্য প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন',
            'shift_name.required' => 'অনুগ্রহ করে শিফট নাম প্রদান করুন',
            'shift_name.unique' => 'একই শিফট একাধিকবার দেয়া সম্ভব না',
            'shift_start_time.required' => 'অনুগ্রহ করে শিফট শুরু হওয়ার সময় প্রদান করুন',
            'shift_start_time.unique' => 'এই সময় স্লট আর খালি নেই',
            'shift_end_time.required' => 'অনুগ্রহ করে শেষ হওয়ার সময় প্রদান করুন',
            'shift_end_time.unique' => 'এই সময় স্লট আর খালি নেই',
            'shift_end_time.after' => 'শেষ হওয়ার সময় অবশ্যই শুরুর পরবর্তী সময় হতে হবে',
        ]);

        try {
            $shift = Shift::where('uid', $request->id)->first();

            $shift->update([
                'shift_name' => $request->shift_name,
                'shift_details' => $request->shift_details,
                'branch_id' => $request->branch_id,
                'shift_start_time' => $request->shift_start_time,
                'shift_end_time' => $request->shift_end_time,
                'eiin' => auth()->user()->eiin,
            ]);

            $shift->save();

            $notification = array(
                'message' => 'শিফট সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.shift.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardShiftEdit(Request $request)
    {
        $shiftData = Shift::on('db_read')->where('uid', $request->id)->first();
        $eiinId = auth()->user()->eiin;

        $branchList = $this->branchService->getByEiinId($eiinId, 1);
        $shiftList = $this->shiftService->getByEiinId($eiinId);

        return view('frontend/noipunno/shift/edit', compact('shiftData', 'shiftList', 'branchList'));
    }

    public function noipunnoDashboardShiftDelete(Request $request)
    {
        $eiin = auth()->user()->eiin;

        $section_list = Section::where('eiin', $eiin)->where('shift_id', $request->id)->get();
        $student_list = Student::where('eiin', $eiin)->where('shift', $request->id)->get();
        $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('shift_id', $request->id)->get();

        $related_items['section_items'] = $section_list;
        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        $message = '';
        if (count($related_items['section_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই শিফট এর অধীনে ' . count($related_items['section_items']) . ' টি সেকশন এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['student_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই শিফট এর অধীনে ' . count($related_items['student_items']) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই শিফট এর অধীনে ' . count($related_items['subject_teachers']) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।</p>';
        }

        if ((count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            $message  .= '<p>অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        Shift::where('uid', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'শিফট এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
    // Shift End

    // Section Start
    public function noipunnoDashboardSectionAdd()
    {
        $eiinId = auth()->user()->eiin;
        $branchList = $this->branchService->getByEiinId($eiinId, 1);
        $versionList = $this->versionService->getByEiinId($eiinId, 1);
        $shiftList = $this->shiftService->getByEiinId($eiinId, 1);
        $sectionList = Section::on('db_read')->select('uid', 'section_name', 'section_year', 'class_id', 'shift_id', 'version_id', 'branch_id', 'section_details')
            ->with('branch', 'version', 'shift')->where('eiin', $eiinId)->orderBy('class_id', 'asc')->get();
        // $classList = $this->classService->getAll();

        return view('frontend/noipunno/section/index', compact('branchList', 'shiftList', 'versionList', 'sectionList', 'eiinId'));
    }

    public function noipunnoDashboardSectionStore(Request $request)
    {
        // $sectionExistsInTrash = Section::where('section_name', $request->section_name)
        //     ->where('eiin', auth()->user()->eiin)
        //     ->where('branch_id',  $request->branch_id)
        //     ->where('version_id',  $request->version_id)
        //     ->where('shift_id',  $request->shift_id)
        //     ->where('class_id',  $request->class_id)
        //     ->where('section_year',  $request->section_year)
        //     ->withTrashed()
        //     ->first();

        // if ($sectionExistsInTrash) {
        //     if (
        //         $sectionExistsInTrash->branch_id != $request->branch_id
        //         || $sectionExistsInTrash->version_id != $request->version_id
        //         || $sectionExistsInTrash->shift_id != $request->shift_id
        //         || $sectionExistsInTrash->class_id != $request->class_id
        //         || $sectionExistsInTrash->section_year != $request->section_year
        //     ) {
        //         $this->validate(request(), [
        //             'section_name' => [
        //                 'required',
        //                 Rule::unique('sections')
        //                     ->where('class_id', $request->class_id)
        //                     ->where('section_year', $request->section_year)
        //                     ->where('branch_id', $request->branch_id)
        //                     ->where('version_id', $request->version_id)
        //                     ->where('shift_id', $request->shift_id)
        //                     ->where('eiin', auth()->user()->eiin)
        //                     ->where('deleted_at', null)
        //             ],
        //             'section_year' => 'required',
        //             'class_id' => 'required',
        //         ], [
        //             'section_name.required' => 'অনুগ্রহ করে সেকশন নাম প্রদান করুন',
        //             'section_name.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না',
        //             'section_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন',
        //             'class_id.required' => 'অনুগ্রহ করে ক্লাস প্রদান করুন',
        //         ]);
        //     }

        //     $sectionExistsInTrash->update([
        //         'section_name' => $request->section_name,
        //         'section_details' => $request->section_details,
        //         'section_year' => $request->section_year,
        //         'class_id' => $request->class_id,
        //         'shift_id' => $request->shift_id,
        //         'version_id' => $request->version_id,
        //         'branch_id' => $request->branch_id,
        //         'eiin' => auth()->user()->eiin,
        //         'deleted_at' => null
        //     ]);
        //     $sectionExistsInTrash->save();

        //     $notification = array(
        //         'message' => 'Section Created successfully.',
        //         'alert-type' => 'success'
        //     );
        //     return redirect()->route('noipunno.dashboard.section.add')->with($notification);
        // }

        $this->validate(request(), [

            'section_name' => [
                'required',
                Rule::unique('sections')
                    ->where('class_id', $request->class_id)
                    ->where('section_year', $request->section_year)
                    ->where('branch_id', $request->branch_id)
                    ->where('version_id', $request->version_id)
                    ->where('shift_id', $request->shift_id)
                    ->where('eiin', auth()->user()->eiin)
                    ->where('deleted_at', null)
            ],
            'section_year' => 'required',
            'class_id' => 'required',
            'branch_id' => 'required',
            'version_id' => 'required',
            'shift_id' => 'required',

        ], [
            'section_name.required' => 'অনুগ্রহ করে সেকশন নাম প্রদান করুন',
            'section_name.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না',
            'section_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন',
            'class_id.required' => 'অনুগ্রহ করে ক্লাস প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন',
        ]);

        try {
            Section::create([
                'section_name' => $request->section_name,
                'section_details' => $request->section_details,
                'section_year' => $request->section_year,
                'class_id' => $request->class_id,
                'shift_id' => $request->shift_id,
                'version_id' => $request->version_id,
                'branch_id' => $request->branch_id,
                'eiin' => auth()->user()->eiin,
            ]);
            $notification = array(
                'message' => 'সেকশন সফলভাবে তৈরি করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.section.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardSectionUpdate(Request $request)
    {

        $this->validate(request(), [
            'section_name' => [
                'required',
                Rule::unique('sections')->where(function ($query) use ($request) {
                    return $query
                        ->where('eiin', auth()->user()->eiin)
                        ->where('branch_id', $request->branch_id)
                        ->where('version_id', $request->version_id)
                        ->where('shift_id', $request->shift_id)
                        ->where('class_id', $request->class_id)
                        ->where('section_year', $request->section_year)
                        ->where('uid', '!=', $request->id)
                        ->where('section_name', $request->section_name)
                        ->whereNull('deleted_at');
                })
            ],
            'class_id' => 'required',
            'branch_id' => 'required',
            'version_id' => 'required',
            'shift_id' => 'required',
        ], [
            'section_name.required' => 'অনুগ্রহ করে সেকশন নাম প্রদান করুন',
            'section_name.unique' => 'একই সেকশন একাধিকবার দেয়া সম্ভব না',
            // 'section_year.required' => 'অনুগ্রহ করে শিক্ষাবর্ষ প্রদান করুন',
            'class_id.required' => 'অনুগ্রহ করে ক্লাস প্রদান করুন',
            'branch_id.required' => 'অনুগ্রহ করে ব্রাঞ্চ প্রদান করুন',
            'version_id.required' => 'অনুগ্রহ করে ভার্সন প্রদান করুন',
            'shift_id.required' => 'অনুগ্রহ করে শিফট প্রদান করুন',
        ]);
        // }
        try {
            $section = Section::where('uid', $request->id)->first();

            $section->update([
                'section_name' => $request->section_name,
                'section_details' => $request->section_details,
                'section_year' => $request->section_year,
                'class_id' => $request->class_id,
                'shift_id' => $request->shift_id,
                'version_id' => $request->version_id,
                'branch_id' => $request->branch_id,
                'eiin' => auth()->user()->eiin,
            ]);

            $section->save();

            $notification = array(
                'message' => 'সেকশন সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return redirect()->route('noipunno.dashboard.section.add')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function noipunnoDashboardSectionDelete(Request $request)
    {
        $related_items = [];
        $related_items = $this->getRelatedItemsForSectionRemove($related_items, $request);

        $message = '';

        if (count($related_items['student_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই সেকশন এর অধীনে ' . count($related_items['student_items']) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।</p>';
        }

        if (count($related_items['classroom_items']) > 0) {
            $message  .= '<p>ইতিমধ্যে এই সেকশন এর অধীনে বিষয় শিক্ষক এর তথ্য রয়েছে।</p>';
        }

        if ((count($related_items['student_items']) > 0) || (count($related_items['classroom_items']) > 0)) {
            $message  .= '<p>অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        Section::where('uid', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'সেকশন এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    private function getRelatedItemsForSectionRemove($related_items, Request $request)
    {
        $related_classroom_list = ClassRoom::where('eiin', auth()->user()->eiin)->where('section_id', $request->id)->get();
        $related_student_list = Student::where('eiin', auth()->user()->eiin)->where('section', $request->id)->get();

        $related_items['classroom_items'] = $related_classroom_list;
        $related_items['student_items'] = $related_student_list;

        return $related_items;
    }

    public function noipunnoDashboardSectionEdit(Request $request)
    {
        $eiinId = auth()->user()->eiin;
        $sectionData = Section::on('db_read')->where('uid', $request->id)->get()->first();
        $branchList = $this->branchService->getByEiinId($eiinId, 1);
        $versionList = $this->versionService->getByEiinId($eiinId, 1);
        $shiftList = $this->shiftService->getByEiinId($eiinId, 1);
        $sectionList = Section::on('db_read')->select('uid', 'section_name', 'section_year', 'class_id', 'shift_id', 'version_id', 'branch_id', 'section_details')
            ->with('branch', 'version', 'shift')->where('eiin', $eiinId)->orderBy('class_id', 'asc')->get();

        return view('frontend/noipunno/section/edit', compact('sectionData', 'branchList', 'versionList', 'shiftList', 'sectionList'));
    }
    // Section End

    public function noipunnoDashboardSessionAdd()
    {
        return view('frontend/noipunno/session/index');
    }

    public function noipunnoDashboardSessionEdit()
    {
        return view('frontend/noipunno/session/edit');
    }

    public function noipunnoDashboardComponents()
    {
        return view('frontend/noipunno/style-components/index');
    }
    public function noipunnoDashboardStudentsReport()
    {
        return view('frontend/noipunno/students-report/index');
    }

    // Wise start
    public function instituteWiseBranch(Request $request)
    {
        $data['branches'] = Branch::on('db_read')->select('uid', 'branch_name')->where('eiin', $request->eiin)->get();

        return response()->json($data);
    }

    public function branchWiseVersion(Request $request)
    {
        $data['versions'] = Version::on('db_read')->select('uid', 'version_name')->where('eiin', $request->eiin)->where('branch_id', $request->branch_id)->get();
        $data['shifts'] = Shift::on('db_read')->select('uid', 'shift_name')->where('eiin', $request->eiin)->where('branch_id', $request->branch_id)->get();

        // $data['versions'] = Version::on('db_read')->where('eiin', $request->eiin)->where('branch_id', $request->branch_id)->get();
        // $data['shifts'] = Shift::on('db_read')->where('eiin', $request->eiin)->where('branch_id', $request->branch_id)->get();

        return response()->json($data);
    }
    public function classWiseSubject(Request $request)
    {
        $data['subjects'] = $this->subjectService->getAll($request);
        return response()->json($data);
    }
    public function classWiseSection(Request $request)
    {
        $data['sections'] = Section::on('db_read')->select('uid', 'section_name')
            ->where('eiin', $request->eiin)
            ->where('branch_id', $request->branch_id)
            ->where('class_id', $request->class_id)
            ->where('shift_id', $request->shift_id)
            ->where('version_id', $request->version_id)
            ->get();

        return response()->json($data);
    }

    public function sectionWiseYear(Request $request)
    {
        $data['section'] = Section::on('db_read')->select('uid', 'section_year')->where('uid', $request->section_id)->first();
        return response()->json($data);
    }

    public function divisionWiseDistrict(Request $request)
    {
        $data['districts'] = District::on('db_read')->select('uid', 'district_name_bn', 'district_name_en')
            ->where('division_id', $request->division_id)
            ->get();

        return response()->json($data);
    }
    public function districtWiseUpazila(Request $request)
    {
        $data['upazilas'] = Upazilla::on('db_read')->select('uid', 'upazila_name_bn', 'upazila_name_en')
            ->where('district_id', $request->district_id)
            ->get();

        return response()->json($data);
    }

    public function upazilaWiseEiinInstitute(Request $request)
    {
        if ($request->has_eiin == 1) {
            $data['institutes'] = Institute::on('db_read')
                ->select('uid', 'eiin', 'institute_name_bn', 'institute_name')
                ->where('upazila_uid', $request->upazila_id)
                ->where('has_eiin', 1)
                ->get();
        } else {
            $data['institutes'] = Institute::on('db_read')
                ->select('uid', 'eiin', 'institute_name_bn', 'institute_name')
                ->where('upazila_uid', $request->upazila_id)
                ->get();
        }

        return response()->json($data);
    }
    // Wise End

    //PiBi Approve Start
    public function changePiBiApproveStatusSubjectWise($uid, Request $request)
    {
        $submitStatus = (int) $request->submit_status;
        $review = PiBiReview::where('uid', $uid)->first();

        $model_pi = $this->piEvaluationService->getModel($review->evaluate_type, $review['class_room']['class_id']);
        $model_bi = $this->biEvaluationService->getModel($review->evaluate_type, $review['class_room']['class_id']);

        // $pi_evaluations = DB::connection('db_evaluation')->table('vw_pi_evolation')->select('uid', 'teacher_uid', 'class_room_uid')
        $pi_evaluations = $model_pi::on('db_evaluation')->select('uid', 'teacher_uid', 'class_room_uid')
            ->where('teacher_uid', $review->teacher_uid)
            ->where('subject_uid', $review->subject_uid)
            ->where('class_room_uid', $review->class_room_uid)
            ->get();
        $bi_evaluations = $model_bi::on('db_evaluation')->select('uid', 'teacher_uid', 'class_room_uid')
            ->where('teacher_uid', $review->teacher_uid)
            ->where('subject_uid', $review->subject_uid)
            ->where('class_room_uid', $review->class_room_uid)
            ->get();


        if ($submitStatus === 1) {
            foreach ($pi_evaluations as $item) {
                $pi = $model_pi::on('db_evaluation')->where('uid', $item->uid)->first();
                $pi->submit_status = 1;
                $pi->save();
            }
            foreach ($bi_evaluations as $item) {
                $bi = $model_bi::on('db_evaluation')->where('uid', $item->uid)->first();
                $bi->submit_status = 1;
                $bi->save();
            }
            $review->is_approved = 1;
        } else {
            foreach ($pi_evaluations as $item) {
                $pi = $model_pi::on('db_evaluation')->where('uid', $item->uid)->first();
                $pi->submit_status = 2;
                $pi->save();
            }
            foreach ($bi_evaluations as $item) {
                $bi = $model_bi::on('db_evaluation')->where('uid', $item->uid)->first();
                $bi->submit_status = 2;
                $bi->save();
            }
            $review->is_approved = 2;
        }

        $review->save();
        return redirect()->back();
    }

    public function changePiBiApproveStatusOld($uid, Request $request)
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

        return redirect()->back();
    }

    public function changePiBiApproveStatus($uid, Request $request)
    {
        $submitStatus = (int) $request->submit_status;

        $review = PiReview::where('uid', $uid)->first();
        if (!$review) {
            $review = BiReview::where('uid', $uid)->first();
        }

        $model_pi = $this->piEvaluationService->getModel($review->evaluate_type, $review['class_room']['class_id']);
        // dd($model_pi);
        if ($review) {
            $evaluations = $model_pi::on('db_evaluation')->where('evaluate_type', $review->evaluate_type)
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

        return redirect()->back();
    }


    public function changePiBiApproveAll()
    {
        $user = auth()->user();
        $eiinId = auth()->user()->eiin;


        $pi_review = PiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
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
            // ->where('is_approved', 0)
            ->where('session', date('Y'))
            ->get();
        $reviews = array_merge($pi_review->toArray(), $bi_review->toArray());

        $subject_reviews = PiBiReview::with(['teacher', 'class_room', 'class_room.section', 'class_room.branch', 'class_room.shift', 'class_room.version'])
            ->whereHas('teacher', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereHas('class_room', function ($query) {
                $query->whereNull('deleted_at');
            })
            // ->where('is_approved', 0)
            ->where('session', date('Y'))
            ->get();

        foreach ($subject_reviews as $key => $review) {
            $subject_reviews[$key]['subject'] = $this->subjectService->getSubjectInfo($review->subject_uid);
        }

        return view('frontend/noipunno/approve/index', compact('reviews', 'subject_reviews'));
    }
    //PiBi Approve End

    public function stats(Request $request)
    {
        //         $thanas = [
//             ['upazila_name_bn' => 'ঢাকা কোতয়ালী থানা', 'upazila_name_en' => 'Dhaka Kotwali Thana'],
//             ['upazila_name_bn' => 'কামরাঙ্গীরচর থানা', 'upazila_name_en' => 'Kamrangirchar Thana'],
//             ['upazila_name_bn' => 'চকবাজার থানা', 'upazila_name_en' => 'Chawkbazar Thana'],
//             ['upazila_name_bn' => 'তেজগাঁও থানা', 'upazila_name_en' => 'Tejgaon Thana'],
//             ['upazila_name_bn' => 'তেজগাঁও শিল্পাঞ্চল থানা', 'upazila_name_en' => 'Tejgaon Industrial Area Thana'],
//             ['upazila_name_bn' => 'শাহআলী থানা', 'upazila_name_en' => 'Shah Ali Thana'],
//             ['upazila_name_bn' => 'পল্লবী থানা', 'upazila_name_en' => 'Pallabi Thana'],
//             ['upazila_name_bn' => 'বাড্ডা থানা', 'upazila_name_en' => 'Badda Thana'],
//             ['upazila_name_bn' => 'ক্যান্টনমেন্ট থানা', 'upazila_name_en' => 'Cantonment Thana'],
//             ['upazila_name_bn' => 'উত্তরা মডেল থানা', 'upazila_name_en' => 'Uttara Model Thana'],
//             ['upazila_name_bn' => 'তুরাগ থানা', 'upazila_name_en' => 'Turag Thana'],
//             ['upazila_name_bn' => 'উত্তরখান থানা', 'upazila_name_en' => 'Uttarkhan Thana'],
//             ['upazila_name_bn' => 'দক্ষিণখান থানা', 'upazila_name_en' => 'Dakshinkhan Thana'],
//             ['upazila_name_bn' => 'দারুস সালাম থানা', 'upazila_name_en' => 'Darus Salam Thana'],
//             ['upazila_name_bn' => 'মিরপুর মডেল থানা', 'upazila_name_en' => 'Mirpur Model Thana'],
//             ['upazila_name_bn' => 'শেরেবাংলা নগর থানা', 'upazila_name_en' => 'Sher-e-Bangla Nagar Thana'],
//             ['upazila_name_bn' => 'শাহজাহানপুর থানা', 'upazila_name_en' => 'Shahjahanpur Thana'],
//             ['upazila_name_bn' => 'ওয়ারী থানা', 'upazila_name_en' => 'Wari Thana'],
//             ['upazila_name_bn' => 'বনানী থানা', 'upazila_name_en' => 'Banani Thana'],
//             ['upazila_name_bn' => 'ভাটারা থানা', 'upazila_name_en' => 'Vatara Thana'],
//             ['upazila_name_bn' => 'ভাষানটেক থানা', 'upazila_name_en' => 'Bhashantek Thana'],
//             ['upazila_name_bn' => 'রূপনগর থানা', 'upazila_name_en' => 'Rupnagar Thana'],
//             ['upazila_name_bn' => 'মুগদা থানা', 'upazila_name_en' => 'Mugda Thana'],
//             ['upazila_name_bn' => 'উত্তরা পশ্চিম থানা', 'upazila_name_en' => 'Uttara West Thana'],
//             ['upazila_name_bn' => 'গুলশান থানা', 'upazila_name_en' => 'Gulshan Thana'],
//             ['upazila_name_bn' => 'বিমানবন্দর থানা', 'upazila_name_en' => 'Airport Thana'],
//             ['upazila_name_bn' => 'যাত্রাবাড়ী থানা', 'upazila_name_en' => 'Jatrabari Thana'],
//             ['upazila_name_bn' => 'সূত্রাপুর থানা', 'upazila_name_en' => 'Sutrapur Thana'],
//             ['upazila_name_bn' => 'মোহাম্মদপুর থানা', 'upazila_name_en' => 'Mohammadpur Thana'],
//             ['upazila_name_bn' => 'ধানমন্ডি থানা', 'upazila_name_en' => 'Dhanmondi Thana'],
//             ['upazila_name_bn' => 'কাফরুল থানা', 'upazila_name_en' => 'Kafrul Thana'],
//             ['upazila_name_bn' => 'খিলক্ষেত থানা', 'upazila_name_en' => 'Khilkhet Thana'],
//             ['upazila_name_bn' => 'আদাবর থানা', 'upazila_name_en' => 'Adabor Thana'],
//             ['upazila_name_bn' => 'রামপুরা থানা', 'upazila_name_en' => 'Rampura Thana'],
//             ['upazila_name_bn' => 'সবুজবাগ থানা', 'upazila_name_en' => 'Sabujbagh Thana'],
//             ['upazila_name_bn' => 'কদমতলী থানা', 'upazila_name_en' => 'Kadamtali Thana'],
//             ['upazila_name_bn' => 'গেন্ডারিয়া থানা', 'upazila_name_en' => 'Gendaria Thana'],
//             ['upazila_name_bn' => 'শ্যামপুর থানা', 'upazila_name_en' => 'Shyampur Thana'],
//             ['upazila_name_bn' => 'নিউমার্কেট থানা', 'upazila_name_en' => 'New Market Thana'],
//             ['upazila_name_bn' => 'বংশাল থানা', 'upazila_name_en' => 'Bangshal Thana'],
//             ['upazila_name_bn' => 'পল্টন মডেল থানা', 'upazila_name_en' => 'Paltan Model Thana'],
//             ['upazila_name_bn' => 'ডেমরা থানা', 'upazila_name_en' => 'Demra Thana'],
//             ['upazila_name_bn' => 'রমনা মডেল থানা', 'upazila_name_en' => 'Ramna Model Thana'],
//             ['upazila_name_bn' => 'হাজারীবাগ থানা', 'upazila_name_en' => 'Hazaribagh Thana'],
//             ['upazila_name_bn' => 'খিলগাঁও থানা', 'upazila_name_en' => 'Khilgaon Thana'],
//             ['upazila_name_bn' => 'মতিঝিল থানা', 'upazila_name_en' => 'Motijheel Thana'],
//             ['upazila_name_bn' => 'শাহবাগ থানা', 'upazila_name_en' => 'Shahbagh Thana'],
//             ['upazila_name_bn' => 'কলাবাগান থানা', 'upazila_name_en' => 'Kalabagan Thana'],
//             ['upazila_name_bn' => 'লালবাগ থানা', 'upazila_name_en' => 'Lalbagh Thana'],
//         ];
    
//         foreach ($thanas as $thana) {
//             DB::table('upazilas')->updateOrInsert(
//                 ['upazila_name_en' => $thana['upazila_name_en']], // Check duplicate
//                 [
//                     'upazila_name_bn' => $thana['upazila_name_bn'],
//                     'uid' => hexdec(uniqid()),
//                     'district_id' => 1,
//                     'upazila_id' => null,
//                     'created_by' => 3110479,
//                     'updated_by' => 3110479,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]
//             );
//         };

// return 'success';
        ini_set('max_execution_time', 3600);
        if ($request->q == 321) {
            $data['total_teacher_count'] = Teacher::count();
            $data['today_teacher_count'] = Teacher::whereDate('created_at', now()->toDateString())->count();
            $data['total_student_count'] = Student::count();
            $data['today_student_count'] = Student::whereDate('created_at', now()->toDateString())->count();
            $data['total_institute_count'] = Institute::count();
            $data['today_institute_count'] = Institute::whereDate('created_at', now()->toDateString())->count();
            $data['has_eiin_institute_count'] = Institute::where('has_eiin', 1)->count();
            $data['no_eiin_institute_count'] = Institute::where('has_eiin', 0)->count();
            $data['school_institute_count'] = Institute::where('category', 'School')->count();
            $data['college_institute_count'] = Institute::where('category', 'College')->count();
            $data['school_college_institute_count'] = Institute::where('category', 'School and College')->count();
            $data['madrasah_institute_count'] = Institute::where('category', 'Madrasah')->count();
            $data['primary_institute_count'] = Institute::where('category', 'Primary')->count();
            $data['technical_institute_count'] = Institute::where('category', 'Technical')->count();

            $data['total_pi_submission'] = PiEvaluation::on('db_evaluation')->count();
            $data['today_pi_submission'] = PiEvaluation::on('db_evaluation')->whereBetween('created_at', [now()->subDay(), now()])->count();

            $data['total_bi_submission'] = BiEvaluation::on('db_evaluation')->count();
            $data['today_bi_submission'] = BiEvaluation::on('db_evaluation')->whereBetween('created_at', [now()->subDay(), now()])->count();

            return view('frontend.noipunno.stats.index', $data);
        }
    }
}
