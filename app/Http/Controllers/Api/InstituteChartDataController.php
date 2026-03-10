<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\District;
use App\Models\Upazilla;
use Illuminate\Support\Facades\DB;

class InstituteChartDataController extends Controller
{
    /**
     * Get institute data.
     *
     * @return array Associative array containing institute data.
     */
    public function getInstituteData(Request $request)
    {

        $upazilaId      = $request->upazilaId ?? '';
        $district_id    = $request->district_id ?? '';

        $boardDistrictArr = DB::table('board_to_districts')
            ->where('board_uid', app('sso-auth')->user()->board_id)
            ->pluck('district_uid')
            ->toArray();



        if (!empty($district_id)) {
            $boardDistrictUpazilasArr = DB::table('upazilas')->whereIn('district_id', $district_id)
                ->pluck('uid')->toArray();
        } else {
            $boardDistrictUpazilasArr = DB::table('upazilas')->whereIn('district_id', $boardDistrictArr)
                ->pluck('uid')->toArray();
        }

        $data =  DB::table('vw_upazilla_category_wise_institutes')->select('total_primary as Primary', 'total_school as School', 'total_school_and_college as SchoolAndCollege', 'total_college as College', 'total_technical as Technical', 'total_madrasah as Madrasah');

        if ((app('sso-auth')->user()->user_type_id == 6) && (!empty(app('sso-auth')->user()->board_id))) {
            $data = $data->whereIn('upazila_uid', $boardDistrictUpazilasArr);
        }

        if (!empty($upazilaId)) {
            $data = $data->where(
                function ($query) use ($upazilaId) {
                    $query->where('upazila_uid', $upazilaId);
                }
            );
        }

        $data = $data->get();

        $keys = [
            'School',
            'College',
            'SchoolAndCollege',
            'PrimarySchool',
            'Technical',
            'Madrasah',
        ];

        $sums = [];
        foreach ($keys as $key) {
            $sums[$key] = array_sum(array_column($data->toArray(), $key));
            //dd($sums);
        }

        $newArray = [];
        foreach ($sums as $key => $value) {
            $newArray[] = [
                'name' => $key,
                'steps' => $value,
            ];
        }

        return response()->json($newArray);
    }

    public function getDistictList(Request $request)
    {
        // dd(app('sso-auth')->user());
        $boardid = app('sso-auth')->user()->board_id ?? '';


        $boardDistrictArr = DB::table('board_to_districts')
            ->join('districts', 'districts.uid', '=', 'board_to_districts.district_uid')
            ->where('board_to_districts.board_uid', app('sso-auth')->user()->board_id)
            ->pluck('district_uid')
            ->toArray();


        return response()->json($boardDistrictArr);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function getStudentData()
    {
        $viewStudentsData = DB::table('vw_classtotal_students')->get();
        return response()->json($viewStudentsData);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function getTeacherSubjectWise(Request $request)
    {
        $querySelect = function ($query) {
            $query->distinct()
                ->from('subject_teachers')
                ->select('subject_uid', 'teacher_uid')
                ->whereNull('deleted_at');
        };
        $classId = $request->isMethod('post') ? $request->input('classId') : 6;
        $teacheSubjectWise = DB::table($querySelect, 'a')
            ->join('noip_competence_db.subjects as b', 'a.subject_uid', '=', 'b.uid')
            ->where('class_uid', $classId)
            ->groupBy('b.class_uid', 'b.name')
            ->orderBy('b.class_uid')
            ->select('b.name as name', 'b.class_uid', DB::raw('COUNT(a.subject_uid) as count'))
            ->get();

        return response()->json($teacheSubjectWise);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function getBoardWiseInstitute()
    {
        $boardWiseInstitute = DB::table('boardwiseinstitute')->get();
        return response()->json($boardWiseInstitute);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function getBoardWiseInstituteTeacher()
    {
        $boardWiseInstitute = DB::table('vw_board_wise_institute_teacher_student')->get();
        return response()->json($boardWiseInstitute);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function getBoards()
    {
        $boardList = Board::select('uid', 'board_name_bn','board_name_en','board_short_name')->get();
        return response()->json($boardList);
    }


    public function divisionByDistricts(Request $request)
    {
        $divisionId = $request->division_id;

        $boardDistrictArr = DB::table('board_to_districts')
            ->where('board_uid', app('sso-auth')->user()->board_id)
            ->pluck('district_uid')
            ->toArray();

        $districts = District::query();
        if ((app('sso-auth')->user()->user_type_id == 6) && (!empty(app('sso-auth')->user()->board_id))) {
            $districts = $districts->whereIn('uid', $boardDistrictArr);
        }
        if (!empty($divisionId)) {
            $districts = $districts->where('division_id', $divisionId);
        }
        $districts = $districts->get();

        return response()->json($districts);
    }

    /**
     * Get student data based on the provided criteria.
     *
     * @return array Associative array containing student data.
     */
    public function districtByUpazilas(Request $request)
    {
        $districtId = $request->district_id;
        $upazilas = Upazilla::where('district_id', $districtId)->get();

        return response()->json($upazilas);
    }
}
