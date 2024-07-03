<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id', 'user_id', 'parent_id', 'content',
    ];
    // Relación con la tabla posts (muchos a uno)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relación con la tabla users (muchos a uno)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación recursiva con comentarios padre
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Relación recursiva con comentarios hijo
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
