<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardV3Controller extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function categoryWiseInstitute(Request $request)
    {

        // dd(app('sso-auth')->user());

        try {
            $institutes = DB::connection('db_read')->table('vw_upazilla_category_wise_institutes')
                ->select(
                    'total_school as School',
                    'total_college as College',
                    'total_school_and_college as School and College',
                    'total_primary as Primary',
                    'total_technical as Technical',
                    'total_madrasah as Madrasah'
                )
                ->where('upazila_uid', $request->id)
                ->first();

            return $this->successResponse($institutes, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status'  => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function foreignCategoryWiseInstitute(Request $request)
    {
        try {
            $institutes = DB::connection('db_read')->table('vw_foreign_category_wise_institutes')
                ->select(
                    'total_school as School',
                    'total_college as College',
                    'total_school_and_college as School and College',
                    'total_primary as Primary',
                    'total_technical as Technical',
                    'total_madrasah as Madrasah'
                )
                ->first();

            return $this->successResponse($institutes, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status'  => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function classWiseStudent(Request $request)
    {

        try {
            $students = DB::connection('db_read')->table('vw_upazilla_class_wise_students')
                ->select(
                    'total_class_6 as Six',
                    'total_class_7 as Seven',
                    'total_class_8 as Eight',
                    'total_class_9 as Nine',
                    // 'total_class_10 as Ten'
                )
                ->where('upazila_uid', $request->id)
                ->first();

            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function foreignClassWiseStudent(Request $request)
    {
        try {
            $students = DB::connection('db_read')->table('vw_foreign_class_wise_students')
                ->select(
                    'total_class_6 as Six',
                    'total_class_7 as Seven',
                    'total_class_8 as Eight',
                    'total_class_9 as Nine',
                    // 'total_class_10 as Ten'
                )
                ->first();

            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status'  => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function subjectWiseTeacher(Request $request)
    {
        try {
            $data = DB::connection('db_read')->table('vw_upazilla_subject_wise_teachers')
                ->select('subject_name', 'total_teachers')
                ->where('upazilla_id', $request->id)
                ->get();

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function foreignSubjectWiseTeacher(Request $request)
    {
        try {
            $data = DB::connection('db_read')->table('vw_foreign_subject_wise_teachers')
                ->select('subject_name', 'total_teachers')
                ->get();

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return (object) [
                'status'   => false,
                'message'  => $e->getMessage(),
            ];
        }
    }

}
