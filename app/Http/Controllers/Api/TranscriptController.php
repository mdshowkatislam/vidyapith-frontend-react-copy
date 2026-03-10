<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiEvaluation;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Institute;
use App\Models\Pi;
use App\Models\PiEvaluation;
use App\Models\PiTranscriptDownload;
use App\Models\ReportCardDownload;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use PDF;
use PhpParser\Builder\Class_;

class TranscriptController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function transcript(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
        $pi_attribute_weight = $this->subjectService->getPiWeight();
        $students = Student::select('uid')->where('branch', $request->branch_uid)->where('version',  $request->version_uid)->where('shift', $request->shift_uid)->where('class', $request->class_uid)->where('section', $request->section_uid)->get();
        // dd($students->toArray());
        foreach ($students as $key => $student) {
            $duplicate_evaluation_list = PiEvaluation::on('db_evaluation')->select(DB::raw('MAX(student_uid) as student_uid'), DB::raw('MAX(teacher_uid) as teacher_uid'), 'pi_uid')
                ->with(['get_self_pi_evaluation'])
                ->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                    $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
                })
                ->where('student_uid', $student->uid)
                ->where('teacher_uid', $teacher->uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) > 1')
                ->orderBy('pi_uid', 'desc')
                ->get();
            $data['duplicate_evaluation'] = [];
            foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $d_list['get_self_pi_evaluation']->pluck('weight_uid')->toArray())->toArray();
                $get_student_weight_with_number = array_column($get_student_weight, 'number');
                array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $d_list->student_uid;
                $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
                $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = @$get_student_weight[0]['uid'];
            }

            $single_evaluation_list = PiEvaluation::on('db_evaluation')->select(
                DB::raw('MAX(student_uid) as student_uid'),
                DB::raw('MAX(weight_uid) as weight_uid'),
                'pi_uid'
            )->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
            })
                ->where('student_uid', $student->uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) < 2')
                ->orderBy('pi_uid', 'desc')
                ->get();
            $data['single_evaluation'] = [];
            foreach ($single_evaluation_list as $s_list_key => $s_list) {
                $data['single_evaluation'][$s_list_key]['student_uid'] = $s_list->student_uid;
                $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
                $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
            }

            $trancript[$key]['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);
        }
        return response()->json(['transcript' => $trancript]);
    }

    public function transcriptByStudent_old(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
        $pi_attribute_weight = $this->subjectService->getPiWeight();

        $duplicate_evaluation_list = PiEvaluation::on('db_evaluation')->select(DB::raw('MAX(student_uid) as student_uid'), DB::raw('MAX(teacher_uid) as teacher_uid'), 'pi_uid')
            ->with(['get_self_pi_evaluation'])
            ->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
            })
            ->where('student_uid', $request->student_uid)
            ->where('teacher_uid', $teacher->uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) > 1')
            ->orderBy('pi_uid', 'desc')
            ->get();
        $data['duplicate_evaluation'] = [];
        foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
            $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $d_list['get_self_pi_evaluation']->pluck('weight_uid')->toArray())->toArray();
            $get_student_weight_with_number = array_column($get_student_weight, 'number');
            array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
            $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $d_list->student_uid;
            $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
            $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = @$get_student_weight[0]['uid'];
        }

        $single_evaluation_list = PiEvaluation::on('db_evaluation')->select(
            DB::raw('MAX(student_uid) as student_uid'),
            DB::raw('MAX(weight_uid) as weight_uid'),
            'pi_uid'
        )->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
            $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
        })
            ->where('student_uid', $request->student_uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) < 2')
            ->orderBy('pi_uid', 'desc')
            ->get();
        $data['single_evaluation'] = [];
        foreach ($single_evaluation_list as $s_list_key => $s_list) {
            $data['single_evaluation'][$s_list_key]['student_uid'] = $s_list->student_uid;
            $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
            $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
        }

        $trancript['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);

        return response()->json(['transcript' => $trancript]);
    }

    public function transcriptByStudent(Request $request)
    {
        ini_set('max_execution_time', 3600);
        try {
            // $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
            $pi_attribute_weight = $this->subjectService->getPiWeight();

            $duplicate_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
                ->where('student_uid', $request->student_uid)
                ->where('subject_uid', $request->subject_uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) > 1')
                ->orderBy('pi_uid', 'desc')
                ->get();

            $data['duplicate_evaluation'] = [];
            foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                $pi_weight = PiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('pi_uid', $d_list->pi_uid)->pluck('weight_uid');

                $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();

                $get_student_weight_with_number = array_column($get_student_weight, 'number');
                array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $d_list->student_uid;
                $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
                $get_student_weight_single = collect($get_student_weight)->first();
                $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
            }
            $single_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
                ->where('student_uid', $request->student_uid)
                ->where('subject_uid', $request->subject_uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) < 2')
                ->orderBy('pi_uid', 'desc')
                ->get();
            $data['single_evaluation'] = [];
            foreach ($single_evaluation_list as $s_list_key => $s_list) {
                $data['single_evaluation'][$s_list_key]['student_uid'] = $s_list->student_uid;
                $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
                $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
            }
            $trancript['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);

            $download = new PiTranscriptDownload();
            $download->student_uid = $request->student_uid;
            $download->subject_uid = $request->subject_uid;
            $download->save();

            return response()->json(['transcript' => $trancript]);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function reportCard(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
        $pi_attribute_weight = $this->subjectService->getPiWeight();

        $students = Student::select('uid')->where('branch', $request->branch_uid)->where('version',  $request->version_uid)->where('shift', $request->shift_uid)->where('class', $request->class_uid)->where('section', $request->section_uid)->get();

        foreach ($students as $key => $student) {
            $duplicate_evaluation_list = PiEvaluation::on('db_evaluation')->select(DB::raw('MAX(student_uid) as student_uid'), DB::raw('MAX(teacher_uid) as teacher_uid'), 'pi_uid')
                ->with(['get_self_pi_evaluation'])
                ->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                    $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
                })
                ->where('student_uid', $student->uid)
                ->where('teacher_uid', $teacher->uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) > 1')
                ->orderBy('pi_uid', 'desc')
                ->get();
            $data['duplicate_evaluation'] = [];
            foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $d_list['get_self_pi_evaluation']->pluck('weight_uid')->toArray())->toArray();
                $get_student_weight_with_number = array_column($get_student_weight, 'number');
                array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
                $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = @$get_student_weight[0]['uid'];
            }

            $single_evaluation_list = PiEvaluation::on('db_evaluation')->select(
                DB::raw('MAX(student_uid) as student_uid'),
                DB::raw('MAX(weight_uid) as weight_uid'),
                'pi_uid'
            )->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
            })
                ->where('student_uid', $student->uid)
                ->groupBy('pi_uid')
                ->havingRaw('COUNT(pi_uid) < 2')
                ->orderBy('pi_uid', 'desc')
                ->get();
            $data['single_evaluation'] = [];
            foreach ($single_evaluation_list as $s_list_key => $s_list) {
                $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
                $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
            }

            $trancript[$key]['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);

            $dimensions = $this->subjectService->getDimensionBySubject(['subject_uid' => $request['subject_uid']]);
            foreach ($dimensions as $index => $dimension) {
                $max = 0;
                $min = 0;

                $dimension_pis = collect($dimension['pis'])->pluck('pi_uid')->toArray();
                $dimension_pis_from_transcript = collect($trancript[$key]['student_result'])->whereIn('pi_uid', $dimension_pis);

                $max = ($dimension_pis_from_transcript->where('weight_uid', '1780613656567367')->count());
                $min = ($dimension_pis_from_transcript->where('weight_uid', '1780613557043401')->count());

                $result = ($max - $min) / count($dimension['pis']) * 100;

                // $report[$key]['student_result'][$index]['dimension_result_percent'] = $result;
                $report[$key]['student_result'][$index]['student_uid'] = $student->uid;
                $report[$key]['student_result'][$index]['dimension_uid'] = $dimension['uid'];

                if ($result == 100) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 7;
                } elseif ($result >= 50 && $result < 100) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 6;
                } elseif ($result >= 25 && $result < 50) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 5;
                } elseif ($result >= 0 && $result < 25) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 4;
                } elseif ($result >= -25 && $result < 0) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 3;
                } elseif ($result >= -50 && $result < -25) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 2;
                } elseif ($result >= -100 && $result < -50) {
                    $report[$key]['student_result'][$index]['dimension_result'] = 1;
                } else {
                    $report[$key]['student_result'][$index]['dimension_result'] = 0;
                }
            }
        }
        return response()->json(['report_card' => $report]);
    }

    public function reportCardByStudentSingleSubject(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
        $pi_attribute_weight = $this->subjectService->getPiWeight();

        $duplicate_evaluation_list = PiEvaluation::on('db_evaluation')->select(DB::raw('MAX(student_uid) as student_uid'), DB::raw('MAX(teacher_uid) as teacher_uid'), 'pi_uid')
            ->with(['get_self_pi_evaluation'])
            ->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
                $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
            })
            ->where('student_uid', $request->student_uid)
            ->where('teacher_uid', $teacher->uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) > 1')
            ->orderBy('pi_uid', 'desc')
            ->get();
        $data['duplicate_evaluation'] = [];
        foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
            $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $d_list['get_self_pi_evaluation']->pluck('weight_uid')->toArray())->toArray();
            $get_student_weight_with_number = array_column($get_student_weight, 'number');
            array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
            $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $d_list->student_uid;
            $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
            $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = @$get_student_weight[0]['uid'];
        }

        $single_evaluation_list = PiEvaluation::on('db_evaluation')->select(
            DB::raw('MAX(student_uid) as student_uid'),
            DB::raw('MAX(weight_uid) as weight_uid'),
            'pi_uid'
        )->withWhereHas('teacher_wise_subject_list', function ($q) use ($request, $teacher) {
            $q->where('teacher_id', $teacher->uid)->where('subject_id', $request->subject_uid);
        })
            ->where('student_uid', $request->student_uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) < 2')
            ->orderBy('pi_uid', 'desc')
            ->get();
        $data['single_evaluation'] = [];
        foreach ($single_evaluation_list as $s_list_key => $s_list) {
            $data['single_evaluation'][$s_list_key]['student_uid'] = $s_list->student_uid;
            $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
            $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
        }
        $trancript['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);

        $dimensions = $this->subjectService->getDimensionBySubject(['subject_uid' => $request['subject_uid']]);
        foreach ($dimensions as $index => $dimension) {
            $max = 0;
            $min = 0;

            $dimension_pis = collect($dimension['pis'])->pluck('pi_uid')->toArray();
            $dimension_pis_from_transcript = collect($trancript['student_result'])->whereIn('pi_uid', $dimension_pis);

            $max = ($dimension_pis_from_transcript->where('weight_uid', '1780613656567367')->count());
            $min = ($dimension_pis_from_transcript->where('weight_uid', '1780613557043401')->count());

            $result = ($max - $min) / count($dimension['pis']) * 100;

            // $report['student_result'][$index]['dimension_result_percent'] = $result;
            $report['student_result'][$index]['student_uid'] = $request->student_uid;
            $report['student_result'][$index]['dimension_uid'] = $dimension['uid'];

            if ($result == 100) {
                $report['student_result'][$index]['dimension_result'] = 7;
            } elseif ($result >= 50 && $result < 100) {
                $report['student_result'][$index]['dimension_result'] = 6;
            } elseif ($result >= 25 && $result < 50) {
                $report['student_result'][$index]['dimension_result'] = 5;
            } elseif ($result >= 0 && $result < 25) {
                $report['student_result'][$index]['dimension_result'] = 4;
            } elseif ($result >= -25 && $result < 0) {
                $report['student_result'][$index]['dimension_result'] = 3;
            } elseif ($result >= -50 && $result < -25) {
                $report['student_result'][$index]['dimension_result'] = 2;
            } elseif ($result >= -100 && $result < -50) {
                $report['student_result'][$index]['dimension_result'] = 1;
            } else {
                $report['student_result'][$index]['dimension_result'] = 0;
            }
        }

        return response()->json(['report_card' => $report]);
    }

    public function reportCardByStudent(Request $request)
    {
        ini_set('max_execution_time', 3600);
        // $teacher = Teacher::select('uid', 'caid')->where('caid', app('sso-auth')->user()->caid)->first();
        try {
            $pi_attribute_weight = $this->subjectService->getPiWeight();
            // $student_subjects = PiEvaluation::on('db_evaluation')->where('student_uid', 1784586541382652)->get()->toArray;
            $student_subjects = DB::connection('db_evaluation')->table('vw_pi_evolation')->where('student_uid', $request->student_uid)->get()->toArray();
            $class_room_uid = $student_subjects[0]->class_room_uid;
            $SubjectIds = array_column($student_subjects, 'subject_uid');
            $distinctSubjectIds = array_unique($SubjectIds);
            $subject_ids = array_values($distinctSubjectIds);
            foreach ($subject_ids as $key => $subject_id) {
                $duplicate_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('pi_uid')
                    ->havingRaw('COUNT(pi_uid) > 1')
                    ->orderBy('pi_uid', 'desc')
                    ->get();

                $data['duplicate_evaluation'] = [];
                foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                    $pi_weight = PiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('pi_uid', $d_list->pi_uid)->pluck('weight_uid');

                    $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();
                    // dd($get_student_weight);
                    $get_student_weight_with_number = array_column($get_student_weight, 'number');
                    array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                    $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $d_list->student_uid;
                    $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
                    $get_student_weight_single = collect($get_student_weight)->first();
                    $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
                }
                $single_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('pi_uid')
                    ->havingRaw('COUNT(pi_uid) < 2')
                    ->orderBy('pi_uid', 'desc')
                    ->get();
                $data['single_evaluation'] = [];
                foreach ($single_evaluation_list as $s_list_key => $s_list) {
                    $data['single_evaluation'][$s_list_key]['student_uid'] = $s_list->student_uid;
                    $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
                    $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
                }
                $trancript['subject_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);

                $dimensions = $this->subjectService->getDimensionBySubject(['subject_uid' => $subject_id]);
                // dd($dimensions);
                foreach ($dimensions as $index => $dimension) {
                    $max = 0;
                    $min = 0;

                    $dimension_pis = collect($dimension['pis'])->pluck('pi_uid')->toArray();
                    $dimension_pis_from_transcript = collect($trancript['subject_result'])->whereIn('pi_uid', $dimension_pis);

                    $max = ($dimension_pis_from_transcript->where('weight_uid', '1780613656567367')->count());
                    $min = ($dimension_pis_from_transcript->where('weight_uid', '1780613557043401')->count());
                    if (count($dimension['pis']) > 0) {
                        $result = ($max - $min) / count($dimension['pis']) * 100;
                    }

                    $report[$key]['subject_result'][$index]['subject_uid'] = intval($subject_id);
                    $report[$key]['subject_result'][$index]['student_uid'] = intval($request->student_uid);
                    $report[$key]['subject_result'][$index]['dimension_uid'] = $dimension['uid'];

                    if ($result == 100) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 7;
                    } elseif ($result >= 50 && $result < 100) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 6;
                    } elseif ($result >= 25 && $result < 50) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 5;
                    } elseif ($result >= 0 && $result < 25) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 4;
                    } elseif ($result >= -25 && $result < 0) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 3;
                    } elseif ($result >= -50 && $result < -25) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 2;
                    } elseif ($result >= -100 && $result < -50) {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 1;
                    } else {
                        $report[$key]['subject_result'][$index]['dimension_result'] = 0;
                    }
                }
            }

            $download = new ReportCardDownload();
            $download->student_uid = $request->student_uid;
            $download->save();

            return response()->json(['report_card' => $report]);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }






    public function transcriptByStudentPdf(Request $request)
    {
        // dd(app('sso-auth')->user());
        ini_set('max_execution_time', 3600);
        $class_room = ClassRoom::where('eiin', app('sso-auth')->user()->eiin)
                    ->where('class_id', $request->class_uid)
                    ->where('branch_id', $request->branch_uid)
                    ->where('shift_id', $request->shift_uid)
                    ->where('version_id', $request->version_uid)
                    ->where('section_id', $request->section_uid)
                    ->first();
        $pi_attribute_weight = $this->subjectService->getPiWeight();

        $duplicate_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
            ->where('student_uid', $request->student_uid)
            ->where('subject_uid', $request->subject_uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) > 1')
            ->orderBy('pi_uid', 'desc')
            ->get();

        $data['duplicate_evaluation'] = [];
        foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
            $pi_weight = PiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('pi_uid', $d_list->pi_uid)->pluck('weight_uid');

            $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();

            $get_student_weight_with_number = array_column($get_student_weight, 'number');
            array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
            $data['duplicate_evaluation'][$d_list_key]['pi_uid'] = $d_list->pi_uid;
            $pi = Pi::on('mysql3')->where('uid', $d_list->pi_uid)->first();
            $data['duplicate_evaluation'][$d_list_key]['pi'] = $pi;
            $get_student_weight_single = collect($get_student_weight)->first();
            $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
        }
        $single_evaluation_list = DB::connection('db_evaluation')->table('vw_pi_evolation')
            ->where('student_uid', $request->student_uid)
            ->where('subject_uid', $request->subject_uid)
            ->groupBy('pi_uid')
            ->havingRaw('COUNT(pi_uid) < 2')
            ->orderBy('pi_uid', 'desc')
            ->get();
        $data['single_evaluation'] = [];
        foreach ($single_evaluation_list as $s_list_key => $s_list) {
            $data['single_evaluation'][$s_list_key]['pi_uid'] = $s_list->pi_uid;
            $pi = Pi::on('mysql3')->where('uid', $s_list->pi_uid)->first();
            $data['single_evaluation'][$s_list_key]['pi'] = $pi;
            $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
        }
        $trancript['student_result'] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);
        $trancript['institute'] = Institute::where('eiin', app('sso-auth')->user()->eiin)->first();
        $trancript['student'] = Student::select('student_name_bn', 'student_name_en', 'roll')->where('uid', $request->student_uid)->first();
        $trancript['class_teacher'] = Teacher::select('name_en', 'name_bn')->where('uid', $class_room->class_teacher_id)->first();
        $trancript['section'] = Section::select('section_name', 'class_id')->where('uid', $request->section_uid)->first();
        $trancript['branch'] = Branch::select('branch_location')->where('uid', $request->branch_uid)->first();
        $trancript['subject'] = DB::connection('mysql3')->table('subjects')->select('name')->where('uid', $request->subject_uid)->first();

        // return response()->json(['transcript' => $trancript]);
        // return view('frontend.transcript.pi-transcript-pdf', $trancript);
        $fileName = $request->student_uid.'_'.$request->subject_uid. '.' . 'pdf';
        $pdf = PDF::loadView('frontend.transcript.pi-transcript-pdf', $trancript);
        $pdf->save('transcript/'. $fileName);
        return response()->json(['message' => 'Successfully Generated!']);
        // return $pdf->stream($fileName);
    }
}
