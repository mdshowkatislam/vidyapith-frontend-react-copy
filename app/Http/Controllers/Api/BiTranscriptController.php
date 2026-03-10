<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bi;
use App\Models\BiEvaluation;
use App\Models\BiTranscriptDownload;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Institute;
use App\Models\PiEvaluation;
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
class BiTranscriptController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function biTranscriptByStudent(Request $request)
    {
        ini_set('max_execution_time', 3600);
        try {
            $pi_attribute_weight = $this->subjectService->getPiWeight();
            $student_subjects = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->get();
            $class_room_uid = $student_subjects[0]->class_room_uid;
            $SubjectIds = array_column($student_subjects->toArray(), 'subject_uid');
            $distinctSubjectIds = array_unique($SubjectIds);
            $subject_ids = array_values($distinctSubjectIds);
            foreach ($subject_ids as $key => $subject_id) {
                $duplicate_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) > 1')
                    ->orderBy('bi_uid', 'desc')
                    ->get();

                $data['duplicate_evaluation'] = [];
                foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                    $pi_weight = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('bi_uid', $d_list->bi_uid)->pluck('weight_uid');

                    $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();

                    $get_student_weight_with_number = array_column($get_student_weight, 'number');
                    array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                    $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $request->student_uid;
                    $data['duplicate_evaluation'][$d_list_key]['bi_uid'] = $d_list->bi_uid;
                    $get_student_weight_single = collect($get_student_weight)->first();
                    $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
                }
                $single_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) < 2')
                    ->orderBy('bi_uid', 'desc')
                    ->get();
                $data['single_evaluation'] = [];
                foreach ($single_evaluation_list as $s_list_key => $s_list) {
                    $data['single_evaluation'][$s_list_key]['student_uid'] = $request->student_uid;
                    $data['single_evaluation'][$s_list_key]['bi_uid'] = $s_list->bi_uid;
                    $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
                }

                $trancript[$key] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);
            }

            $trancript_with_bi = [];

            foreach ($trancript as $trancript_single_subject) {
                foreach ($trancript_single_subject as $trancript_single_subject_bi) {
                    if (@$trancript_single_subject_bi['weight_uid']) {
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['bi_uid'] = $trancript_single_subject_bi['bi_uid'];
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] = (int)@$trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] + 1;
                    }
                }
            }
            $bis = [];
            foreach ($trancript_with_bi as $bi) {
                $bis[$bi['bi_uid']]['bi_uid'] = $bi['bi_uid'];

                $maxValue = max($bi['weight_data']);

                $weight_data_max_values = array_filter($bi['weight_data'], function ($value) use ($maxValue) {
                    return $value == $maxValue;
                });

                $bis[$bi['bi_uid']]['weight_uid'] = max(array_keys($weight_data_max_values));
            }
            $trancript = array_values($bis);

            $download = new BiTranscriptDownload();
            $download->student_uid = $request->student_uid;
            $download->save();

            return response()->json(['trancript' => $trancript]);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function biReportCardByStudent(Request $request)
    {
        ini_set('max_execution_time', 3600);
        try {
            $pi_attribute_weight = $this->subjectService->getPiWeight();
            $student_subjects = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->get();
            $class_room_uid = $student_subjects[0]->class_room_uid;
            $SubjectIds = array_column($student_subjects->toArray(), 'subject_uid');
            $distinctSubjectIds = array_unique($SubjectIds);
            $subject_ids = array_values($distinctSubjectIds);
            foreach ($subject_ids as $key => $subject_id) {
                $duplicate_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) > 1')
                    ->orderBy('bi_uid', 'desc')
                    ->get();

                $data['duplicate_evaluation'] = [];
                foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                    $pi_weight = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('bi_uid', $d_list->bi_uid)->pluck('weight_uid');

                    $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();

                    $get_student_weight_with_number = array_column($get_student_weight, 'number');
                    array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                    $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $request->student_uid;
                    $data['duplicate_evaluation'][$d_list_key]['bi_uid'] = $d_list->bi_uid;
                    $get_student_weight_single = collect($get_student_weight)->first();
                    $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
                }
                $single_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) < 2')
                    ->orderBy('bi_uid', 'desc')
                    ->get();
                $data['single_evaluation'] = [];
                foreach ($single_evaluation_list as $s_list_key => $s_list) {
                    $data['single_evaluation'][$s_list_key]['student_uid'] = $request->student_uid;
                    $data['single_evaluation'][$s_list_key]['bi_uid'] = $s_list->bi_uid;
                    $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
                }

                $trancript[$key] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);
            }

            $trancript_with_bi = [];

            foreach ($trancript as $trancript_single_subject) {
                foreach ($trancript_single_subject as $trancript_single_subject_bi) {
                    if (@$trancript_single_subject_bi['weight_uid']) {
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['bi_uid'] = $trancript_single_subject_bi['bi_uid'];
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] = (int)@$trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] + 1;
                    }
                }
            }
            $bis = [];
            foreach ($trancript_with_bi as $bi) {
                $bis[$bi['bi_uid']]['bi_uid'] = $bi['bi_uid'];

                $maxValue = max($bi['weight_data']);

                $weight_data_max_values = array_filter($bi['weight_data'], function ($value) use ($maxValue) {
                    return $value == $maxValue;
                });

                $bis[$bi['bi_uid']]['weight_uid'] = max(array_keys($weight_data_max_values));
            }
            $trancript = array_values($bis);

            $dimensions = $this->subjectService->getBiDimension();

            foreach ($dimensions as $index => $dimension) {
                $max = 0;
                $min = 0;

                $dimension_bis = collect($dimension['bis'])->pluck('uid')->toArray();
                $dimension_bis_from_transcript = collect($trancript)->whereIn('bi_uid', $dimension_bis);

                $max = ($dimension_bis_from_transcript->where('weight_uid', '1780613656567367')->count());
                $min = ($dimension_bis_from_transcript->where('weight_uid', '1780613557043401')->count());

                $result = ($max - $min) / count($dimension['bis']) * 100;

                $report[$index]['subject_uid'] = intval($subject_id);
                $report[$index]['student_uid'] = intval($request->student_uid);
                $report[$index]['dimension_uid'] = $dimension['uid'];

                if ($result == 100) {
                    $report[$index]['dimension_result'] = 7;
                } elseif ($result >= 50 && $result < 100) {
                    $report[$index]['dimension_result'] = 6;
                } elseif ($result >= 25 && $result < 50) {
                    $report[$index]['dimension_result'] = 5;
                } elseif ($result >= 0 && $result < 25) {
                    $report[$index]['dimension_result'] = 4;
                } elseif ($result >= -25 && $result < 0) {
                    $report[$index]['dimension_result'] = 3;
                } elseif ($result >= -50 && $result < -25) {
                    $report[$index]['dimension_result'] = 2;
                } elseif ($result >= -100 && $result < -50) {
                    $report[$index]['dimension_result'] = 1;
                } else {
                    $report[$index]['dimension_result'] = 0;
                }
            }
            return response()->json(['report_card' => $report]);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }





    public function biTranscriptByStudentPdf(Request $request)
    {
        ini_set('max_execution_time', 3600);

        try {
            $pi_attribute_weight = $this->subjectService->getPiWeight();
            $student_subjects = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->get();
            $class_room_uid = $student_subjects[0]->class_room_uid;
            $SubjectIds = array_column($student_subjects->toArray(), 'subject_uid');
            $distinctSubjectIds = array_unique($SubjectIds);
            $subject_ids = array_values($distinctSubjectIds);
            foreach ($subject_ids as $key => $subject_id) {
                $duplicate_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) > 1')
                    ->orderBy('bi_uid', 'desc')
                    ->get();

                $data['duplicate_evaluation'] = [];
                foreach ($duplicate_evaluation_list as $d_list_key => $d_list) {
                    $pi_weight = BiEvaluation::on('db_evaluation')->where('student_uid', $request->student_uid)->where('bi_uid', $d_list->bi_uid)->pluck('weight_uid');

                    $get_student_weight = collect($pi_attribute_weight)->whereIn('uid', $pi_weight->toArray())->toArray();

                    $get_student_weight_with_number = array_column($get_student_weight, 'number');
                    array_multisort($get_student_weight_with_number, SORT_DESC, $get_student_weight);
                    $data['duplicate_evaluation'][$d_list_key]['student_uid'] = $request->student_uid;
                    $data['duplicate_evaluation'][$d_list_key]['bi_uid'] = $d_list->bi_uid;
                    $get_student_weight_single = collect($get_student_weight)->first();
                    $data['duplicate_evaluation'][$d_list_key]['weight_uid'] = $get_student_weight_single ? ($get_student_weight_single['uid']) : '';
                }
                $single_evaluation_list = BiEvaluation::on('db_evaluation')->select('bi_uid')
                    ->where('student_uid', $request->student_uid)
                    ->where('subject_uid', $subject_id)
                    ->groupBy('bi_uid')
                    ->havingRaw('COUNT(bi_uid) < 2')
                    ->orderBy('bi_uid', 'desc')
                    ->get();
                $data['single_evaluation'] = [];
                foreach ($single_evaluation_list as $s_list_key => $s_list) {
                    $data['single_evaluation'][$s_list_key]['student_uid'] = $request->student_uid;
                    $data['single_evaluation'][$s_list_key]['bi_uid'] = $s_list->bi_uid;
                    $data['single_evaluation'][$s_list_key]['weight_uid'] = $s_list->weight_uid;
                }

                $trancript[$key] = array_merge($data['single_evaluation'], $data['duplicate_evaluation']);
            }

            $trancript_with_bi = [];

            foreach ($trancript as $trancript_single_subject) {
                foreach ($trancript_single_subject as $trancript_single_subject_bi) {
                    if (@$trancript_single_subject_bi['weight_uid']) {
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['bi_uid'] = $trancript_single_subject_bi['bi_uid'];
                        $trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] = (int)@$trancript_with_bi[$trancript_single_subject_bi['bi_uid']]['weight_data'][$trancript_single_subject_bi['weight_uid']] + 1;
                    }
                }
            }
            $bis = [];
            foreach ($trancript_with_bi as $bi) {
                $bis[$bi['bi_uid']]['bi_uid'] = $bi['bi_uid'];
                $bi_info = Bi::on('mysql3')->where('uid', $bi['bi_uid'])->first();
                $bis[$bi['bi_uid']]['bi_info'] = $bi_info;
                $maxValue = max($bi['weight_data']);

                $weight_data_max_values = array_filter($bi['weight_data'], function ($value) use ($maxValue) {
                    return $value == $maxValue;
                });

                $bis[$bi['bi_uid']]['weight_uid'] = max(array_keys($weight_data_max_values));
            }
            $trancript['result'] = array_values($bis);
            // dd($trancript['result']);

            $trancript['institute'] = Institute::where('eiin', app('sso-auth')->user()->eiin)->first();

            $trancript['student'] = Student::select('student_name_bn', 'student_name_en', 'roll')->where('uid', $request->student_uid)->first();
            $class_room = ClassRoom::where('eiin', app('sso-auth')->user()->eiin)
            ->where('class_id', $request->class_uid)
            ->where('branch_id', $request->branch_uid)
            ->where('shift_id', $request->shift_uid)
            ->where('version_id', $request->version_uid)
            ->where('section_id', $request->section_uid)
            ->first();
            $trancript['class_teacher'] = Teacher::select('name_en', 'name_bn')->where('uid', $class_room->class_teacher_id)->first();
            $trancript['section'] = Section::select('section_name', 'class_id')->where('uid', $request->section_uid)->first();
            $trancript['branch'] = Branch::select('branch_location')->where('uid', $request->branch_uid)->first();
            // $trancript['subject'] = DB::connection('mysql3')->table('subjects')->select('name')->where('uid', $request->subject_uid)->first();
            // dd($trancript['section']);
            // return view('frontend.transcript.bi-transcript-pdf', $trancript);
        $fileName = $request->student_uid.'_bi'. '.' . 'pdf';
        $pdf = PDF::loadView('frontend.transcript.bi-transcript-pdf', $trancript);
        $pdf->save('transcript/'. $fileName);
        return response()->json(['message' => 'Successfully Generated!']);

            return response()->json(['trancript' => $trancript]);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
