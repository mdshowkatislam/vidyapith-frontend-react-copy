<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Traits\CreatedUId;

class LibFineConfiguration extends Model
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    protected $table = 'lab_fine_configures';
    protected $fillable = [
        'uid',
        'eiin',
        'branch_id',
        'fine_type',
        'fine_amount',
        'damage_fine_amount',
        'loss_fine_amount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
