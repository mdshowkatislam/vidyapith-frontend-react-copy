<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Group extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $fillable = [
        'uid',
        'group_name_bn',
        'group_name_en',
        'eiin',
        'rec_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
