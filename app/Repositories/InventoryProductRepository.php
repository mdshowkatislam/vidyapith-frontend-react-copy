<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\InventoryCategory;
use App\Models\Section;
use App\Repositories\Interfaces\InventoryProductRepositoryInterface;

use App\Models\InventoryProduct;

class InventoryProductRepository implements InventoryProductRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return InventoryProduct::on('db_read')->get();
    }

    public function create($data)
    {
        return InventoryProduct::create($data);
    }

    public function update($data)
    {
        $inventoryProduct = InventoryProduct::where('uid', $data['uid'])->first();

        if ($inventoryProduct) {
            $inventoryProduct->eiin = $data['eiin'];
            $inventoryProduct->branch_id = $data['branch_id'];
            $inventoryProduct->category_id = $data['category_id'];
            $inventoryProduct->item_id = $data['item_id'];
            $inventoryProduct->unique_no = $data['unique_no'];
            $inventoryProduct->author_name = $data['author_name'];
            $inventoryProduct->edition = $data['edition'];
            $inventoryProduct->price = $data['price'];
            $inventoryProduct->quantity = $data['quantity'];
            $inventoryProduct->purchase_date = $data['purchase_date'];
            $inventoryProduct->supplier = $data['supplier'];
            $inventoryProduct->location = $data['location'];
            $inventoryProduct->rec_status = $data['rec_status'];
            $inventoryProduct->save();
            return $inventoryProduct;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return InventoryProduct::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return InventoryProduct::on('db_read')->select('uid', 'unique_no',)->where('eiin', $eiin)->get();
        } else {
            return InventoryProduct::on('db_read')->select('uid', 'eiin', 'branch_id', 'category_id','item_id', 'unique_no', 'author_name', 'edition' , 'price','quantity','purchase_date','supplier','location', 'rec_status')->where('eiin', $eiin)->get();
            // return InventoryProduct::on('db_read')->where('eiin', $eiin)->get();
        }
    }
    public function generalIndex($eiin, $optimize = null)
    {
        $matchingCategoryIds = InventoryCategory::on('db_read')
            ->where('name_en', 'LIKE', '%library%') // Adjust the 'LIKE' condition as needed
            ->pluck('uid');

        if ($optimize) {
            return InventoryProduct::on('db_read')->select('uid', 'unique_no',)->whereIn('category_id', $matchingCategoryIds)->where('eiin', $eiin)->whereNotIn('category_id', $matchingCategoryIds)->get();
        } else {
            return InventoryProduct::on('db_read')->select('uid', 'eiin', 'branch_id', 'category_id','item_id', 'unique_no', 'author_name', 'edition' , 'price','quantity','purchase_date','supplier','location', 'rec_status')->whereNotIn('category_id', $matchingCategoryIds)->where('eiin', $eiin)->get();
            // return InventoryProduct::on('db_read')->where('eiin', $eiin)->get();
        }
    }
    public function libraryIndex($eiin, $optimize = null)
    {
        $matchingCategoryIds = InventoryCategory::on('db_read')
            ->where('name_en', 'LIKE', '%library%') // Adjust the 'LIKE' condition as needed
            ->pluck('uid');

        if ($optimize) {
            return InventoryProduct::on('db_read')->select('uid', 'unique_no',)->whereIn('category_id', $matchingCategoryIds)->where('eiin', $eiin)->whereIn('category_id', $matchingCategoryIds)->get();
        } else {
            return InventoryProduct::on('db_read')->select('uid', 'eiin', 'branch_id', 'category_id','item_id', 'unique_no', 'author_name', 'edition' , 'price','quantity','purchase_date','supplier','location', 'rec_status')->whereIn('category_id', $matchingCategoryIds)->where('eiin', $eiin)->get();
            // return InventoryProduct::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return InventoryProduct::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return InventoryProduct::where('uid', $id)->delete();
    }

    // public function getRelatedProductsForInventoryProduct($related_items, $id)
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
    //     return InventoryProduct::on('db_read')->select('uid', 'shift_name_en', 'shift_name_bn')
    //             ->where('eiin', app('sso-auth')->user()->eiin)
    //             ->where('branch_id', $branch_id)
    //             ->where('rec_status', 1)
    //             ->get();
    // }
}
