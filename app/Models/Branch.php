<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Branch extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'branch_id',
        'branch_name',
        'branch_name_en',
        'branch_location',
        'head_of_branch_id',
        'eiin',
        'rec_status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branchHead()
    {
        return $this->belongsTo(Teacher::class, 'head_of_branch_id', 'uid')->select('uid', 'pdsid', 'caid', 'name_en', 'index_number');
    }
    
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'eiin', 'eiin');
    }

    public function finalExams()
    {
        return $this->hasMany(FinalExam::class, 'branch_id');
    }

    public function monthlyTest()
    {
        return $this->hasMany(MonthlyTest::class, 'branch_id');
    }
}
