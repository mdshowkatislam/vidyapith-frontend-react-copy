<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class InventoryStore extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'name_bn',
        'name_en',
        'branch_id',
        'eiin',
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
    public function racks()
    {
        return $this->hasMany(InventoryRack::class, 'store_id', 'uid');
    }

    public function shelves()
    {
        return $this->hasMany(InventoryShelves::class, 'store_id', 'uid');
    }
    public function boxes()
    {
        return $this->hasMany(InventoryBox::class, 'store_id', 'uid');
    }


}
