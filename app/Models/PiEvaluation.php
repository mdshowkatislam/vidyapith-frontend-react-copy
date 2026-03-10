<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;
use Awobaz\Compoships\Compoships;

class PiEvaluation extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;
    use Compoships;

    protected $fillable = [
        'uid',
        'evaluate_type',
        'bi_uid',
        'weight_uid',
        'student_uid',
        'teacher_uid',
        'class_room_uid',
        'submit_status',
        'is_approved',
        'remark',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'
    ];

    public function get_self_pi_evaluation(){
        return  $this->hasMany(PiEvaluation::class, ['pi_uid', 'student_uid'], ['pi_uid', 'student_uid']);
    }
    public function teacher_wise_subject_list(){
        return  $this->hasMany(SubjectTeacher::class, 'teacher_uid', 'teacher_uid');
    }
    public function class_room_wise_subject_list(){
        return  $this->hasMany(SubjectTeacher::class, 'class_room_uid', 'class_room_uid');
    }
}
