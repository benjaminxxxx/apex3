<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RowColumn extends Model
{
    use HasFactory;
    protected $table = "row_column";

    protected $fillable = ['row_id', 'column_id', 'data'];

    public function rowChart()
    {
        return $this->belongsTo(RowChart::class, 'row_id');
    }

    public function columnChart()
    {
        return $this->belongsTo(ColumnChart::class, 'column_id');
    }
}
