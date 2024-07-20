<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'role_id',
    ];

    /**
     * Get the document associated with the DocumentRole
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the role associated with the DocumentRole
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
