<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerProject extends Model
{
    use HasFactory;
    protected $fillable = [
        'manager_id', 'project_id'
    ];
}
