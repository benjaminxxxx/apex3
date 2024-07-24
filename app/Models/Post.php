<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'created_by',
        'code', 
        'title', 
        'content', 
        'slug', 
        'cover_image',
        'excerpt',
        'status',
        'type'
    ];
    protected $appends = ['cover_image_url'];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'post_visibility_levels', 'post_id', 'visibility_level');
    }
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        } else {
            return 'https://picsum.photos/500/200';
        }
    }
    // Relación con la tabla categories (muchos a muchos)
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Relación con la tabla comments (uno a muchos)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relación con la tabla tags (muchos a muchos)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Relación con la tabla reactions (uno a muchos)
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
    public function visibilityLevels()
    {
        return $this->hasMany(PostVisibilityLevel::class);
    }
}
