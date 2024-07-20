<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'administrator_id',
        'profile_image',
        'cover_image',
        'project_code',
    ];

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'manager_project', 'project_id', 'manager_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    public function groupsOfMe()
    {
        return $this->hasMany(Group::class)->where('manager_id', Auth::id());
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        } else {
            return 'https://picsum.photos/1070/440';
        }
    }
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        } else {
            return 'https://picsum.photos/100/100';
        }
    }
}
