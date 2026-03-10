<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class StudentTransfer extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'student_uid',
        'issue_date',
        'comment',
        'class_room_uid',
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
    public function studentInfo()
    {
        return $this->belongsTo(Student::class, 'student_uid', 'uid');
    }
    public function student_info()
    {
        return $this->belongsTo(Student::class, 'student_uid', 'uid')->select('uid', 'suid', 'caid', 'student_name_en', 'student_name_bn', 'date_of_birth', 'gender', 'religion', 'brid', 'father_name_en', 'father_mobile_no', 'mother_name_en');
    }
}
