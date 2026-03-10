<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BranchRepositoryInterface;

use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Shift;
use App\Models\Student;
use App\Models\Version;

class BranchRepository implements BranchRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Branch::on('db_read')->get();
    }

    public function create($data)
    {
        return Branch::create($data);
    }

    public function update($data)
    {
        $branch = Branch::where('uid', $data['uid'])->first();
        
        if ($branch) {
            $branch->branch_name = $data['branch_name'];
            $branch->branch_name_en = $data['branch_name_en'];
            $branch->branch_location = $data['branch_location'];
            $branch->head_of_branch_id = $data['head_of_branch_id'];
            $branch->eiin = $data['eiin'];
            $branch->rec_status = $data['rec_status'];
            $branch->save();
            return $branch;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Branch::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
          \Log::info( $eiin );
        if ($optimize) {
            return Branch::on('db_read')->select('uid', 'branch_name')->where('eiin', $eiin)->get();
        } else {

            return Branch::on('db_read')->select('uid', 'branch_name', 'branch_name_en', 'branch_location', 'head_of_branch_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return Branch::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByBranchId($eiin, $optimize = null, $branch_id)
    {
        if ($optimize) {
            return Branch::on('db_read')->whereIn('uid', $branch_id)->select('uid', 'branch_name')->where('eiin', $eiin)->get();
        } else {
            return Branch::on('db_read')->whereIn('uid', $branch_id)->select('uid', 'branch_name', 'branch_name_en', 'branch_location', 'head_of_branch_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return Branch::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Branch::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Branch::where('uid', $id)->delete();
    }

    public function getRelatedItemsForBranch($related_items, $id)
    {
        // $eiin = app('sso-auth')->user()->eiin;
        $eiin = 134172;

        $related_version_list           = Version::where('eiin', $eiin)->where('branch_id', $id)->get();
        $related_shift_list             = Shift::where('eiin', $eiin)->where('branch_id', $id)->get();
        $related_section_list           = Section::where('eiin', $eiin)->where('branch_id', $id)->get();
        $related_student_list           = Student::where('eiin', $eiin)->where('branch', $id)->get();
        $related_subject_teacher_list   = ClassRoom::where('eiin', $eiin)->where('branch_id', $id)->get();

        $related_items['version_items']     = $related_version_list;
        $related_items['shift_items']       = $related_shift_list;
        $related_items['section_items']     = $related_section_list;
        $related_items['student_items']     = $related_student_list;
        $related_items['subject_teachers']  = $related_subject_teacher_list;

        return $related_items;
    }
}
