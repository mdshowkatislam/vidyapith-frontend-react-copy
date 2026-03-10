<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'caid',
        'eiin',
        'pdsid',
        'suid',
        'phone_no',
        'user_type_id',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function institute(){
        return $this->belongsTo(Institute::class,'caid','caid')->select('caid', 'logo');
    }
    
    public function eiin_institute(){
        return $this->belongsTo(Institute::class,'eiin','eiin')->select('eiin', 'board_uid');
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class, 'caid', 'caid');
    }

}
