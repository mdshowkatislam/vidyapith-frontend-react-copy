<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;

class StudentAttendance extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
}
