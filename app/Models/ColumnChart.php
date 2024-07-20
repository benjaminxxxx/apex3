<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumnChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chart_id'
    ];

    public function chart()
    {
        return $this->belongsTo(Chart::class);
    }

    public function partnerColumns()
    {
        return $this->hasMany(PartnerColumn::class);
    }
}
