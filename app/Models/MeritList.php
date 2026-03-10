<?php

namespace App\Models;

use App\Traits\CreatedUId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeritList extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    protected $fillable = [
        'uid',
        'eiin',
        'student_id',
        'branch_id',
        'class_id',
        'section_id',
        'exam_type',
        'position',
        'class_position',
        'is_fail',
        'total_marks',
        'year',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
