<?php

namespace App\Models;

use App\Contracts\AttendableInterface;
use App\Traits\CreatedUId;
use App\Traits\CreatedUpdatedBy;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model implements AttendableInterface
{
    use Compoships;
    use SoftDeletes, HasFactory;
    use CreatedUId;
    use CreatedUpdatedBy;

    protected $casts = [
        'disability_types' => 'array',
    ];

    protected $fillable = [
        'eiin',
        'suid',
        'uid',
        'caid',
        'type',
        'incremental_no',
        'student_name_bn',
        'student_name_en',
        'brid',
        'date_of_birth',
        'email',
        'registration_year',
        'religion',
        'birth_place',
        'gender',
        'board_reg_no',
        'nationality',
        'recent_study_class',
        'disability_status',
        'blood_group',
        'student_mobile_no',
        'ethnic_info',
        'branch',
        'version',
        'shift',
        'class',
        'section',
        'group',
        'roll',
        'is_regular',
        'father_name_en',
        'father_name_bn',
        'father_nid',
        'father_brid',
        'father_date_of_birth',
        'father_mobile_no',
        'mother_name_en',
        'mother_name_bn',
        'mother_nid',
        'mother_brid',
        'mother_date_of_birth',
        'mother_mobile_no',
        'guardian_name_bn',
        'guardian_name_en',
        'guardian_mobile_no',
        'guardian_nid',
        'guardian_occupation',
        'relation_with_guardian',
        'present_address',
        'permanent_address',
        'post_office',
        'division_id',
        'district_id',
        'upazilla_id',
        'unions',
        'image',
        'role',
        'student_unique_id',
        'data_source',
        'student_source',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'
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
        return 'student';
    }

    public function branch_details()
    {
        return $this->belongsTo(Branch::class, 'branch', 'uid')->select('uid', 'branch_name');
    }

    public function version_details()
    {
        return $this->belongsTo(Version::class, 'version', 'uid')->select('uid', 'version_name');
    }

    public function shift_details()
    {
        return $this->belongsTo(Shift::class, 'shift', 'uid')->select('uid', 'shift_name');
    }

    public function section_details()
    {
        return $this->belongsTo(Section::class, 'section', 'uid')->select('uid', 'section_name');
    }

    public function student_class_info()
    {
        return $this->belongsTo(StudentClassInfo::class, 'uid', 'student_uid');
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
