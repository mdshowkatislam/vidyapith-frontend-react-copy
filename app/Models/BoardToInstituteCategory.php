<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;

class BoardToInstituteCategory extends Model
{
    use SoftDeletes,CreatedUId,HasFactory,CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'board_uid',
        'institute_category_uid',
        'rec_status',
        'sort_order',
        'created_at',
        'updated_at',
        'deleted_by',
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'created_at', 'updated_at'
    ];

    public function institute_category()
    {
        return $this->belongsTo(InstituteCategory::class, 'institute_category_uid', 'uid');
    }


}