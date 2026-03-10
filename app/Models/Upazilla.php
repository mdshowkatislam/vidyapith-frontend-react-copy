<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class Upazilla extends Model
{
    use HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $table = 'upazilas';

    public function district(){
        return $this->belongsTo(District::class, 'district_id', 'uid');
    }
}
