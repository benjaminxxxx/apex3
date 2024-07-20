<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Chart extends Model
{
    protected $fillable = [
        'chart_id',
        'data',
        'user_id',
        'type',
        'height',
        'title',
        'order_by',
        'showlabels',
        'showlegend',
        'group_id'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $model->chart_id = Str::random(10);
            } while (self::where('chart_id', $model->chart_id)->exists());
        });
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function columns()
    {
        return $this->hasMany(ColumnChart::class);
    }
}
