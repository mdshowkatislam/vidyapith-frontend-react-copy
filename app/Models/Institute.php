<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Institute extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;
    // protected $appends = ['district_name'];
    protected $fillable = [
        'uid',
        'eiin',
        'caid',
        'division_uid',
        'district_uid',
        'upazila_uid',
        'unions',
        'institute_name',
        'institute_name_bn',
        'institute_type',
        'category',
        'level',
        'mpo',
        'phone',
        'head_caid',
        'head_of_institute_mobile',
        'mobile',
        'email',
        'logo',
        'address',
        'data_soruce',
        'institute_source',
        'role',
        'rec_status',
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

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_uid', 'uid')->select('uid','board_name_bn', 'board_name_en', 'board_code');
    }
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_uid', 'uid');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_uid', 'uid');
    }
    public function upazila()
    {
        return $this->belongsTo(Upazilla::class, 'upazila_uid', 'uid');
    }

    public function getDistrictNameAttribute()
    {
        return !empty($this->district) ? $this->district->name : null;
    }

    public function head_master()
    {
        return $this->belongsTo(Teacher::class, 'head_caid', 'caid');
    }
}
