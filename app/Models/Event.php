<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'code','title', 'slug', 'start_date', 'end_date', 'organizer', 'phone', 'email', 'location', 'website', 'map', 'content', 'type', 'created_by','cover_image','project_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'event_roles', 'event_id', 'role_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories');
    }
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('uploads/' . $this->cover_image);
        } else {
            return 'https://picsum.photos/500/200';
        }
    }
  
}
