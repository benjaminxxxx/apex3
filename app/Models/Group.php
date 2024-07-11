<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'project_id', 'manager_id',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'group_partner', 'group_id', 'partner_id');
    }

   
}

