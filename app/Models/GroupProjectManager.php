<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupProjectManager extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id', 'project_id', 'manager_id'
    ];
}
