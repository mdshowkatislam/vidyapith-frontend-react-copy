<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Subject extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;

    protected $fillable = [
        'uid',
        'subject_name_bn',
        'subject_name_en',
        'subject_code',
        'session',
        'eiin',
        'rec_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function monthlyTest()
    {
        return $this->hasMany(MonthlyTest::class, 'uid');
    }
}
