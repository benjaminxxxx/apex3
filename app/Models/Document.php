<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'title',
        'description',
        'file_path',
        'created_by',
        'status',
        'type',
        'user_to',
        'group_id',
        'project_id'
    ];

    /**
     * Get the user who created the document
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the roles associated with the document
     */

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'document_roles', 'document_id', 'role_id');
    }
    public function getFilePhotoUrlAttribute()
    {
        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'pdf':
                $documentType = 'icon-pdf';
                break;
            case 'doc':
            case 'docx':
                $documentType = 'icon-word';
                break;
            case 'xls':
            case 'xlsx':
                $documentType = 'icon-excel';
                break;
            default:
                $documentType = 'default-icon'; // Icono por defecto
                break;
        }

        return asset('images/' . $documentType . '.svg');
    }
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
    public function getFileUrlAttribute()
    {
        // Verifica si el archivo existe en el almacenamiento público
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            // Genera la URL pública para el archivo
            return Storage::disk('public')->url($this->file_path);
        }

        // Devuelve una URL de un icono predeterminado si el archivo no existe
        return asset('images/default-icon.svg');
    }

}
