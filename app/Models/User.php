<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'lastname',
        'role_id',
        'email',
        'password',
        'user_code',
        'birthdate',
        'phone',
        'address',
        'status',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'administrator_id');
    }
    /*
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }*/
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function managedProjects()
    {
        return $this->belongsToMany(Project::class, 'manager_project', 'manager_id', 'project_id');
    }
    public function assignedProjects($managerId = null)
    {
        return $this->hasMany(ProjectManagerPartner::class, 'partner_id')
            ->when($managerId, function ($query) use ($managerId) {
                $query->where('manager_id', $managerId);
            });
    }
    /*
    public function socios()
    {
        return $this->belongsToMany(Group::class, 'group_partner', 'partner_id', 'group_id');
    }*/

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }
}
