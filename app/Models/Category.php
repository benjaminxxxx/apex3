<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'parent_id',
    ];
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    // Relación recursiva con categorías padre
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relación recursiva con categorías hijo
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories');
    }
}
