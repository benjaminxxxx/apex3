<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'role_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
