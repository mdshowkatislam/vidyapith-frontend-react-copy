<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;
use Awobaz\Compoships\Compoships;

class StudentClassInfo extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;
    use Compoships;

    protected $fillable = [
        'uid',
        'student_uid',
        'roll',
        'class_room_uid',
        'session_year',
        'rec_status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_uid', 'uid')->select(['uid', 'eiin', 'class_teacher_id', 'class_id', 'section_id', 'branch_id', 'shift_id', 'version_id', 'session_year']);
    }
    public function studentInfo()
    {
        return $this->belongsTo(Student::class, 'student_uid', 'uid');
    }
    public function student_info()
    {
        return $this->belongsTo(Student::class, 'student_uid', 'uid')->select('uid', 'suid', 'caid', 'student_name_en', 'student_name_bn', 'date_of_birth', 'gender', 'religion', 'brid', 'father_name_en', 'father_mobile_no', 'mother_name_en', 'father_name_bn', 'mother_name_bn');
    }
}
