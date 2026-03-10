<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Result extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $fillable = [
        'uid',
        'eiin',
        'branch_id',
        'class_id',
        'subject_id',
        'section_id',
        'is_submitted',
        'student_id',
        'exam_category_id',
        'exam_type',
        'mcq_mark',
        'written_mark',
        'practical_mark',
        'mark',
        'attendance',
        'behavior',
        'full_mark',
        'exam_taken_mark',
        'converted_full_mark',
        'highest_mark',
        'session',
        'year',
        'is_optional_subject',
        'is_separately_pass',
        'result_status',
        'grad_point',
        'grade',
        'is_present',
        
        'class_test_mark',
        'weekly_test_mark',
        'bi_weekly_test_mark',
        'monthly_test_mark',
        'assignment_mark',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function className()
    {
        return $this->belongsTo(ClassName::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'uid');
    }

}
