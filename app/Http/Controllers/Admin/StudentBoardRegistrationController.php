<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ClassEnum;
use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Payment;
use App\Models\PaymentConfig;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\BoardRegistrationService;
use App\Services\InstituteService;
use App\Services\PaymentService;
use App\Services\StudentService;
use Carbon\Carbon;
use Exception;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class StudentBoardRegistrationController extends Controller
{
    private $studentService;
    private $instituteService;
    private $paymentService;
    private $boardRegistrationService;

    public function __construct(StudentService $studentService, InstituteService $instituteService, PaymentService $paymentService, BoardRegistrationService $boardRegistrationService)
    {
        $this->studentService = $studentService;
        $this->instituteService = $instituteService;
        $this->paymentService = $paymentService;
        $this->boardRegistrationService = $boardRegistrationService;
    }

    public function studentBoardRegistrtion(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            return view('frontend/noipunno/student-add/board-registration/board-registration', compact('branchs', 'classList', 'institute'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionClass($class_id)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $existsPayment = $this->paymentService->isExists($eiinId, $class_id, date('Y'));


            if (!$existsPayment) {
                $payment_config = PaymentConfig::where('class', $class_id)->first();
                return view('frontend/noipunno/student-add/board-registration/board-registration-payment', compact('payment_config'));
            } else {
                $remainingStudent = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'));

                if ($remainingStudent < 1) {

                    $payment_config = PaymentConfig::where('class', $class_id)->first();
                    return view('frontend/noipunno/student-add/board-registration/board-registration-payment', compact('payment_config'));
                } else {
                    $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
                    if ($class_id) {
                        $class = ClassEnum::caseName($class_id);
                        $classList = [];
                        if ($class !== null) {
                            $classList[$class_id] = $class;
                        }
                    } else {
                        $classList = ClassEnum::values();
                    }
                    $temp_count = $this->boardRegistrationService->tempStudentCount($class_id);
                    $reg_count = $this->boardRegistrationService->registeredStudentCount($class_id);
                    return view('frontend/noipunno/student-add/board-registration/board-registration-class', compact('branchs', 'classList', 'class_id', 'temp_count', 'reg_count'));
                }
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionPaymentStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'depositor_name' => 'required',
                'depositor_mobile' => 'required',
                'no_of_students' => 'required',
                'amount' => 'required'
            ], [
                'depositor_name.required' => 'অনুগ্রহপূর্বক আমানতকারীর নাম প্রদান করুন',
                'depositor_mobile.required' => 'অনুগ্রহপূর্বক আমানতকারীর মোবাইল নম্বর প্রদান করুন',
                'no_of_students.required' => 'শিক্ষার্থীর সংখ্যা প্রদান করুন',
                'amount.required' => 'টাকার পরিমাণ প্রদান করুন',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }


        $eiinId = auth()->user()->eiin;
        if ($request->class != 10) {
            $class_id = '0' . $request->class;
        } else {
            $class_id = $request->class;
        }
        $time = date('ymdHis');
        $generated_invoice = 'R' . $class_id . $eiinId . $time;
        // $generated_invoice = '55565655112121445454S';
        // $generated_invoice = 'INV' . $class_id . $eiinId . '00000001';
        $spg_username = "a2i@pmo";
        $spg_password = "sbPayment0002";
        $spg_account = "0002601020864";
        $redirect_url = route('student.board_registration.payment_redirect');

        $auth_token = 'ZHVVc2VyMjAxNDpkdVVzZXJQYXltZW50MjAxNA==';

        // Access Token Api for SPG
        $spg_access_token_api_url = 'https://spg.sblesheba.com:6314/api/v2/SpgService/GetAccessToken';
        $access_token_payload = [
            "AccessUser" => [
                "userName" => $spg_username,
                "password" => $spg_password,
            ],
            "invoiceNo" => $generated_invoice,
            "amount" => $request->amount,
            "invoiceDate" => date('Y-m-d'),
            "accounts" => [
                [
                    "crAccount" => $spg_account,
                    "crAmount" => $request->amount,
                ]
            ]
        ];

        $access_token_response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth_token,
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions([
                'verify' => false
            ])
            ->post($spg_access_token_api_url, $access_token_payload);

        $access_token_result = (object) json_decode($access_token_response->getBody(), true);

        // Session Token Api for SPG
        $spg_session_token_api_url = 'https://spg.sblesheba.com:6314/api/v2/SpgService/CreatePaymentRequest';

        $session_token_payload = [
            "authentication" => [
                "apiAccessUserId" => $spg_username,
                "apiAccessToken" => $access_token_result->access_token,
            ],

            "referenceInfo" => [
                "InvoiceNo" => $generated_invoice,
                "InvoiceDate" => date('Y-m-d'),
                "ReturnUrl" => $redirect_url,
                "TotalAmount" => $request->amount,
                "ApplicentName" => $request->depositor_name,
                "ApplicentContactNo" => $request->depositor_mobile,
                "ExtraRefNo" => "noip-test",
            ],
            "creditInformations" => [
                [
                    "slno" => "1",
                    "crAccount" => $spg_account,
                    "crAmount" => $request->amount,
                    "tranMode" => "TRN",
                ]
            ]
        ];

        $session_token_response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth_token,
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions([
                'verify' => false
            ])
            ->post($spg_session_token_api_url, $session_token_payload);
        $session_token_result = (object) json_decode($session_token_response->getBody(), true);

        $spg_landing_url = 'https://spg.sblesheba.com:6313/SpgLanding/SpgLanding/' . $session_token_result->session_token;

        DB::beginTransaction();
        try {
            $requestData = $request->all();
            $requestData['eiin'] = $eiinId;
            $requestData['session_token'] = $session_token_result->session_token;
            $requestData['invoice_no'] = $generated_invoice;

            $payment = $this->paymentService->create($requestData);
            DB::commit();

            return redirect($spg_landing_url);
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }




        // this new code

        // $request->validate(
        //     [
        //         'depositor_name' => 'required',
        //         'depositor_mobile' => 'required',
        //         'no_of_students' => 'required',
        //         'amount' => 'required'
        //     ],
        //     [
        //         'depositor_name.required' => 'অনুগ্রহপূর্বক আমানতকারীর নাম প্রদান করুন',
        //         'depositor_mobile.required' => 'অনুগ্রহপূর্বক আমানতকারীর মোবাইল নম্বর প্রদান করুন',
        //         'no_of_students.required' => 'শিক্ষার্থীর সংখ্যা প্রদান করুন',
        //         'amount.required' => 'টাকার পরিমাণ প্রদান করুন',
        //     ]
        // );
        // DB::beginTransaction();
        // try {
        //     $requestData = $request->all();
        //     $requestData['eiin'] = auth()->user()->eiin;

        //     $payment = $this->paymentService->create($requestData);

        //     $notification = array(
        //         'message' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।',
        //         'alert-type' => 'success'
        //     );
        //     DB::commit();
        //     return redirect()->route('student.board_registration.class', $request->class)->with($notification);
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     $notification = array(
        //         'message' => $e->getMessage(),
        //         'alert-type' => 'error'
        //     );
        //     return back()->with($notification);
        // }
    }


    public function studentBoardRegistrtionPaymentRedirect(Request $request)
    {
        $auth_token = 'ZHVVc2VyMjAxNDpkdVVzZXJQYXltZW50MjAxNA==';
        $transaction_verification_api_url = 'https://spg.sblesheba.com:6314/api/v2/SpgService/TransactionVerificationWithToken';

        $transaction_verification_payload = [
            "session_Token" => $request->session_token
        ];

        $transaction_verification_response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth_token,
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions([
                'verify' => false
            ])
            ->post($transaction_verification_api_url, $transaction_verification_payload);

        $transaction_verification_result = (object) json_decode($transaction_verification_response->getBody(), true);

        $payment = Payment::where('session_token', $request->session_token)->first();
        $payment->transaction_id = $transaction_verification_result->TransactionId;
        $payment->payment_status = $request->status;
        $payment->payment_status_code = $transaction_verification_result->PaymentStatus;
        if ($request->status == 200) {
            $payment->rec_status = 1;
        } else {
            $payment->rec_status = 0;
        }
        $payment->save();

        if ($payment->payment_status == 'success') {
            $this->paymentService->boardRegistrationProcessCreate($payment);
        }

        $notification = array(
            'message' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।',
            'alert-type' => 'success'
        );
        DB::commit();
        return redirect()->route('student.board_registration.payment.list')->with($notification);
    }

    public function studentBoardRegistrtionPaymentList(Request $request)
    {
        try {
            $payments = Payment::where('eiin', auth()->user()->eiin)->orderBy('created_at', 'desc')->get();
            return view('frontend/noipunno/student-add/board-registration/payment-list', compact('payments'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentBoardRegistrtionPaymentListPrint(Request $request)
    {
        try {
            $payments = Payment::where('eiin', auth()->user()->eiin)->orderBy('created_at', 'desc')->get();
            return view('frontend/noipunno/student-add/board-registration/payment-list-print', compact('payments'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionList(Request $request)
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
            if ($request->class) {
                $class = ClassEnum::caseName($request->class);
                $classList = [];
                if ($class !== null) {
                    $classList[$request->class] = $class;
                }
            } else {
                $classList = ClassEnum::values();
            }

            if ($request_data) {
                // $students = $this->studentService->getStudentListByAcademicDetails($request_data, $eiinId, date('Y') - 1);
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
                    })
                    ->whereHas('studentInfo', function ($query) {
                        $query->where('reg_status', 0);
                    })
                    ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
                    ->orderBy('class_rooms.class_id', 'asc')
                    ->orderBy('roll', 'asc')
                    ->get();
            } else {
                $students = [];
            }

            $remainingStudent = $this->paymentService->remainingStudent($eiinId, $request->class, date('Y'));

            return view('frontend/noipunno/student-add/board-registration/board-registration-list', compact('branchs', 'classList', 'request_data', 'students', 'remainingStudent'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentBoardRegistrtionTempList(Request $request, $class_id)
    {
        try {
            $students = $this->boardRegistrationService->tempStudentList($class_id);
            return view('frontend/noipunno/student-add/board-registration/board-registration-temp-list', compact('students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionTempStore(Request $request)
    {
        try {
            $request->validate(
                [
                    'checkedStudents' => 'required',
                ],
                [
                    'checkedStudents.required' => 'কোন শিক্ষার্থী সিলেক্ট করা হয়নি।',
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }


        DB::beginTransaction();
        try {
            $requestData = $request->all();
            foreach ($request->checkedStudents as $student_uid) {
                $students[] = $this->boardRegistrationService->assignToTemporaryList($student_uid);
            }
            $board_reg = $this->boardRegistrationService->updateTempCount($requestData);
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে অস্থায়ী তালিকায় সংযুক্ত করা হয়েছে।',
                'alert-type' => 'success'
            );
            DB::commit();
            return redirect()->route('student.board_registration.temp.list_tab', $request->class)->with($notification);
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionStore(Request $request)
    {
        $request->validate(
            [
                'students' => 'required',
            ],
            [
                'students.required' => 'কোন শিক্ষার্থী সিলেক্ট করা হয়নি।',
            ]
        );
        DB::beginTransaction();
        try {
            $request_data = $request->all();
            foreach ($request_data['students'] as $student_uid) {
                $this->boardRegistrationService->assignToRegisteredList($student_uid);
            }
            $this->boardRegistrationService->updateRegCount($request_data);
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে নতুন সেকশনে যুক্ত করা হয়েছে।',
                'alert-type' => 'success'
            );
            DB::commit();
            return redirect()->route('student.board_registration.registered.list_tab', $request->class)->with($notification);
            // return redirect()->route('student.board_registration.registered.list', $request->class)->with($notification);
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionRegisteredList(Request $request, $class_id)
    {
        try {
            $students = $this->boardRegistrationService->registeredStudentList($class_id);
            return view('frontend/noipunno/student-add/board-registration/board-registration-reg-list', compact('students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function generateChallanVoucher(Request $request, $payment_uid)
    {
        $data['payment'] = Payment::where('uid', $payment_uid)->first();
        $data['time'] = Carbon::now()->setTimezone('Asia/Dhaka')->format('d/m/y, h:i A');
        $data['date_format'] = Carbon::now()->setTimezone('Asia/Dhaka')->format('d-m-Y');

        // $data['logo'] = Storage::url(@$data['student']->classRoom->institute->logo);
        // return view('frontend.noipunno.student-add.board-registration.challan-pdf', $data);
        $pdf = PDF::loadView('frontend.noipunno.student-add.board-registration.challan-pdf', $data);

        $fileName = 'challan_' . $data['payment']->transaction_id . '.pdf';
        return $pdf->stream($fileName);
    }

    /*===========TabWise Board Registration===========*/

    public function studentBoardRegistrtionPaymentTab($class_id)
    {

        try {
            $tab = 'payment_tab';
            $eiinId = auth()->user()->eiin;
            $existsPayment = $this->paymentService->isExists($eiinId, $class_id, date('Y'));
            $remainingStudentCount = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'), 0);
            $remainingStudentExists = $this->paymentService->remainingStudentExists($eiinId, $class_id, date('Y'));



            if (!empty($existsPayment) && ($remainingStudentCount > 0)) {
                return redirect()->route('student.board_registration.list_tab', $class_id);
            }

            $class = ClassEnum::caseName($class_id);
            $payment_config = PaymentConfig::where('class', $class_id)->first();


            return view('frontend/noipunno/student-add/board-registration/tab/board-registration-payment-tab', compact('class_id', 'class', 'payment_config', 'tab'));
        } catch (Exception $e) {

            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    /**
     *  Get Student for select temp list papare
     */
    public function studentBoardRegistrtionListTab(Request $request, $class_id)
    {


        try {
            $eiinId = auth()->user()->eiin;

            $remainingStudentCount = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'), 1);
            $remainingStudentExists = $this->paymentService->remainingStudentExists($eiinId, $class_id, date('Y'));
            $temp_count = $this->boardRegistrationService->tempStudentCount($class_id);
            // dd($remainingStudentExists);
            if (!empty($remainingStudentExists) && ($remainingStudentCount == 0)) {
                if ($temp_count > 0) {
                    return redirect()->route('student.board_registration.temp.list_tab', $class_id);
                }
                return redirect()->route('student.board_registration.payment_tab', $class_id);
            }


            $request_data = $request->all();

            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $class = ClassEnum::caseName($class_id);
            $tab = 'student_tab';
            if ($request->class) {
                $class = ClassEnum::caseName($request->class);
                $classList = [];
                if ($class !== null) {
                    $classList[$request->class] = $class;
                }
            } else {
                $classList = ClassEnum::values();
            }

            // $students = $this->studentService->getStudentListByAcademicDetails($request_data, $eiinId, date('Y') - 1);
            $students = StudentClassInfo::with(['classRoom', 'classRoom.section', 'studentInfo'])
                ->whereHas('classRoom', function ($query) use ($eiinId, $class_id) {
                    if (!empty($eiinId)) {
                        $query->where('eiin', $eiinId);
                    }
                    if (!empty($class_id)) {
                        $query->where('class_id', $class_id);
                    }
                })
                ->whereHas('studentInfo', function ($query) {
                    $query->where('reg_status', 0);
                })
                ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
                ->orderBy('class_rooms.class_id', 'asc')
                ->orderBy('roll', 'asc')
                ->get();


            $temp_count = $this->boardRegistrationService->tempStudentCount($class_id);
            $reg_count = $this->boardRegistrationService->registeredStudentCount($class_id);

            $eiinId = auth()->user()->eiin;
            $existsPayment = $this->paymentService->isExists($eiinId, $class_id, date('Y'));
            $remainingStudent = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'));
            return view('frontend/noipunno/student-add/board-registration/tab/board-registration-list-tab', compact('branchs', 'classList', 'request_data', 'students', 'class_id', 'class', 'temp_count', 'reg_count', 'tab', 'existsPayment', 'remainingStudent'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    /**
     *  Get Student for select temp list papare
     */
    public function studentBoardRegistrtionTempListTab(Request $request, $class_id)
    {
        try {
            $tab = 'tem_student_tab';
            $class = ClassEnum::caseName($class_id);
            $students = $this->boardRegistrationService->tempStudentList($class_id);

            $eiinId = auth()->user()->eiin;
            $existsPayment = $this->paymentService->isExists($eiinId, $class_id, date('Y'));
            $remainingStudentCount = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'), 0);
            $remainingStudentExists = $this->paymentService->remainingStudentExists($eiinId, $class_id, date('Y'));


            // dd($remainingStudentCount);
            // if (!empty($remainingStudentExists) && ($remainingStudentCount == 0)) {
            //     return redirect()->route('student.board_registration.payment_tab', $class_id);
            // }

            return view('frontend/noipunno/student-add/board-registration/tab/board-registration-temp-list-tab', compact('students', 'class_id', 'class', 'tab', 'existsPayment', 'remainingStudentCount', 'remainingStudentExists'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionTempListPrint(Request $request, $class_id, $temp = 0)
    {
        try {
            $class = ClassEnum::caseName($class_id);
            if ($temp == 0) {
                $students = $this->boardRegistrationService->tempStudentList($class_id);
                $maleCount = $students->where('gender', 'MALE')->count();
                $femaleCount = $students->where('gender', 'FEMALE')->count();
                return view('frontend/noipunno/student-add/board-registration/tab/board-registration-temp-list-print', compact('students', 'class_id', 'class', 'maleCount', 'femaleCount'));
            }
            if ($temp == 1) {
                $students = $this->boardRegistrationService->registeredStudentList($class_id);
                $maleCount = $students->where('gender', 'MALE')->count();
                $femaleCount = $students->where('gender', 'FEMALE')->count();
                return view('frontend/noipunno/student-add/board-registration/tab/board-registration-reg-list-print', compact('students', 'class_id', 'class', 'maleCount', 'femaleCount'));
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentBoardRegistrtionRegisteredListTab(Request $request, $class_id)
    {
        try {
            $tab = 'registered_tab';
            $class = ClassEnum::caseName($class_id);
            $students = $this->boardRegistrationService->registeredStudentList($class_id);

            $eiinId = auth()->user()->eiin;
            $existsPayment = $this->paymentService->isExists($eiinId, $class_id, date('Y'));
            $remainingStudentCount = $this->paymentService->remainingStudent($eiinId, $class_id, date('Y'));
            $remainingStudentExists = $this->paymentService->remainingStudentExists($eiinId, $class_id, date('Y'));

            return view('frontend/noipunno/student-add/board-registration/tab/board-registration-reg-list-tab', compact('students', 'class_id', 'class', 'tab', 'existsPayment', 'remainingStudentCount', 'remainingStudentExists'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
}
