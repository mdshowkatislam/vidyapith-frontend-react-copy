<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class MarkDistribution extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $fillable = [
       'uid',
        'eiin',
        'class_id',
        'section_id',
        'subject_id',
        'exam_category_id',
        'exam_type',
        'exam_id',
        'exam_full_mark',
        'student_id',
        'mcq_mark',
        'written_mark',
        'practical_mark',
        'obtain_full_mark',
        'converted_full_mark',
        'is_submitted',
        'status',
        'remark',
        'year',
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

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_code');
    }

    public function exam()
    {
        return $this->belongsTo(ExamConfigure::class, 'exam_id', 'uid')
                    ->addSelect(['uid', 'exam_no', 'exam_name', 'mcq_mark', 'written_mark', 'practical_mark', 'exam_full_mark', 'exam_date']);
    }


}
