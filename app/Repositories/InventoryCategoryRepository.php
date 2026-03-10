<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;

use App\Models\InventoryCategory;

class InventoryCategoryRepository implements InventoryCategoryRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryCategory::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryCategory::create($data);
    }

    public function update($data)
    {
        $inventoryCategory = InventoryCategory::where('uid', $data['uid'])->first();

        if ($inventoryCategory) {
            $inventoryCategory->branch_id = $data['branch_id'];
            $inventoryCategory->name_bn = $data['name_bn'];
            $inventoryCategory->name_en = $data['name_en'];
            $inventoryCategory->eiin = $data['eiin'];
            $inventoryCategory->rec_status = $data['rec_status'];
            $inventoryCategory->save();
            return $inventoryCategory;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryCategory::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryCategory::on('db_read')->select('uid', 'name_en', 'name_bn')->where('eiin', $eiin)->get();
        } else {
            return InventoryCategory::on('db_read')->select('uid', 'name_bn', 'name_en', 'branch_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryCategory::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryCategory::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryCategory::where('uid', $id)->delete();
    }

    // public function getRelatedItemsForInventoryCategory($related_items, $id)
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
    //     return InventoryCategory::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
