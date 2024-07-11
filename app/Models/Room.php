<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_room', 'room_id', 'user_id')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(RoomMessage::class, 'room_id');
    }
}
