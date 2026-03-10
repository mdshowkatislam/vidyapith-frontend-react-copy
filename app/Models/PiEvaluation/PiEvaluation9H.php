<?php

namespace App\Models\PiEvaluation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;

class PiEvaluation9H extends Model
{
    use HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $table = 'pi_evaluations_9h';

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
    ];
}
