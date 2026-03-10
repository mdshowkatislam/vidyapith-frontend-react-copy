<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Traits\CreatedUId;

class Designation extends Model
{
    
    use CreatedUId;
   

    protected $fillable = [
        'uid',
        'designation_name',
        'created_at',
        'updated_at',
        
    ];

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
}
