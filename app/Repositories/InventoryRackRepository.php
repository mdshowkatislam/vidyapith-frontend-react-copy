<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryRackRepositoryInterface;

use App\Models\InventoryRack;

class InventoryRackRepository implements InventoryRackRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryRack::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryRack::create($data);
    }

    public function update($data)
    {
        $inventoryRack = InventoryRack::where('uid', $data['uid'])->first();

        if ($inventoryRack) {
            $inventoryRack->eiin = $data['eiin'];
            $inventoryRack->branch_id = $data['branch_id'];
            $inventoryRack->store_id = $data['store_id'];
            $inventoryRack->name_bn = $data['name_bn'];
            $inventoryRack->name_en = $data['name_en'];
            $inventoryRack->rec_status = $data['rec_status'];
            $inventoryRack->save();
            return $inventoryRack;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryRack::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryRack::on('db_read')->select('uid', 'name_en', 'name_bn')->where('eiin', $eiin)->get();
        } else {
            return InventoryRack::on('db_read')->select('uid', 'name_bn', 'name_en', 'branch_id','store_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryRack::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryRack::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryRack::where('uid', $id)->delete();
    }

    // public function getRelatedRacksForInventoryRack($related_items, $id)
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
    //     return InventoryRack::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
