<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUId;

class Certificate extends Model
{
    use CreatedUId;

    protected $fillable = [
        'uid',
        'student_name',
        'roll_number',
        'class_name',
        'section_name',
        'grade_point',
        'total_marks',
        'exam_type',
        'exam_name',
        'academic_year',
        'merit_position',
        'school_name',
        'institute_id',
        'issue_date',
        'is_active',
    ];

    protected $casts = [
        'grade_point' => 'decimal:2',
        'total_marks' => 'integer',
        'merit_position' => 'integer',
        'institute_id' => 'integer',
        'is_active' => 'boolean',
        'issue_date' => 'datetime',
    ];
}
