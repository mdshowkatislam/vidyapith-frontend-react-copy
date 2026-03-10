<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;

class Bi extends Model
{
    use HasFactory, SoftDeletes;
    use CreatedUId;
    use CreatedUpdatedBy;

    public $table = 'bis';
    protected $with =['weights'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'uid',
        'bi_id',
        'name_en',
        'name_bn',
        'description',
        'class_uid',
        'subject_uid',
        'created_by', 
        'updated_by',
        'chapter_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
    ];

    public function weights()
    {
        return $this->hasMany(BiWeight::class, 'bi_uid', 'uid');
    }
}
