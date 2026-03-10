<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class PiBiReview extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'subject_uid',
        'teacher_uid',
        'class_room_uid',
        'remark',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'deleted_by'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_uid', 'uid')->select('uid', 'pdsid', 'caid', 'name_en', 'name_bn', 'eiin', 'designation');
    }
    public function class_room()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_uid', 'uid');
    }
}
