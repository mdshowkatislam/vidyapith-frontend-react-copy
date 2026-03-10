<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\StudentClassInfo;
use App\Models\SubjectTeacher;
use App\Repositories\Interfaces\ClassRoomRepositoryInterface;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ClassRoomRepository implements ClassRoomRepositoryInterface
{
    use ApiResponser;

    public function getAll()
    {
        return ClassRoom::on('db_read')->all();
    }

    public function getAllByEiin($eiin, $year = null)
    {
        if ($year) {
            return ClassRoom::on('db_read')->with('class_teacher', 'subject_teachers.teacher')->select('uid', 'class_teacher_id', 'eiin', 'class_id', 'section_id', 'session_year', 'branch_id', 'shift_id', 'version_id')->where('eiin', $eiin)->where('session_year', $year)->orderBy('class_id', 'asc')->get();
        } else {
            return ClassRoom::on('db_read')->with('subject_teachers.teacher')->select('uid', 'class_teacher_id', 'eiin', 'class_id', 'section_id', 'session_year', 'branch_id', 'shift_id', 'version_id')->where('eiin', $eiin)->orderBy('class_id', 'asc')->get();
        }
    }

    public function getAllByEiinWithPagination($eiin)
    {
        return ClassRoom::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function getById($id)
    {
        return ClassRoom::on('db_read')->with('subject_teachers')->where('uid', $id)->first();;
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $class_room = new ClassRoom();

            $class_room->eiin = app('sso-auth')->user()->eiin;
            $class_room->branch_id = $data['branch_id'];
            $class_room->shift_id = $data['shift_id'];
            $class_room->version_id = $data['version_id'];
            $class_room->class_id = $data['class_id'];
            $class_room->section_id = $data['section_id'];
            $class_room->session_year = $data['session_year'];
            $class_room->status = $data->status ?? 1;
            $class_room->class_teacher_id = $data['class_teacher_id'];
            $class_room->save();

            foreach ($data['teacher_ids'] as $subject_id => $teacher_id) {
                $subject_teacher = new SubjectTeacher();
                $subject_teacher->teacher_uid = $teacher_id;
                $subject_teacher->subject_uid = $subject_id;
                $subject_teacher->class_room_uid = $class_room->uid;
                $subject_teacher->status = 1;
                $subject_teacher->save();
            }
            DB::commit();

            return $class_room;
            // return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            $class_room = ClassRoom::where('uid', $id)->first();

            $class_room->class_teacher_id = $data['class_teacher_id'];
            $class_room->eiin = app('sso-auth')->user()->eiin;
            $class_room->class_id = $data['class_id'];
            $class_room->section_id = $data['section_id'];
            $class_room->session_year = $data['session_year'];
            $class_room->branch_id = $data['branch_id'];
            $class_room->shift_id = $data['shift_id'];
            $class_room->version_id = $data['version_id'];
            $class_room->status = $data->status ?? 1;
            $class_room->save();

            foreach ($data['teacher_ids'] as $subject_id => $teacher_id) {
                $subject_teacher = SubjectTeacher::where('class_room_uid', $class_room->uid)->where('subject_uid', $subject_id)->first();
                if ($subject_teacher) {
                    $subject_teacher = $subject_teacher;
                } else {
                    $subject_teacher = new SubjectTeacher();
                }
                $subject_teacher->teacher_uid = $teacher_id;
                $subject_teacher->subject_uid = $subject_id;
                $subject_teacher->class_room_uid = $class_room->uid;
                $subject_teacher->status = 1;
                $subject_teacher->save();
            }
            DB::commit();

            return $class_room;
            // return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function delete($id)
    {
        $result = ClassRoom::where('uid', $id)->first();

        $subject_teacher = SubjectTeacher::where('class_room_uid', $result->uid)->get();
        foreach ($subject_teacher as $item) {
            $item->delete();
        }
        $result->delete();
        return true;
    }

    public function findOrCreateClassRoom($data)
    {
        $class_room = ClassRoom::where('eiin', @$data['eiin'])
            ->where('branch_id', @$data['branch'])
            ->where('shift_id', @$data['shift'])
            ->where('version_id', @$data['version'])
            ->where('class_id', @$data['class'])
            ->where('section_id', @$data['section'])
            ->where('session_year', @$data['session_year'] ?? date('Y'))
            ->first();

        if (!$class_room) {
            $class_room = new ClassRoom();

            $class_room->eiin = @$data['eiin'];
            $class_room->branch_id = @$data['branch'];  //1781700208593819
            $class_room->shift_id = @$data['shift']; //1781700301606666
            $class_room->version_id = @$data['version']; //1781700421405498
            $class_room->class_id = @$data['class'];   //7
            $class_room->section_id = @$data['section']; //1781900473262207
            $class_room->session_year = @$data['session_year'] ?? date('Y'); //2024
            $class_room->status = 1;
            $class_room->save();
        }
        return $class_room;
    }

    public function getRelatedItemsForClassroom($related_items, $id)
    {
        $student_list = StudentClassInfo::where('class_room_uid', $id)->get();
        $subjectTeacher_list = SubjectTeacher::where('class_room_uid', $id)->whereNotNull('teacher_uid')->get();

        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        return $related_items;
    }

}
