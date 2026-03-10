<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class WeeklyTest extends Model
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
        'subject_code',
        'exam_no',
        'exam_name',
        'mcq_mark',
        'written_mark',
        'practical_mark',
        'exam_full_mark',
        'exam_date',
        'exam_time',
        'exam_details_info',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
