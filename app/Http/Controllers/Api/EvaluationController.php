<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Teacher;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\SubjectService;
use App\Services\TeacherService;
use App\Services\SubjectTeacherService\SubjectTeacherService;

use Exception;

class EvaluationController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $teacherService;
    private $subjectTeacherService;
    private $subjectService;

    public function __construct(TeacherService $teacherService, SubjectTeacherService $subjectTeacherService, SubjectService $subjectService)
    {
        $this->teacherService = $teacherService;
        $this->subjectTeacherService = $subjectTeacherService;
        $this->subjectService = $subjectService;
    }

    public function ownSubject(Request $request)
    {
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $user->caid)->first();

        $data['subjects'] = $this->subjectTeacherService->getOwnSubjectByTeacherId($teacher->uid, date('Y'))->toArray();

        foreach ($data['subjects'] as $key => $item) {
            // $data['subjects'][$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
            $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['subject_id']]);
            $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['subject_id']]);
            $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => date('Y'), 'subject_uid' => $item['subject_id']]);
        }
        // dd($data['subjects']);
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    public function classTeacherCheck(Request $request)
    {
        try {
            $teacher_exists = $this->teacherService->classTeacherCheck($request->uid);

            return $this->successResponse($teacher_exists, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function teacherSubjectList(Request $request)
    {
        try {
            $subjects = $this->subjectTeacherService->getOwnSubjectByTeacherId($request->uid, date('Y'))->toArray();
            foreach ($subjects as $key => $item) {
                $subjects[$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
            }

            return $this->successResponse($subjects, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function oviggotaPiList(Request $request)
    {
        $data['subjects'] = $this->subjectService->getAll();

        foreach ($data['subjects'] as $key => $item) {
            $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['uid']]);
            $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['uid']]);
        }
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function biList(Request $request)
    {
        $data['bis'] = $this->subjectService->getAllBis();
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function piSelectionList(Request $request)
    {

        $data['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => date('Y'), 'subject_uid' => $request->subject_uid]);

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
