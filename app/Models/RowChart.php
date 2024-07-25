<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RowChart extends Model
{
    use HasFactory;

    protected $fillable = ['name','chart_id'];

    public function rowColumns()
    {
        return $this->hasMany(RowColumn::class, 'row_id');
    }
}
