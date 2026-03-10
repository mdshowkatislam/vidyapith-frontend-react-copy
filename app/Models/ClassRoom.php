<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;
use Awobaz\Compoships\Compoships;

class ClassRoom extends Model
{
    use Compoships;
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'class_teacher_id',
        'eiin',
        'class_id',
        'section_id',
        'session_year',
        'branch_id',
        'shift_id',
        'version_id',
        'status',
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

    public function class_teacher()
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id', 'uid')->select('uid', 'pdsid', 'caid', 'name_en', 'name_bn', 'index_number');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'uid')->select('uid', 'branch_name');
    }
    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id', 'uid')->select('uid', 'version_name');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'uid')->select('uid', 'shift_name');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'uid')->select('uid', 'section_name');
    }
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'eiin', 'eiin')->select('uid', 'eiin', 'institute_name', 'institute_name_bn', 'head_caid', 'upazila_uid', 'district_uid', 'division_uid', 'board_uid', 'phone', 'mobile', 'email', 'logo');
    }
    public function students()
    {
        return $this->hasMany(StudentClassInfo::class, 'class_room_uid', 'uid')->select('uid', 'class_room_uid', 'session_year', 'roll', 'student_uid')->where('rec_status', 1);
    }
    public function all_students()
    {
        return $this->hasMany(Student::class,['eiin', 'branch', 'shift', 'version', 'class', 'section'], ['eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id']
        )->select(['uid', 'suid', 'caid', 'roll', 'student_name_en', 'student_name_bn', 'eiin', 'branch', 'shift', 'version', 'class', 'section', 'date_of_birth', 'gender', 'religion', 'brid', 'registration_year', 'blood_group', 'father_name_en', 'father_name_bn', 'father_mobile_no', 'mother_name_en', 'mother_name_bn', 'mother_mobile_no']);
    }

    public function subject_teachers()
    {
        return $this->hasMany(SubjectTeacher::class, 'class_room_uid', 'uid');
    }
}
