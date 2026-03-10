<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryItemRepositoryInterface;

use App\Models\InventoryItem;

class InventoryItemRepository implements InventoryItemRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryItem::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryItem::create($data);
    }

    public function update($data)
    {
        $inventoryItem = InventoryItem::where('uid', $data['uid'])->first();

        if ($inventoryItem) {
            $inventoryItem->eiin = $data['eiin'];
            $inventoryItem->branch_id = $data['branch_id'];
            $inventoryItem->category_id = $data['category_id'];
            $inventoryItem->name_bn = $data['name_bn'];
            $inventoryItem->name_en = $data['name_en'];
            $inventoryItem->specification = $data['specification'];
            $inventoryItem->rec_status = $data['rec_status'];
            $inventoryItem->save();
            return $inventoryItem;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryItem::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryItem::on('db_read')->select('uid', 'name_en', 'name_bn')->where('eiin', $eiin)->get();
        } else {
            return InventoryItem::on('db_read')->select('uid', 'name_bn', 'name_en', 'branch_id','category_id', 'specification', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryItem::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryItem::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryItem::where('uid', $id)->delete();
    }

    // public function getRelatedItemsForInventoryItem($related_items, $id)
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
    //     return InventoryItem::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
