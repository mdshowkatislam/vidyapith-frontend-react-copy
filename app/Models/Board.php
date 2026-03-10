<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Board extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
    ];
    
    protected $fillable = [
        'uid',
        'board_name_bn',
        'board_name_en',
        'board_short_name',
        'board_code',
        'rec_status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function institute_category()
    {
        return $this->belongsTo(InstituteCategory::class, 'institute_category_uid', 'uid');
    }
}
