<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class AttendanceConfigure extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $fillable = [
        'uid',
        'eiin',
        'branch_id',
        'shift_id',
        'version_id',
        'class_id',
        'section_id',
        'mode',
        'rules',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
