<?php

namespace App\Models\PiEvaluation;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;

class PiEvaluation6C extends Model
{
    use HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $table = 'pi_evaluations_6c';

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_uid', 'uid')->select(['uid', 'class_id']);
    }
}
