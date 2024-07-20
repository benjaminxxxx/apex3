<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVisibilityLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'visibility_level'
    ];

    // Relación con el modelo Post (muchos a uno)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
