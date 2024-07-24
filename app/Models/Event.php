<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'code','title', 'slug', 'start_date', 'end_date', 'organizer', 'phone', 'email', 'location', 'website', 'map', 'content', 'type', 'created_by','cover_image'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'event_roles', 'event_id', 'role_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories');
    }
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
    public function getFileUrlAttribute()
    {
        // Verifica si el archivo existe en el almacenamiento público
        if ($this->file_path && Storage::exists('public/' . $this->file_path)) {
            // Genera la URL pública para el archivo
            return Storage::url($this->file_path);
        }
        
        // Devuelve una URL de un icono predeterminado si el archivo no existe
        return asset('images/default-icon.svg');
    }
}
