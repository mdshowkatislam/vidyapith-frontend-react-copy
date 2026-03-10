<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\InstituteUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Institute;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\InstituteService;
use App\Services\TeacherService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class InstituteController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $instituteService;
    private $teacherService;
    private $authService;
    private $userService;

    public function __construct(InstituteService $instituteService, TeacherService $teacherService, AuthService $authService, UserService $userService)
    {
        $this->instituteService = $instituteService;
        $this->teacherService = $teacherService;
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {

        try {
            $institutes = $this->instituteService->list($request);

            return $this->successResponse($institutes, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function storeInstituteHeadMaster(Request $request)
    {
        try {
            $institutes = $this->instituteService->storeInstituteHeadMaster($request->all());
            return $this->successResponse($institutes, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'institute_name' => 'required',
        //     'institute_type' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return $this->errorResponse($this->Validtor($validator->errors()), 422);
        // }
        try {
            $institute = $this->instituteService->create($request->all());


            return $this->successResponse($institute, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request)
    {
        try {
            $institute = $this->instituteService->updateInstituteData($request->all(), $request->eiin);
            return $this->successResponse($institute, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function upazilaInstituteWithHeadMaster(Request $request, $upazila_id)
    {
        try {
            $institutes = $this->instituteService->getUpazilaInstituteWithHeadMaster($upazila_id);
            return $this->successResponsePaginate($institutes, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
        }
    }

    public function upazilaTeachers(Request $request)
    {
        try {
            $teachers = $this->instituteService->getUpazilaTeachers($request->upazila_id);
            return $this->successResponsePaginate($teachers, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
        }
    }

    public function updateInstituteHeadMaster(Request $request)
    {
        try {
            $institute  = $this->instituteService->getByInstituteId($request['eiin']);
            $teacher    = $this->teacherService->getByCaId($request['pdsid']);

            if ((!empty($institute->head_caid) && !empty($teacher->caid)) && ($teacher->caid == $institute->head_caid)) {
                return true;
            }

            $teacher = $this->instituteService->updateInstituteHeadMaster($request->all());
            return $this->successResponse($teacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getById(Request $request)
    {
        try {
            $teacher = $this->instituteService->getById($request->uid);
            return $this->successResponse($teacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function getByIdWithDetails(Request $request)
    {
        try {
            $teacher = $this->instituteService->getByIdWithDetails($request->eiin);
            return $this->successResponse($teacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function upazillaTotalInstitutes(Request $request)
    {
        try {
            $totalTeacher = $this->instituteService->upazillaTotalInstitutes($request);

            return $this->successResponse($totalTeacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function foreignTotalInstitutes()
    {
        try {
            $totalTeacher = $this->instituteService->foreignTotalInstitutes();

            return $this->successResponse($totalTeacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function upazillaTotalSections(Request $request)
    {
        try {
            $totalSections = $this->instituteService->upazillaTotalSections($request);

            return $this->successResponse($totalSections, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function is_exists(Request $request)
    {
        try {
            $institutes = $this->instituteService->searchInstitute($request);

            return $this->successResponse($institutes, Response::HTTP_OK);
        } catch (Exception $exc) {

            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    public function boards()
    {
        try {
            $boards = $this->instituteService->boards();

            return $this->successResponse($boards, Response::HTTP_OK);
        } catch (Exception $exc) {

            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function getBoardByDistrictId($districtId)
    {
        try {
            $boards = $this->instituteService->getBoardByDistrictId($districtId);

            return $this->successResponse($boards, Response::HTTP_OK);
        } catch (Exception $exc) {

            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getByEiin($eiin)
    {
        try {
            $institute = $this->instituteService->getByEiinId($eiin);
            return $this->successResponse($institute, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function updateInstitute(InstituteUpdateRequest $request)
    {
        // try {
            DB::beginTransaction();
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = app('sso-auth')->user()->eiin . '_' . date('Ymd') . '_' . time() . '.' . $logo->getClientOriginalExtension();

                $filePath = $logo->storeAs('institute/logo', $filename, 'public');

                $request['filePath'] = $filePath;
            }else {
                $student = Institute::on('db_read')->where('eiin', $request->eiin)->first();
                $request['filePath'] = $student->logo;
            }
            $institute = $this->instituteService->updateInstituteData($request->all(), $request->eiin);
            $this->authService->accountUpdate($request->all(), $request->caid, $request->eiin, 1, 1);
            $this->userService->update($request->caid, $request->all());
            DB::commit();
            $message = 'প্রতিষ্ঠানের তথ্য সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($institute, $message, Response::HTTP_OK);
        // } catch (Exception $exc) {
        //     DB::rollBack();
        //     // return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        //     $message = 'প্রতিষ্ঠানের তথ্য আপডেট করা যায় নি।';
        //     return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        // }
    }



    public function getExamPaper()
    {
        try {
            $info = $this->instituteService->getExamPaper();

            $responseData = json_decode($info, true);

            if (isset($responseData['redirect_url'])) {
                return redirect()->away($responseData['redirect_url']);
            } else {
                return response()->json(['error' => 'Redirect URL not found in response.'], 500);
            }
        } catch (Exception $exc) {

            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

}
