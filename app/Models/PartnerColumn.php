<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'column_chart_id',
        'data'
    ];

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function columnChart()
    {
        return $this->belongsTo(ColumnChart::class);
    }
}
