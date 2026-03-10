<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class CombineSubject extends Model
{
    use HasFactory;
    use CreatedUId;

   protected $fillable = ['uid', 'eiin', 'combine_name_bn', 'combine_name_en', 'subjects', 'status'];

    protected $casts = [
        'subjects' => 'array',
    ];
}
