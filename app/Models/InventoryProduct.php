<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class InventoryProduct extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'eiin',
        'branch_id',
        'category_id',
        'item_id',
        'unique_no',
        'author_name',
        'edition',
        'price',
        'quantity',
        'purchase_date',
        'supplier',

        'stock_in_at',
        'stock_in_by',
        'store_id',
        'store_name',
        'location_id',
        'location',
        'stock_out_at',
        'stock_out_by',
        'assign_by',
        'assign_type',
        'return_date',
        'actual_return',

        'rec_status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'uid')->select('uid', 'branch_name');
    }


}
