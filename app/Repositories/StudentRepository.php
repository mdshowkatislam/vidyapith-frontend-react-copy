<?php

namespace App\Repositories;

use App\Helper\UtilsApiEndpoint;
use App\Helper\UtilsCookie;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Institute;
use App\Models\Section;
use App\Models\Shift;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentClassInfo;
use App\Models\Version;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\AttendanceSyncService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Auth;

class StudentRepository implements StudentRepositoryInterface
{
    protected $attendanceSyncService;

    public function __construct(AttendanceSyncService $attendanceSyncService)
    {
        $this->attendanceSyncService = $attendanceSyncService;
    }

    // public function list()
    // {
    //     return Student::on('db_read')->get();
    // }
    public function list($request)
    {
        $students = Student::on('db_read');

        if (!empty($request->input('name'))) {
            $studentName = $request->input('name');
            $students = $students->where(function ($query) use ($studentName) {
                $query
                    ->where('student_name_bn', 'like', "%{$studentName}%")
                    ->orWhere('student_name_en', 'like', "%{$studentName}%");
            });
        }

        if (app('sso-auth')->user()->caid != '4010001') {
            if ((app('sso-auth')->user()->user_type_id == '5') && (empty(app('sso-auth')->user()->upazila_id))) {
                $totalInstituteArr = Institute::on('db_read')->where('is_foreign', 1)->pluck('eiin')->toArray();
                $students = $students->whereIn('eiin', $totalInstituteArr);
            } else if (!empty($request->upazila_id)) {
                $instituteArr = Institute::on('db_read')->where('upazila_uid', $request->upazila_id)->pluck('eiin')->toArray();
                $students = $students->whereIn('eiin', $instituteArr);
            }
        }

        if (!empty($request->eiin)) {
            $students = $students->where('eiin', $request->eiin);
        }

        if (!empty($request->gender)) {
            $students = $students->where('gender', $request->gender);
        }

        $total_student = $students->count();

        // $perPage = 10; // Number of items per page
        $perPage = $request->limit ?? 10;  // Number of items per page
        $page = $request->page ?? 1;  // Current page number

        $offset = ($page - 1) * $perPage;

        $students = $students->skip($offset)->take($perPage)->get();

        return ['total_student' => $total_student, 'students' => $students];
    }

    public function create($data, $class_room_uid)
    {
        DB::beginTransaction();

        try {
            $birthday = '';
            if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) {
                $birthday = date('Y-m-d', strtotime($data['date_of_birth']));
            } elseif (isset($data['dob']) && !empty($data['dob'])) {
                $birthday = date('Y-m-d', strtotime($data['dob']));
            } else {
                $birthday = NULL;
            }

            $father_date_of_birth = '';
            if (isset($data['father_date_of_birth']) && !empty($data['father_date_of_birth'])) {
                $father_date_of_birth = date('Y-m-d', strtotime($data['father_date_of_birth']));
            } else {
                $father_date_of_birth = NULL;
            }

            $mother_date_of_birth = '';
            if (isset($data['mother_date_of_birth']) && !empty($data['mother_date_of_birth'])) {
                $mother_date_of_birth = date('Y-m-d', strtotime($data['mother_date_of_birth']));
            } else {
                $mother_date_of_birth = NULL;
            }

            $student = new Student;
            $student->eiin = @$data['eiin'];
            $student->suid = @$data['suid'];
            $student->caid = @$data['caid'];
            $student->type = @$data['type'];
            $student->incremental_no = @$data['incremental_no'];
            $student->student_name_en = @$data['student_name_en'] ?? @$data['fullname_english'];
            $student->student_name_bn = @$data['student_name_bn'] ?? @$data['fullname_bangla'];
            $student->brid = @$data['brid'] ?? @$data['bin_brn'];
            $student->date_of_birth = $birthday;
            $student->religion = str_replace(' ', '', @$data['religion']);
            $student->birth_place = @$data['birth_place'] ?? @$data['birthplace'];
            $student->gender = str_replace(' ', '', @$data['gender']);
            $student->board_reg_no = @$data['board_reg_no'];
            $student->nationality = @$data['nationality'];
            $student->recent_study_class = @$data['recent_study_class'] ?? @$data['register_class'];
            $student->disability_status = @$data['disability_status'] ?? @$data['is_disability'];
            $student->blood_group = @$data['blood_group'];
            $student->student_mobile_no = @$data['student_mobile_no'];
            $student->ethnic_info = @$data['ethnic_info'] ?? @$data['small_ethnic_group'];
            $student->branch = @$data['branch'];
            $student->version = @$data['version'];
            $student->shift = @$data['shift'];
            $student->class = @$data['class'] ?? @$data['register_class'];
            $student->section = @$data['section'];
            $student->group = @$data['group'];
            $student->roll = @$data['roll'] ?? @$data['academic_roll_no'];
            $student->student_unique_id = @$data['student_unique_id'];
            $student->is_regular = @$data['is_regular'];
            $student->father_name_bn = @$data['father_name_bn'] ?? @$data['fathername_bangla'];
            $student->father_name_en = @$data['father_name_en'] ?? @$data['fathername_bangla'];
            $student->father_nid = @$data['father_nid'];
            $student->father_brid = @$data['father_brid'];
            $student->father_date_of_birth = $father_date_of_birth;
            $student->father_mobile_no = @$data['father_mobile_no'];
            $student->mother_name_bn = @$data['mother_name_bn'] ?? @$data['mothername_bangla'];
            $student->mother_name_en = @$data['mother_name_en'] ?? @$data['mothername_bangla'];
            $student->mother_nid = @$data['mother_nid'];
            $student->mother_brid = @$data['mother_brid'];
            $student->mother_date_of_birth = $mother_date_of_birth;
            $student->mother_mobile_no = @$data['mother_mobile_no'];
            $student->guardian_name_bn = @$data['guardian_name_bn'] ?? @$data['guardian_name'];
            $student->guardian_name_en = @$data['guardian_name_en'] ?? @$data['guardian_name'];
            $student->guardian_mobile_no = @$data['guardian_mobile_no'];
            $student->guardian_nid = @$data['guardian_nid'];
            $student->guardian_occupation = @$data['guardian_occupation'] ?? @$data['guardian_profession'];
            $student->relation_with_guardian = @$data['relation_with_guardian'] ?? @$data['relationship_with_guardian'];
            $student->present_address = @$data['present_address'] ?? @$data['present_address'];
            $student->permanent_address = @$data['permanent_address'] ?? @$data['present_address'];
            $student->post_office = @$data['post_office'] ?? @$data['present_post_office'];
            $student->division_id = @$data['division_id'] ?? @$data['presentdivisionid'];
            $student->district_id = @$data['district_id'] ?? @$data['presentdistrictid'];
            $student->upazilla_id = @$data['upazila_id'] ?? @$data['presentthanaid'];
            $student->unions = @$data['unions'] ?? @$data['presentthanaid'];
            $student->data_source = @$data['data_source'];
            $student->image = @$data['filePath'];

            $directory_br = 'student/br_file';
            if (@$data['br_file'] && $br_file = @$data->file('br_file')) {
                $filename = $student->eiin . '_' . date('Ymd') . '_' . time() . '.' . $br_file->getClientOriginalExtension();
                $filePath = $br_file->storeAs(
                    $directory_br,
                    $filename,
                    's3'
                );
                $student['br_file'] = $filePath;
            }

            $directory_dis = 'student/dis_file';
            if (@$data['disability_file'] && $dis_file = @$data->file('disability_file')) {
                $filename = $student->eiin . '_' . date('Ymd') . '_' . time() . '.' . $dis_file->getClientOriginalExtension();
                $filePath = $dis_file->storeAs(
                    $directory_dis,
                    $filename,
                    's3'
                );
                $student['disability_file'] = $filePath;
            }

            $student->save();

            // $class_room = ClassRoom::where('branch_id', @$data['branch'])
            //     ->where('version_id', @$data['version'])
            //     ->where('shift_id', @$data['shift'])
            //     ->where('class_id', @$data['class'])
            //     ->where('section_id', @$data['section'])
            //     ->where('session_year', date('Y'))
            //     ->first();
            // if (!$class_room) {
            //     $class_room = new ClassRoom();

            //     $class_room->eiin = @$data['eiin'];
            //     $class_room->class_id = @$data['class'];
            //     $class_room->section_id = @$data['section'];
            //     $class_room->session_year = date('Y');
            //     $class_room->branch_id = @$data['branch'];
            //     $class_room->shift_id = @$data['shift'];
            //     $class_room->version_id = @$data['version'];
            //     $class_room->status = 1;
            //     $class_room->save();
            // }
            $student_class_info = new StudentClassInfo();
            $student_class_info->student_uid = $student->uid;
            $student_class_info->roll = @$data['roll'];;
            $student_class_info->class_room_uid = $class_room_uid;
            $student_class_info->session_year = @$data['session_year'] ?? date('Y');
            $student_class_info->rec_status = 1;
            $student_class_info->save();
          

            // Sync with attendance service - if this fails, the transaction will rollback
            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($student, 'create');
            }

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Student creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    public function update($data, $id, $class_room_uid, $is_restore = false)
    {
        DB::beginTransaction();

        try {
            $birthday = '';
            if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) {
                $birthday = date('Y-m-d', strtotime($data['date_of_birth']));
            } elseif (isset($data['dob']) && !empty($data['dob'])) {
                $birthday = date('Y-m-d', strtotime($data['dob']));
            } else {
                $birthday = NULL;
            }

            if ($is_restore) {
                Student::where('caid', $id)->orwhere('uid', $id)->onlyTrashed()->restore();
            }

            $student = Student::on('db_read')->where('caid', $id)->orwhere('uid', $id)->first();

            if (!$student) {
                throw new \Exception("Student not found with ID: {$id}");
            }

            $student->branch = @$data['branch'];
            $student->version = @$data['version'];
            $student->shift = @$data['shift'];
            $student->class = @$data['class'];
            $student->section = @$data['section'];
            $student->group = @$data['group'];
            $student->registration_year = @$data['registration_year'];
            $student->roll = @$data['roll'];
            $student->student_unique_id = @$data['student_unique_id'];
            $student->student_name_en = @$data['student_name_en'];
            $student->student_name_bn = @$data['student_name_bn'];
            $student->brid = @$data['brid'];
            $student->date_of_birth = $birthday;
            $student->gender = str_replace(' ', '', @$data['gender']);
            $student->religion = str_replace(' ', '', @$data['religion']);
            $student->board_reg_no = @$data['board_reg_no'];
            $student->student_mobile_no = @$data['student_mobile_no'];
            $student->mother_name_bn = @$data['mother_name_bn'];
            $student->mother_name_en = @$data['mother_name_en'];
            $student->father_name_bn = @$data['father_name_bn'];
            $student->father_name_en = @$data['father_name_en'];
            $student->father_mobile_no = @$data['father_mobile_no'];
            $student->mother_mobile_no = @$data['mother_mobile_no'];
            $student->guardian_name_en = @$data['guardian_name_en'];
            $student->guardian_name_bn = @$data['guardian_name_bn'];
            $student->guardian_mobile_no = @$data['guardian_mobile_no'];

            $student->division_id = @$data['division_id'] ?? @$data['presentdivisionid'];
            $student->district_id = @$data['district_id'] ?? @$data['presentdistrictid'];
            $student->upazilla_id = @$data['upazila_id'] ?? @$data['presentthanaid'];
            $student->image = @$data['filePath'];

            $directory_br = 'student/br_file';
            if (@$data['br_file'] && $br_file = @$data->file('br_file')) {
                $filename = $student->eiin . '_' . date('Ymd') . '_' . time() . '.' . $br_file->getClientOriginalExtension();
                $filePath = $br_file->storeAs(
                    $directory_br,
                    $filename,
                    's3'
                );
                $student['br_file'] = $filePath;
            }

            $directory_dis = 'student/dis_file';
            if (@$data['disability_file'] && $dis_file = @$data->file('disability_file')) {
                $filename = $student->eiin . '_' . date('Ymd') . '_' . time() . '.' . $dis_file->getClientOriginalExtension();
                $filePath = $dis_file->storeAs(
                    $directory_dis,
                    $filename,
                    's3'
                );
                $student['disability_file'] = $filePath;
            }

            $student->save();
            // $class_room = ClassRoom::where('branch_id', @$data['branch'])
            //     ->where('version_id', @$data['version'])
            //     ->where('shift_id', @$data['shift'])
            //     ->where('class_id', @$data['class'])
            //     ->where('section_id', @$data['section'])
            //     ->where('session_year', date('Y'))
            //     ->first();
            // if (!$class_room) {
            //     $class_room = new ClassRoom();

            //     $class_room->eiin = app('sso-auth')->user()->eiin;
            //     $class_room->class_id = @$data['class'];
            //     $class_room->section_id = @$data['section'];
            //     $class_room->session_year = date('Y');
            //     $class_room->branch_id = @$data['branch'];
            //     $class_room->shift_id = @$data['shift'];
            //     $class_room->version_id = @$data['version'];
            //     $class_room->status = 1;
            //     $class_room->save();
            // }
            $student_class_info = StudentClassInfo::where('student_uid', $id)->first();
            if (!$student_class_info) {
                $student_class_info = new StudentClassInfo();
            }
            $student_class_info->student_uid = $student->uid;
            $student_class_info->roll = @$data['roll'];;
            $student_class_info->class_room_uid = $class_room_uid;
            $student_class_info->session_year = date('Y');
            $student_class_info->rec_status = 1;
            $student_class_info->save();

            // Sync with attendance service - if this fails, the transaction will rollback

            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($student, 'update');
            }

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Student update failed: ' . $e->getMessage(), [
                'student_id' => $id,
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    public function getById($id)
    {
        return StudentClassInfo::on('db_read')->with(['classRoom', 'studentInfo'])->where('student_uid', $id)->first();
    }

    public function getStudentInfoByUid($uid)
    {
        return Student::on('db_read')->where('uid', $uid)->first();
    }

    public function getAllByEiinId($eiinId, $is_not_paginate, $request_data)
    {
        $search = $request_data['search'] ?? '';
        $students = StudentClassInfo::with(['classRoom', 'studentInfo'])
            // // ->whereHas('classRoom', function ($query) use ($eiinId, $request_data) {
            // //     if (!empty($eiinId)) {
            // //         $query->where('eiin', $eiinId);
            // //     }
            // //     if (!empty($request_data['branch'])) {
            // //         $query->where('branch_id', $request_data['branch']);
            // //     }
            // //     if (!empty($request_data['shift'])) {
            // //         $query->where('shift_id', $request_data['shift']);
            // //     }
            // //     if (!empty($request_data['version'])) {
            // //         $query->where('version_id', $request_data['version']);
            // //     }
            // //     if (!empty($request_data['class'])) {
            // //         $query->where('class_id', $request_data['class']);
            // //     }
            // //     if (!empty($request_data['section'])) {
            // //         $query->where('section_id', $request_data['section']);
            // //     }
            // //     // $query->orderBy('class_id', 'desc');
            // // })
            // // ->whereHas('studentInfo', function ($query) use ($search) {
            // //     if ($search) {
            // //         $query->where('student_name_en', 'like', '%' . $search . '%')
            // //             ->orWhere('student_name_bn', 'like', '%' . $search . '%')
            // //             ->orWhereHas('student_class_info', function ($query_roll) use ($search) {
            // //                 $query_roll->where('roll', 'like', $search);
            // //             });
            // //     }
            // // })
            // // // ->orderByRaw('CAST(roll AS SIGNED INTEGER) ASC')
            // // ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
            // ->orderBy('class_rooms.class_id', 'asc')
            ->orderBy('roll', 'asc')
            ->get();

        return $students;
    }

    public function getByEiinId($eiinId, $is_not_paginate, $request_data, $paginate_number)
    {
        $search = $request_data['search'] ?? '';
        $students = StudentClassInfo::with(['classRoom', 'studentInfo'])
            ->whereHas('classRoom', function ($query) use ($eiinId, $request_data) {
                if (!empty($eiinId)) {
                    $query->where('eiin', $eiinId);
                }
                if (!empty($request_data['branch_id'])) {
                    $query->where('branch_id', $request_data['branch_id']);
                }
                if (!empty($request_data['shift_id'])) {
                    $query->where('shift_id', $request_data['shift_id']);
                }
                if (!empty($request_data['version_id'])) {
                    $query->where('version_id', $request_data['version_id']);
                }
                if (!empty($request_data['class_id'])) {
                    $query->where('class_id', $request_data['class_id']);
                }
                if (!empty($request_data['section_id'])) {
                    $query->where('section_id', $request_data['section_id']);
                }
                // $query->orderBy('class_id', 'desc');
            })
            ->whereHas('studentInfo', function ($query) use ($search, $request_data) {
                if (!empty($request_data['student_unique_id'])) {
                    $query->where('student_unique_id', $request_data['student_unique_id']);
                }
                if ($search) {
                    $query
                        ->where('student_name_en', 'like', '%' . $search . '%')
                        ->orWhere('student_name_bn', 'like', '%' . $search . '%')
                        ->orWhereHas('student_class_info', function ($query_roll) use ($search) {
                            $query_roll->where('roll', 'like', $search);
                        });
                }
            })
            // ->orderByRaw('CAST(roll AS SIGNED INTEGER) ASC')
            ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
            ->orderBy('class_rooms.class_id', 'asc')
            ->orderBy('class_rooms.section_id', 'asc')
            // ->orderBy('roll', ' asc')
            ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
            ->paginate($paginate_number ?? 30);
        //  ->paginate(4000);

        return $students;
    }

    public function getByClassRoomId($eiinId, $is_not_paginate, $request_data, $class_room_uid, $paginate_number)
    {
        $search = $request_data['search'] ?? '';
        $students = StudentClassInfo::with(['classRoom', 'studentInfo'])
            ->whereIn('class_room_uid', $class_room_uid)
            ->whereHas('classRoom', function ($query) use ($eiinId, $request_data) {
                if (!empty($eiinId)) {
                    $query->where('eiin', $eiinId);
                }
                if (!empty($request_data['branch'])) {
                    $query->where('branch_id', $request_data['branch']);
                }
                if (!empty($request_data['shift'])) {
                    $query->where('shift_id', $request_data['shift']);
                }
                if (!empty($request_data['version'])) {
                    $query->where('version_id', $request_data['version']);
                }
                if (!empty($request_data['class'])) {
                    $query->where('class_id', $request_data['class']);
                }
                if (!empty($request_data['section'])) {
                    $query->where('section_id', $request_data['section']);
                }
                // $query->orderBy('class_id', 'desc');
            })
            ->whereHas('studentInfo', function ($query) use ($search) {
                if ($search) {
                    $query
                        ->where('student_name_en', 'like', '%' . $search . '%')
                        ->orWhere('student_name_bn', 'like', '%' . $search . '%')
                        ->orWhereHas('student_class_info', function ($query_roll) use ($search) {
                            $query_roll->where('roll', 'like', $search);
                        });
                }
            })
            // ->orderByRaw('CAST(roll AS SIGNED INTEGER) ASC')
            ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
            ->orderBy('class_rooms.class_id', 'asc')
            ->orderBy('class_rooms.section_id', 'asc')
            // ->orderBy('roll', ' asc')
            ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
            ->paginate($paginate_number ?? 30);

        return $students;
    }

    /**
     * Need to change this function according to branch
     */
    public function getTotalStudentByEiinId($eiin, $is_not_paginate = null, $search = null)
    {
        if ($is_not_paginate) {
            return Student::on('db_read')->select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class')->where('eiin', $eiin)->orderBy('class', 'asc')->orderBy('roll', 'asc')->get();
        } else {
            return Student::on('db_read')
                ->select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class')
                ->where('eiin', $eiin)
                ->where(function ($query) use ($search) {
                    if ($search) {
                        $query
                            ->where('student_name_en', 'like', '%' . $search . '%')
                            ->orWhere('student_name_bn', 'like', '%' . $search . '%')
                            ->orWhere('roll', $search)
                            ->orWhere('class', $search)
                            ->orWhereHas('section_details', function ($sectionQuery) use ($search) {
                                $sectionQuery->where('section_name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('version_details', function ($versionQuery) use ($search) {
                                $versionQuery->where('version_name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('shift_details', function ($shiftQuery) use ($search) {
                                $shiftQuery->where('shift_name', 'like', '%' . $search . '%');
                            });
                    }
                })
                ->orderBy('class', 'asc')
                ->orderBy('roll', 'asc')
                ->paginate(10);
        }
    }

    public function getBranchByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return Branch::on('db_read')->select('uid', 'branch_name')->where('eiin', $eiin)->get();
        } else {
            return Branch::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getVersionByEiinId($branch, $eiin)
    {
        if ($branch == '') {
            return Version::on('db_read')->where('eiin', $eiin)->get();
        } else {
            return Version::on('db_read')->where('branch_id', (int) @$branch)->where('eiin', $eiin)->get();
        }
    }

    public function getShiftByEiinId($branch, $eiin)
    {
        if ($branch == '') {
            return Shift::on('db_read')->where('eiin', $eiin)->get();
        } else {
            return Shift::on('db_read')->where('branch_id', (int) @$branch)->where('eiin', $eiin)->get();
        }
    }

    public function getSectionByEiinId($branch, $class, $shift, $version, $eiin)
    {
        if ($branch == '' && $class == '' && $shift == '' && $version == '') {
            return Section::on('db_read')->where('eiin', $eiin)->get();
        } else {
            return Section::on('db_read')->where('branch_id', (int) $branch)->where('class_id', (int) $class)->where('shift_id', (int) $shift)->where('version_id', (int) $version)->where('eiin', $eiin)->get();
        }
    }

    public function getByCaId($id)
    {
        return Student::on('db_read')->where('caid', $id)->first();;
    }

    public function getByUId($id)
    {
        return Student::on('db_read')->where('uid', $id)->first();;
    }

    public function getWithTrashedById($data, $eiin)
    {
        $branch = $data['branch'];
        $shift = $data['shift'];
        $version = $data['version'];
        $class = $data['class'];
        $section = $data['section'];
        $registration_year = $data['registration_year'];
        $roll = $data['roll'];
        return DB::table('students')->where(function ($query) use ($eiin, $branch, $shift, $version, $class, $section, $registration_year, $roll) {
            if (!empty($eiin)) {
                $query->where('eiin', $eiin);
            }
            if (!empty($branch)) {
                $query->where('branch', $branch);
            }
            if (!empty($shift)) {
                $query->where('shift', $shift);
            }
            if (!empty($version)) {
                $query->where('version', $version);
            }
            if (!empty($class)) {
                $query->where('class', $class);
            }
            if (isset($section) && !empty($section)) {
                $query->where('section', $section);
            }
            if (!empty($registration_year)) {
                $query->where('registration_year', $registration_year);
            }
            if (!empty($roll)) {
                $query->where('roll', $roll);
            }
        })->first();
    }

    public function getStudentListByAcademicDetails($data, $eiin, $year)
    {
        $branch = $data['branch'];
        $shift = $data['shift'];
        $version = $data['version'];
        $class = $data['class'];
        $section = $data['section'];
        return Student::select('uid', 'student_name_bn', 'student_name_en', 'roll', 'father_name_bn', 'incremental_no', 'registration_year')
            ->whereNull('incremental_no')
            ->where(function ($query) use ($eiin, $branch, $shift, $version, $class, $section) {
                if (!empty($eiin)) {
                    $query->where('eiin', $eiin);
                }
                if (!empty($branch)) {
                    $query->where('branch', $branch);
                }
                if (!empty($shift)) {
                    $query->where('shift', $shift);
                }
                if (!empty($version)) {
                    $query->where('version', $version);
                }
                if (!empty($class)) {
                    $query->where('class', $class);
                }
                if (!empty($section)) {
                    $query->where('section', $section);
                }
            })
            ->where('registration_year', $year)
            // ->orderBy('roll')
            ->orderByRaw('CAST(roll AS SIGNED INTEGER) ASC')
            ->get();
    }

    public function isRollExists($class_room_uid, $roll)
    {
        $student = StudentClassInfo::where('class_room_uid', $class_room_uid)->where('roll', $roll)->where('session_year', date('Y'))->first();
        return $student;

        //  $student = StudentClassInfo::join('students', 'students.id', '=', 'student_class_infos.student_uid')
        //     ->where('student_class_infos.class_room_uid', $class_room_uid)
        //     ->where('student_class_infos.roll', $roll)
        //     ->where('student_class_infos.session_year', date('Y'))
        //     ->select('student_class_infos.*', 'students.*')
        //     ->first();

        // return $student;
    }

    public function checkRollExists($caid, $roll)
    {
        return Student::on('db_read')->where('caid', $caid)->where('roll', '<>', $roll)->exists();
    }

    public function authAccountCreateStudent($data)
    {
        $accessToken = UtilsCookie::getCookie();
        $endpoint = 'https://accounts.project-ca.com/api/v1/user/account-create';

        // $endpoint = UtilsApiEndpoint::accountCreate();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post($endpoint, [
            'name' => @$data['student_name_en'],
            'email' => @$data['email'],
            'phone_no' => @$data['father_mobile_no'],
            'password' => 123456,
            'eiin' => @app('sso-auth')->user()->eiin,
            'suid' => '',
            'user_type_id' => 2,
            'class_id' => @$data['class'],
            'year' => @$data['registration_year'],
        ]);

        if (!$response->ok()) {
            return false;
        }
        $result = json_decode($response->getBody(), true);

        return $result;
    }

    public function authAccountCreateInstitude($data)
    {
        // dd($data->institutename);
        $accessToken = UtilsCookie::getCookie();
        $endpoint = 'https://accounts.project-ca.com/api/v1/user/account-create';

        // $endpoint = UtilsApiEndpoint::accountCreate();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post($endpoint, [
            'name' => @$data->institutename,
            // 'email' => @$data['email'],
            'phone_no' => @$data->mobileno,
            'password' => 123456,
            'eiin' => @$data->eiin,
            'suid' => '',
            'user_type_id' => 3,
            'zila_id' => @$data->districtid,
            'upazila_id' => @$data->upazilaid,
            'year' => '2023',
        ]);

        // if (!$response->ok()) {
        //     return false;
        // }
        $result = json_decode($response->getBody(), true);
        // dd($result);
        return $result;
    }

    public function getClassInfoByUid($id)
    {
        $result = StudentClassInfo::on('db_read')->where('student_uid', $id)->first();
        return $result;
    }

    public function upazillaTotalStudents($request)
    {
        $institutesArr = Institute::on('db_read')->where('upazila_uid', $request->id)->pluck('eiin')->toArray();
        $totalUpazillaStudent = Student::on('db_read')->whereIn('eiin', $institutesArr)->count();

        return $totalUpazillaStudent;
    }

    public function foreignTotalStudents()
    {
        $institutesArr = Institute::on('db_read')->where('is_foreign', 1)->pluck('eiin')->toArray();
        $totalForeignStudent = Student::on('db_read')->whereIn('eiin', $institutesArr)->count();

        return $totalForeignStudent;
    }

    public function getRelatedItemsForStudent($related_items, $id)
    {
        $attendances_info = StudentAttendance::where('student_uid', $id)->first();
        // $vw_pi_evolation_info = DB::connection('db_evaluation')->table('vw_pi_evolation')->where('student_uid', $id)->first();

        $related_items['attendances_info'] = $attendances_info;
        // $related_items['evolation_info'] = $vw_pi_evolation_info;

        return $related_items;
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $student = Student::where('uid', $id)->first();

            if (!$student) {
                throw new \Exception('Student not found');
            }

            $studentUniqueId = $student->student_unique_id;

            // Delete from attendance service

            if ($this->attendanceSyncService->isEnabled()) {
                $this->attendanceSyncService->deleteStudent($studentUniqueId);
            }

            // Delete all class info records for this student
            StudentClassInfo::where('student_uid', $id)->delete();

            // Delete student
            $student->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete student: ' . $e->getMessage(), [
                'student_id' => $id,
                'exception' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function changeStatus($student_uid, $status)
    {
        $student_class_info = StudentClassInfo::where('student_uid', $student_uid)->first();
        $student_class_info->rec_status = $status;
        $student_class_info->save();

        return $student_class_info;
    }

    public function query()
    {
        return Student::on('db_read')->newQuery();
    }
}
