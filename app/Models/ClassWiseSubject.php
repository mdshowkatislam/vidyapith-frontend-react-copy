<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class ClassWiseSubject extends Model
{
    // use SoftDeletes;
    use CreatedUId, HasFactory;
    protected $table = 'class_wise_subjects';

    protected $fillable = [
        'uid',
        'class_id',
        'section_id',
        'subject_id',
        'session_id',
        'eiin',
        'rec_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
