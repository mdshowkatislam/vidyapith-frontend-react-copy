<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ClassEnum;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Models\StudentHistory;
use App\Models\StudentTransfer;
use App\Services\InstituteService;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class StudentTransferController extends Controller
{
    private $studentService;
    private $instituteService;

    public function __construct(StudentService $studentService, InstituteService $instituteService)
    {
        $this->studentService = $studentService;
        $this->instituteService = $instituteService;
    }

    public function issueTransfer(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            return view('frontend/noipunno/student-add/transfer/transfer', compact('branchs', 'classList', 'institute'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }


    public function issueTransferList(Request $request)
    {
        $request->validate(
            [
                'branch' => 'required',
                'shift' => 'required',
                'version' => 'required',
                'class' => 'required',
                'section' => 'required',
            ],
            [
                'branch.required' => 'ব্রাঞ্চের তথ্য নির্বাচন করুন',
                'shift.required' => 'শিফট নির্বাচন করুন',
                'version.required' => 'ভার্সন নির্বাচন করুন',
                'class.required' => 'শ্রেণি নির্বাচন করুন',
                'section.required' => 'সেকশন নির্বাচন করুন',
            ]
        );
        try {
            $request_data = $request->all();
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            if ($request_data) {
                $students = StudentClassInfo::with(['classRoom', 'classRoom.section', 'studentInfo'])
                    ->whereHas('classRoom', function ($query) use ($eiinId, $request_data) {
                        if (!empty($eiinId)) {
                            $query->where('eiin', $eiinId);
                        }
                        if (!empty($request_data['shift'])) {
                            $query->where('shift_id', $request_data['shift']);
                        }
                        if (!empty($request_data['version'])) {
                            $query->where('version_id', $request_data['version']);
                        }
                        if (!empty($request_data['branch'])) {
                            $query->where('branch_id', $request_data['branch']);
                        }
                        if (!empty($request_data['class'])) {
                            $query->where('class_id', $request_data['class']);
                        }
                        if (!empty($request_data['section'])) {
                            $query->where('section_id', $request_data['section']);
                        }
                        // $query->orderBy('class_id', 'desc');
                    })
                    ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
                    ->orderBy('class_rooms.class_id', 'asc')
                    ->orderBy('roll', 'asc')
                    ->paginate(40);
            } else {
                $students = [];
            }
            // dd($students);
            return view('frontend/noipunno/student-add/transfer/transfer-list', compact('branchs', 'classList', 'institute', 'request_data', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function issueTransferAdd(Request $request, $student_uid)
    {
        try {
            $student = StudentClassInfo::where('student_uid', $student_uid)->first();
            return view('frontend/noipunno/student-add/transfer/issue-transfer-form', compact('student'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function issueTransferStore(Request $request)
    {
        $request->validate(
            [
                'issue_date' => 'required',
                'reason' => 'required'
            ],
            [
                'issue_date.required' => 'ছাড়পত্র প্রদানের তারিখ নির্বাচন করুন',
                'reason.required' => 'ছাড়পত্র প্রদানের কারন প্রদান করুন'
            ]
        );

        try {
            DB::beginTransaction();
            $student_class_info = StudentClassInfo::where('student_uid', $request->student_uid)->where('class_room_uid', $request->class_room_uid)->first();

            $student_class_info->rec_status = 2;
            $student_class_info->save();

            $student_history = new StudentHistory();
            $student_history->student_uid = $student_class_info->student_uid;
            $student_history->roll = $student_class_info->roll;
            $student_history->class_room_uid = $student_class_info->class_room_uid;
            $student_history->session_year = $student_class_info->session_year;
            $student_history->rec_status = $student_class_info->rec_status;
            $student_history->save();

            $student_transfer = new StudentTransfer();
            $student_transfer->student_uid = $student_class_info->student_uid;
            $student_transfer->class_room_uid = $student_class_info->class_room_uid;
            $student_transfer->issue_date  = $request->issue_date;
            $student_transfer->reason  = $request->reason;
            $student_transfer->comment  = $request->comment;
            $student_transfer->save();
            
            DB::commit();
            $notification = array(
                'message' => 'এই শিক্ষার্থীকে ছাড়পত্র প্রদান করা হলো।',
                'alert-type' => 'success'
            );
            return redirect()->route('student.issue.transfer')->with($notification);
            
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function generateTransferCertificate(Request $request, $student_uid)
    {
        $data['student'] = StudentClassInfo::where('student_uid', $student_uid)->first();
        $data['student_transfer'] = StudentTransfer::where('student_uid', $student_uid)->first();
        $data['class'] = ClassEnum::caseName($data['student']->classRoom->class_id);
        $data['logo'] = Storage::url(@$data['student']->classRoom->institute->logo);
        // return view('frontend.noipunno.student-add.transfer.transfer-pdf', $data);
        $pdf = PDF::loadView('frontend.noipunno.student-add.transfer.transfer-pdf', $data);


        $fileName = 'tc_'.$student_uid.'.pdf';
        return $pdf->stream($fileName);
    }

    public function TransferStudentAdd(Request $request)
    {
        try {
            // $student = StudentClassInfo::where('student_uid', $student_uid)->first();
            return view('frontend/noipunno/student-add/transfer/transfer-student-add');
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
}
