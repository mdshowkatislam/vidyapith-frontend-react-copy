<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\ShiftRepositoryInterface;

use App\Models\Shift;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Shift::on('db_read')->get();
    }

    public function create($data)
    {
        // basic validation rules for create
        $rules = [
            'shift_name_bn' => 'required|string|max:255',
            'shift_name_en' => 'required|string|max:255',
            'branch_id' => 'required|integer',
            'shift_start_time' => 'nullable|date_format:H:i',
            'shift_end_time' => 'nullable|date_format:H:i',
            'rec_status' => 'nullable|in:0,1',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }
        // check pre-existing shift for same eiin, branch and name
        $exists = Shift::where(function ($q) use ($data) {
                $q->where('shift_name_en', $data['shift_name_en'])
                  ->orWhere('shift_name_bn', $data['shift_name_bn']);
            })->exists();

        if ($exists) {
            throw new \Exception('Shift with same name already exists for this branch');
        }

        $shift = new Shift();
        $shift->shift_name_bn = $data['shift_name_bn'];
        $shift->shift_name_en = $data['shift_name_en'];
        $shift->shift_details = $data['shift_details'] ?? null;
        $shift->branch_id = $data['branch_id'];
        $shift->shift_start_time = $data['shift_start_time'] ?? null;
        $shift->shift_end_time = $data['shift_end_time'] ?? null;
        $shift->eiin = getAuthInfo()['eiin'];
        $shift->rec_status = $data['rec_status'] ?? 1;
        $shift->save();

        return $shift;
    }

    public function update($data)
    {
        $shift = Shift::where('uid', $data['uid'])->first();

        if ($shift) {
            $shift->shift_name_bn = $data['shift_name_bn'];
            $shift->shift_name_en = $data['shift_name_en'];
            $shift->shift_details = $data['shift_details'];
            $shift->branch_id = $data['branch_id'];
            $shift->shift_start_time = $data['shift_start_time'];
            $shift->shift_end_time = $data['shift_end_time'];
            $shift->eiin=getAuthInfo()['eiin'];  ;
            $shift->rec_status = $data['rec_status'];
            $shift->save();
            return $shift;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
      
        return Shift::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return Shift::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')->where('eiin', $eiin)->get();
        } else {
            return Shift::on('db_read')->select('uid', 'shift_name_bn', 'shift_name_en', 'shift_details', 'branch_id', 'shift_start_time', 'shift_end_time', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return Shift::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByShiftId($eiin, $optimize = null, $shift_id)
    {
        if ($optimize) {
            return Shift::on('db_read')->whereIn('uid', $shift_id)->select('uid', 'shift_name_en', 'shift_name_bn')->where('eiin', $eiin)->get();
        } else {
            return Shift::on('db_read')->whereIn('uid', $shift_id)->select('uid', 'shift_name_bn', 'shift_name_en', 'shift_details', 'branch_id', 'shift_start_time', 'shift_end_time', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return Shift::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Shift::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Shift::where('uid', $id)->delete();
    }

    public function getRelatedItemsForShift($related_items, $id)
    {
        $eiin = app('sso-auth')->user()->eiin;

        $section_list = Section::where('eiin', $eiin)->where('shift_id', $id)->get();
        $student_list = Student::where('eiin', $eiin)->where('shift', $id)->get();
        $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('shift_id', $id)->get();

        $related_items['section_items'] = $section_list;
        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        return $related_items;
    }

    public function getByBranch($branch_id)
    {
        return Shift::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
                ->where('eiin', app('sso-auth')->user()->eiin)
                ->where('branch_id', $branch_id)
                ->where('rec_status', 1)
                ->get();
    }
}
