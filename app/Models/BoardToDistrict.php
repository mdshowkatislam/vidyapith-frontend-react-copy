<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;

class BoardToDistrict extends Model
{
    use SoftDeletes,CreatedUId,HasFactory;

    protected $fillable = [
        'uid',
        'board_uid',
        'district_uid',
        'rec_status',
        'sort_order',
        'created_at',
        'updated_at',
        'deleted_by',
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'created_at', 'updated_at'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_uid', 'uid');
    }
}
