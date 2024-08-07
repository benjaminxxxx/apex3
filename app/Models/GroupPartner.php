<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPartner extends Model
{
    use HasFactory;
    protected $table = 'group_partner';
    protected $fillable = [
        'group_id', 'partner_id'
    ];
}
