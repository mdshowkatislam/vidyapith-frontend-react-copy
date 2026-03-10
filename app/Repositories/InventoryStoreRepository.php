<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryStoreRepositoryInterface;

use App\Models\InventoryStore;

class InventoryStoreRepository implements InventoryStoreRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryStore::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryStore::create($data);
    }

    public function update($data)
    {
        $inventoryStore = InventoryStore::where('uid', $data['uid'])->first();

        if ($inventoryStore) {
            $inventoryStore->branch_id = $data['branch_id'];
            $inventoryStore->name_bn = $data['name_bn'];
            $inventoryStore->name_en = $data['name_en'];
            $inventoryStore->eiin = $data['eiin'];
            $inventoryStore->rec_status = $data['rec_status'];
            $inventoryStore->save();
            return $inventoryStore;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryStore::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryStore::on('db_read')->select('uid', 'name_en', 'name_bn')->where('eiin', $eiin)->get();
        } else {
            return InventoryStore::on('db_read')->select('uid', 'name_bn', 'name_en', 'branch_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryStore::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryStore::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryStore::where('uid', $id)->delete();
    }

    // public function getRelatedItemsForInventoryStore($related_items, $id)
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
    //     return InventoryStore::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
