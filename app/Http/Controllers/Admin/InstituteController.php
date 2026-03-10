<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\InstituteService;
use App\Models\District;
use App\Models\Division;
use App\Models\Institute;
use App\Models\Teacher;
use App\Models\Upazilla;
use App\Services\Api\AuthService;
use App\Services\DivisionService;
use App\Services\TeacherService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Storage;

class InstituteController extends Controller
{
    private $instituteService;
    private $divisionService;
    private $authService;
    private $teacherService;
    private $userService;

    public function __construct(TeacherService $teacherService, InstituteService $instituteService, DivisionService $divisionService, AuthService $authService, UserService $userService)
    {
        $this->instituteService = $instituteService;
        $this->divisionService = $divisionService;
        $this->authService = $authService;
        $this->teacherService = $teacherService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $institutes = $this->instituteService->list();
            return view('admin.institutes.index', compact('institutes'));
        } catch (Exception $e) {
            return back()->with($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'institute_name' => 'required',
            'institute_name_bn' => 'required',
            'board_uid' => 'required',
            'phone' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'logo' => 'mimes:jpg,png,jpeg|max:300',
        ]);
        try {
            $this->instituteService->create($request);
            return redirect()->route('institutes.index');
        } catch (Exception $e) {
            return back()->with($e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        // dd(Storage::download('institute/logo/abc.jpg'));
        $eiinId = auth()->user()->eiin;
        $divisions = $this->divisionService->list(1);
        $boards = Board::all();
        $countries = Country::all();
        $institute = $this->instituteService->getByInstituteId($request->id);
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1);
        return view('frontend.noipunno.institutes.index', compact('myTeachers', 'institute', 'divisions', 'eiinId', 'boards', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'institute_name'            => 'required',
                'institute_name_bn'         => 'required',
                'board_uid'                 => 'required',
                'category'                  => 'required',
                'phone'                     => 'required_if:is_foreign,0',
                'division_id'               => 'required_if:is_foreign,0',
                'district_id'               => 'required_if:is_foreign,0',
                'upazila_id'                => 'required_if:is_foreign,0',
                'country'                   => 'required_if:is_foreign,1',
                'email'                     => 'required_if:is_foreign,1',
                'logo'                      => 'mimes:jpg,png,jpeg|max:300',
            ],[
                'institute_name.required'   => 'অনুগ্রহ করে প্রতিষ্ঠানের নাম (ইংরেজি) প্রদান করুন',
                'institute_name_bn.required'=> 'অনুগ্রহ করে প্রতিষ্ঠানের নাম (বাংলা) প্রদান করুন',
                'board_uid.required'        => 'বোর্ড নির্বাচন করুন',
                'category.required'         => 'প্রতিষ্ঠানের ধরন নির্বাচন করুন',
                'phone.required_if'         => 'অনুগ্রহ করে মোবাইল নম্বর প্রদান করুন',
                'division_id.required_if'   => 'বিভাগ নির্বাচন করুন',
                'district_id.required_if'   => 'জেলা নির্বাচন করুন',
                'upazila_id.required_if'    => 'উপজেলা নির্বাচন করুন',
                'country.required_if'       => 'দেশ নির্বাচন করুন',
                'email.required_if'         => 'অনুগ্রহ করে ইমেইল প্রদান করুন',
                'logo.mimes'                => 'লোগো শুধুমাত্র JPG, JPEG, PNG হবে',
                'logo.max'                  => 'লোগো এর সাইজ সর্বোচ্চ হবে 300 KB',
            ]
        );

        try {
            $this->instituteService->updateInstituteData($request, $id);
            $this->authService->accountUpdate($request->all(), $request->caid, $request->eiin, 1, 1);
            $this->userService->update($request->caid, $request->all());
            $notification = array(
                'message' => 'প্রতিষ্ঠানের তথ্য সফলভাবে আপডেট করা হয়েছে।',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'প্রতিষ্ঠানের তথ্য আপডেট করা যায় নি।',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }


    public function getExamPaper()
    {
        try {
            $info = $this->instituteService->getExamPaper();

            $responseData = json_decode($info, true);

            if (isset($responseData['redirect_url'])) {
                return redirect()->away($responseData['redirect_url']);
            } else {
      //     dd($responseData);

                return redirect()->route('resubmit_paper',$responseData);
            }
        } catch (Exception $exc) {
            // Handle the exception here
        }
    }
}