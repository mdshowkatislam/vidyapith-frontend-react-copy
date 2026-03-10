<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\StudentsImport;

use App\Models\Student;
use App\Models\User;
use App\Models\Branch;
use App\Models\Version;
use App\Models\Shift;
use App\Models\Section;

use App\Services\ClassService;
use App\Services\StudentService;
use App\Services\Api\AuthService;
use App\Services\InstituteService;
use Exception;
use Auth;
use File;

class StudentController extends Controller
{
    private $classService;
    private $studentService;
    private $authService;
    private $instituteService;

    public function __construct(studentService $studentService, AuthService $authService, ClassService $classService, InstituteService $instituteService)
    {
        $this->classService = $classService;
        $this->studentService = $studentService;
        $this->authService = $authService;
        $this->instituteService = $instituteService;
    }

    public function index(Request $request)
    {
        $value = $request->session()->get('active_tab') ?? 'tab2';
        $request->session()->put('active_tab', $value);

        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId);
            $students = $this->studentService->getByEiinId($eiinId);
            $branchs = $this->studentService->getBranchByEiinId($eiinId);
            $versions = $this->studentService->getVersionByEiinId('', $eiinId);
            $shifts =  $this->studentService->getShiftByEiinId('', $eiinId);
            $sections = $this->studentService->getSectionByEiinId('', '', '', '', $eiinId);

            $classList = $this->classService->getAll();
            return view('frontend/noipunno/student-add/index', compact('students', 'branchs', 'classList', 'institute', 'versions', 'shifts', 'sections'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function store(Request $request)
    {
        $request->session()->forget('active_tab');
        $request->session()->put('active_tab', 'tab1');

        $request->validate(
            [
                'class' => 'required',
                'registration_year' => 'required',
                'roll' => 'required',
                'student_name_en' => 'required',
                'student_name_bn' => 'nullable',
                'brid' => 'nullable',
                'date_of_birth' => 'nullable',
                'gender' => 'nullable',
                'religion' => 'nullable',
                'student_mobile_no' => 'nullable',
                'mother_name_bn' => 'nullable',
                'mother_name_en' => 'nullable',
                'father_name_bn' => 'required',
                'father_name_en' => 'nullable',
                'father_mobile_no' => 'nullable',
                'mother_mobile_no' => 'nullable',
                'guardian_mobile_no' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'class.required' => 'ক্লাস নাম প্রদান করুন',
                'registration_year.required' => 'সন প্রদান করুন',
                'roll.required' => 'রোল নাম্বার প্রদান করুন',
                'student_name_en.required' => 'শিক্ষার্থীর নাম (English) প্রদান করুন',
                'father_name_bn.required' => 'পিতার নাম (বাংলা) প্রদান করুন',
                // 'student_name_bn.required' => 'শিক্ষার্থীর নাম (বাংলা) প্রদান করুন',
                // 'father_mobile_no.required' => 'পিতার মোবাইল নাম্বার প্রদান করুন',
            ]
        );

        try {
            $findByTrash = $this->studentService->getWithTrashedById($request->all(), auth()->user()->eiin);
            if ($findByTrash && $findByTrash->caid) {
                $request['caid'] = @$findByTrash->caid;
                $request['eiin'] = @$findByTrash->eiin;

                if (!empty($findByTrash->deleted_at)) {
                    $this->studentService->update($request->all(), $findByTrash->uid, true);
                    $notification = array(
                        'message' => 'Student Inserted successfully.',
                        'alert-type' => 'success'
                    );
                    $authRequest = $this->authService->accountUpdate($request->all(), $findByTrash->caid, auth()->user()->eiin, 1, 1);
                } else {
                    $notification = array(
                        'message' => 'This institute this roll number student already exist.',
                        'alert-type' => 'error'
                    );
                }
                return redirect()->back()->with($notification);
            } else {
                $authRequest = $this->authService->student($request->all());
                if (@$authRequest->status == true) {
                    $authData = (object) $authRequest->data;
                    $request['caid'] = $authData->caid;
                    $request['eiin'] = $authData->eiin;
                    $this->studentService->create($request->all());
                    $notification = array(
                        'message' => 'Student Inserted successfully.',
                        'alert-type' => 'success'
                    );
                    return redirect()->back()->with($notification);
                } else {
                    $notification = array(
                        'message' => 'Student Inserted failed.',
                        'alert-type' => 'error'
                    );
                    return back()->with($notification);
                }
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Student Inserted failed.',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function import(Request $request)
    {
        $request->session()->forget('active_tab');
        $request->session()->put('active_tab', 'tab2');

        $request->validate([
            'class' => 'required',
            'registration_year' => 'required',
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            DB::beginTransaction();
            $result = Excel::import(new StudentsImport, request()->file('file'));
            if ($result) {
                $notification = array(
                    'message' => 'Student Inserted successfully.',
                    'alert-type' => 'success'
                );
            } else {
                $notification = array(
                    'message' => 'Student Inserted failed.',
                    'alert-type' => 'error'
                );
            }
            DB::commit();
            return redirect()->back()->with($notification);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollback();
            $failures = $e->failures();
            return redirect()->back()->with('import_errors', $failures);
        }
    }

    public function edit($id)
    {
        $eiinId = auth()->user()->eiin;
        $institute = $this->instituteService->getByEiinId($eiinId);
        $student = $this->studentService->getByUId($id);
        $branchs = $this->studentService->getBranchByEiinId($eiinId);
        $versions = $this->studentService->getVersionByEiinId('', $eiinId);
        $shifts =  $this->studentService->getShiftByEiinId('', $eiinId);
        $sections = $this->studentService->getSectionByEiinId('', '', '', '', $eiinId);
        $classList = $this->classService->getAll();
        if (!empty($student->branch)) {
            $versions = $this->studentService->getVersionByEiinId($student->branch, $eiinId);
            $shifts = $this->studentService->getShiftByEiinId($student->branch, $eiinId);
            $sections = $this->studentService->getSectionByEiinId($student->branch, $student->class, $student->shift, $student->version, $eiinId);
        } else {
            $versions = $this->studentService->getVersionByEiinId('', $eiinId);
            $shifts =  $this->studentService->getShiftByEiinId('', $eiinId);
            $sections = $this->studentService->getSectionByEiinId('', '', '', '', $eiinId);
        }
        $allStudents = $this->studentService->getByEiinId($eiinId);
        return view('frontend/noipunno/student-add/edit', compact('student', 'allStudents', 'branchs', 'versions', 'shifts', 'sections', 'classList', 'institute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'class' => 'required',
                'registration_year' => 'required',
                'roll' => 'required',
                'student_name_en' => 'required',
                'student_name_bn' => 'nullable',
                'brid' => 'nullable',
                'date_of_birth' => 'nullable',
                'gender' => 'nullable',
                'religion' => 'nullable',
                'student_mobile_no' => 'nullable',
                'mother_name_bn' => 'nullable',
                'mother_name_en' => 'nullable',
                'father_name_bn' => 'required',
                'father_name_en' => 'nullable',
                'father_mobile_no' => 'nullable',
                'mother_mobile_no' => 'nullable',
                'guardian_mobile_no' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'class.required' => 'ক্লাস নাম প্রদান করুন',
                'registration_year.required' => 'সন প্রদান করুন',
                'roll.required' => 'রোল নাম্বার প্রদান করুন',
                'student_name_en.required' => 'শিক্ষার্থীর নাম (English) প্রদান করুন',
                'father_name_bn.required' => 'পিতার নাম (বাংলা) প্রদান করুন',
                // 'student_name_bn.required' => 'শিক্ষার্থীর নাম (বাংলা) প্রদান করুন',
                // 'father_mobile_no.required' => 'পিতার মোবাইল নাম্বার প্রদান করুন',
            ]
        );

        try {
            $result = $this->studentService->checkRollExists($id, $request->roll);
            if ($result) {
                $notification = array(
                    'message' => 'This institute this roll number student already exist.',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            } else {
                $authRequest = $this->authService->accountUpdate($request->all(), $id, auth()->user()->eiin, 1, 1);
                if (@$authRequest->status == true) {
                    // $authData = (object) $authRequest->data;
                    // $request['eiin'] = @$authData->eiin;
                    $this->studentService->update($request->all(), $id);
                    $notification = array(
                        'message' => 'Student Updated successfully.',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('student.index')->with($notification);
                } else {
                    $notification = array(
                        'message' => 'Student Updated failed.',
                        'alert-type' => 'error'
                    );
                    return back()->with($notification);
                }
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Student Updated failed.',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function download()
    {
        $eiinId = auth()->user()->eiin;

        $filePath = public_path('student/eiin_students.xlsx');

        $newName = $eiinId . '_students.xlsx';

        $headers = [
            'Content-Type' => 'application/xlsx',
            'Content-Disposition' => 'attachment; filename="' . $newName . '"',
        ];

        return response()->file($filePath, $headers);

        // return response()->download(public_path('student/eiin_students.xlsx'));
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function getAllRequiredDropdownForStudents(Request $request)
    {
        $branchId = $request->input('id');
        $eiinId = auth()->user()->eiin;
        $versions = $this->studentService->getVersionByEiinId($branchId, $eiinId);
        $shifts =  $this->studentService->getShiftByEiinId($branchId, $eiinId);

        $response = [
            'versions' => $versions,
            'shifts' => $shifts,
        ];

        return response()->json($response);
    }

    public function getSectionDropdownForStudents(Request $request)
    {
        $branchId = $request->input('branch_id');
        $classId = $request->input('class_id');
        $shiftId = $request->input('shift_id');
        $versionId = $request->input('version_id');
        $eiinId = auth()->user()->eiin;

        $response = $this->studentService->getSectionByEiinId($branchId, $classId, $shiftId, $versionId, $eiinId);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $this->studentService->delete($request->id);
        return redirect()->back();
    }
}
