<?php

namespace App\Models;

use App\Contracts\AttendableInterface;
use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model implements AttendableInterface
{
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $table = 'staffs';

    protected $fillable = [
        'eiin',
        'caid',
        'uid',
        'pdsid',
        'type',
        'incremental_no',
        'institute_type',
        'index_number',
        'institute_name',
        'workstation_name',
        'branch_institute_name',
        'email',
        'mobile_no',
        'gender',
        'branch_institute_category',
        'institute_category',
        'service_break_institute',
        'name_en',
        'name_bn',
        'fathers_name',
        'mothers_name',
        'designation',
        'subject',
        'date_of_birth',
        'mpo_code',
        'nid',
        'ismpo',
        'data_source',
        'staff_source',
        'staff_type',
        'access_type',
        'role',
        'designation_id',
        'division_id',
        'district_id',
        'upazilla_id',
        'joining_year',
        'joining_date',
        'last_working_date',
        'image',
        'signature',
        'isactive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getAttendancePayload(): array
    {
        return [
            'eiin' => $this->eiin,
            'caid' => $this->caid,
            'emp_id' => $this->emp_id,
            'entity_type' => $this->getAttendanceEntityType(),
        ];
    }

    public function getAttendanceEntityType(): string
    {
        return 'staff';
    }

    public function designations()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'uid')->select('uid', 'designation_name');
    }
     /**
     * Get the division associated with the teacher
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    /**
     * Get the district associated with the teacher
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    /**
     * Get the upazilla associated with the teacher
     */
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class, 'upazilla_id', 'id');
    }

    /**
     * Get division name with fallback
     */
    public function getDivisionNameAttribute()
    {
        if ($this->division) {
            return $this->division->name_en ?? $this->division->name_bn ?? null;
        }
        return null;
    }

    /**
     * Get district name with fallback
     */
    public function getDistrictNameAttribute()
    {
        if ($this->district) {
            return $this->district->name_en ?? $this->district->name_bn ?? null;
        }
        return null;
    }

    /**
     * Get upazilla name with fallback
     */
    public function getUpazillaNameAttribute()
    {
        if ($this->upazilla) {
            return $this->upazilla->name_en ?? $this->upazilla->name_bn ?? null;
        }
        return null;
    }

    /**
     * Eager load location relationships
     */
    public function scopeWithLocations($query)
    {
        return $query->with(['division', 'district', 'upazilla']);
    }

    /**
     * Get full location string
     */
    public function getFullLocationAttribute()
    {
        $parts = [];
        
        if ($this->upazilla_name) {
            $parts[] = $this->upazilla_name;
        }
        if ($this->district_name) {
            $parts[] = $this->district_name;
        }
        if ($this->division_name) {
            $parts[] = $this->division_name;
        }
        
        return implode(', ', $parts);
    }
}
