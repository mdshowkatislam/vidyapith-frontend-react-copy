<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\CertificateService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Traits\ApiResponser;

class CertificateController extends Controller
{
    use ApiResponser;

    private $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
        
        // Add CORS headers for certificate endpoints
        $this->middleware(function ($request, $next) {
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            return $response;
        });
    }

    /**
     * Store a newly created certificate in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'roll_number' => 'required|string|max:100',
            'class_name' => 'required|string|max:100',
            'section_name' => 'required|string|max:100',
            'grade_point' => 'required|numeric|min:0|max:5',
            'total_marks' => 'nullable|integer|min:0',
            'exam_type' => 'nullable|string|max:100',
            'exam_name' => 'nullable|string|max:255',
            'academic_year' => 'required|string|max:20',
            'merit_position' => 'nullable|integer|min:1',
            'school_name' => 'required|string|max:255',
            'institute_id' => 'nullable|integer',
            'uid' => 'nullable|string|unique:certificates,uid',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $certificate = $this->certificateService->create($request->all());
            return $this->successResponse($certificate, Response::HTTP_CREATED);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search for existing certificate by student details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $certificate = $this->certificateService->findExisting([
                'student_name' => $request->get('student_name'),
                'roll_number' => $request->get('roll_number'),
                'class_name' => $request->get('class_name'),
                'section_name' => $request->get('section_name'),
                'academic_year' => $request->get('academic_year'),
                'exam_type' => $request->get('exam_type', ''),
            ]);

            if ($certificate) {
                return $this->successResponse($certificate, Response::HTTP_OK);
            } else {
                return $this->errorResponse('Certificate not found', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified certificate for verification.
     *
     * @param string $certificateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($certificateId)
    {
        try {
            $certificate = $this->certificateService->getByUid($certificateId);
            return $this->successResponse($certificate, Response::HTTP_OK);
        } catch (Exception $exc) {
            if (strpos($exc->getMessage(), 'not found') !== false) {
                return $this->errorResponse("Certificate not found", Response::HTTP_NOT_FOUND);
            }
            return $this->errorResponse("Failed to retrieve certificate", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
