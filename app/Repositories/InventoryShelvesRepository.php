<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryShelvesRepositoryInterface;

use App\Models\InventoryShelves;

class InventoryShelvesRepository implements InventoryShelvesRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryShelves::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryShelves::create($data);
    }

    public function update($data)
    {
        $inventoryShelves = InventoryShelves::where('uid', $data['uid'])->first();

        if ($inventoryShelves) {
            $inventoryShelves->eiin = $data['eiin'];
            $inventoryShelves->branch_id = $data['branch_id'];
            $inventoryShelves->store_id = $data['store_id'];
            $inventoryShelves->name_bn = $data['name_bn'];
            $inventoryShelves->name_en = $data['name_en'];
            $inventoryShelves->rec_status = $data['rec_status'];
            $inventoryShelves->save();
            return $inventoryShelves;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryShelves::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryShelves::on('db_read')->select('uid', 'name_en', 'name_bn')->where('eiin', $eiin)->get();
        } else {
            return InventoryShelves::on('db_read')->select('uid', 'name_bn', 'name_en', 'branch_id','store_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryShelves::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryShelves::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryShelves::where('uid', $id)->delete();
    }

    // public function getRelatedShelvessForInventoryShelves($related_items, $id)
    // {
    //     $eiin = app('sso-auth')->user()->eiin;

    //     $section_list = Section::where('eiin', $eiin)->where('shift_id', $id)->get();
    //     $student_list = Student::where('eiin', $eiin)->where('shift', $id)->get();
    //     $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('shift_id', $id)->get();

    //     $related_items['section_items'] = $section_list;
    //     $related_items['student_items'] = $student_list;
    //     $related_items['subject_teachers'] = $subjectTeacher_list;

    //     return $related_items;
    // }

    // public function getByBranch($branch_id)
    // {
    //     return InventoryShelves::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
