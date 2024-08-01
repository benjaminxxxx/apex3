<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Chartpublish extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'data',
        'chart_type',
        'type',
        'title',
        'showlabels',
        'showlegend',
        'chart_id',
        'user_id',
        'project_id',
        'description',
        'created_at'
    ];

    // Si utilizas timestamps puedes definir aquí las fechas, si es necesario
    // protected $dates = ['created_at', 'updated_at'];

    // Define las relaciones si es necesario
    public function chart()
    {
        return $this->belongsTo(Chart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
