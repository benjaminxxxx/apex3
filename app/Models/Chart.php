<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $fillable = [
        'data',
        'user_id',
        'type',
        'height',
        'title',
        'order_by',
        'showlabels',
        'showlegend'
    ];
}
