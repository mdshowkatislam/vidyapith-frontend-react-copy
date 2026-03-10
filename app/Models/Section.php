<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Section extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $fillable = [
        'uid',
        'section_name',
        'section_name_en',
        'section_details',
        'section_year',
        'class_id',
        'shift_id',
        'version_id',
        'branch_id',
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
        return $this->hasOne(Branch::class, 'uid','branch_id')->select('uid', 'branch_name');
    }

    public function version()
    {
        return $this->hasOne(Version::class, 'uid','version_id')->select('uid', 'version_name');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'uid','shift_id')->select('uid', 'shift_name');
    }

    public function monthlyTest()
    {
        return $this->hasMany(MonthlyTest::class, 'uid');
    }

}
