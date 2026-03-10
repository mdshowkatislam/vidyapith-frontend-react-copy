<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;

class InstituteCategory extends Model
{
    use HasFactory, CreatedUId,SoftDeletes;

    protected $fillable = [
        'uid',
        'title_en',
        'title_bn',
        'rec_status',
        'sort_order',
        'created_at',
        'updated_at',
    ];
    
    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'created_at', 'updated_at'
    ];
}
