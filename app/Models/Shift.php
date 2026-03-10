<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Shift extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'shift_name_bn',
        'shift_name_en',
        'shift_details',
        'branch_id',
        'shift_start_time',
        'shift_end_time',
        'eiin',
        'rec_status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'uid')->select('uid', 'branch_name');
    }

    public function finalExam()
    {
        return $this->belongsTo(FinalExam::class, 'shift_id', 'id')->select('uid', 'shift_name_en');
    }


}
