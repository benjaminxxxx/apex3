<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'manager_id',
        'project_id',
        'administrator_id'
    ];
    public function inversiones()
    {
        return $this->hasMany(Inversion::class, 'grupo_id');
    }
    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'group_partner', 'group_id', 'partner_id');
    }
    public function charts()
    {
        return $this->hasMany(Chart::class);
    }
}
