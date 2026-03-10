<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Assignment extends Model
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
        'assignment_no',
        'assignment_name',
        'subject_code',
        'mcq_mark',
        'written_mark',
        'practical_mark',
        'assignment_full_mark',
        'assignment_submission_date',
        'assignment_details_info',
        'status',
    ];

    // protected $casts = [
    //     'section_id' => 'array',
    //     'assignment_submission_date' => 'datetime',
    //     'status' => 'boolean',
    // ];

}
