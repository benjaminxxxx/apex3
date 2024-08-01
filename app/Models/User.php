<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'lastname',
        'role_id',
        'email',
        'password',
        'user_code',
        'birthdate',
        'phone',
        'address',
        'status',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public static function managers()
    {
        return self::where('role_id', 3)->get();
    }

    public function partners()
    {
        return $this->hasMany(User::class, 'created_by');
    }
    public function hasPermission($permissionName)
    {
        $role = $this->role;
        if (!$role) {
            return false;
        }

        return $role->permissions()->where('name', $permissionName)->exists();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'administrator_id');
    }
    /*
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }*/
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'manager_id');
    }
    protected function getProjectsStringAttribute()
    {
        return $this->managedProjects->pluck('name')->implode(', ');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->name . ($this->lastname ? ' ' . $this->lastname : '')
        );
    }
    public function groupPartners()
    {
        return $this->belongsToMany(Group::class, 'group_partner', 'partner_id', 'group_id');
    }

    // Relación para amistades donde user_id es el ID del usuario
    public function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    // Relación para amistades donde friend_id es el ID del usuario
    public function friendsOf(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    // Método accesor para combinar ambas relaciones
    public function getFriendsAttribute()
    {
        return $this->friendsOfMine->merge($this->friendsOf);
    }

    public function getChatDetails()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_photo_url' => $this->profile_photo_url,
            'user_code' => $this->user_code,
            'role_name' => $this->role->role_name,
            'fullName' => $this->fullName,
        ];
    }

    public function getCategories()
    {
        $userRoleId = $this->role_id;

        if ($userRoleId == 1) {
            return Category::withCount([
                'posts' => function ($query) {
                    $query->where('type', 'noticia');
                }
            ])->having('posts_count', '>', 0)->get();
        } else {
            return Category::whereHas('posts', function ($query) use ($userRoleId) {
                $query->where('type', 'noticia')
                    ->whereHas('visibilityLevels', function ($query) use ($userRoleId) {
                        $query->where('visibility_level', $userRoleId);
                    });
            })->withCount([
                        'posts' => function ($query) use ($userRoleId) {
                            $query->where('type', 'noticia')
                                ->whereHas('visibilityLevels', function ($query) use ($userRoleId) {
                                    $query->where('visibility_level', $userRoleId);
                                });
                        }
                    ])->having('posts_count', '>', 0)->get();
        }
    }

    public function getMyNews($offset = 0, $type = 1, $take = 5)
    {
        $userRoleId = $this->role_id;

        $query = Post::where('type', $type)
            ->with('categories')
            ->latest()
            ->skip($offset)
            ->take($take);

        if ($userRoleId != 1 && $userRoleId != 2) {
            $query->whereHas('visibilityLevels', function ($query) use ($userRoleId) {
                $query->where('visibility_level', $userRoleId);
            });
        }

        return $query->get();
    }
    public function assignedProjects($managerId = null)
    {
        return $this->hasManyThrough(
            Project::class,
            Group::class,
            'id', // Foreign key on the groups table
            'id', // Foreign key on the projects table
            'id', // Local key on the partners table
            'project_id' // Local key on the groups table
        )->when($managerId, function ($query) use ($managerId) {
            $query->whereHas('groups', function ($query) use ($managerId) {
                $query->where('manager_id', $managerId);
            });
        });
    }
    public function managedProjects()
    {
        return $this->belongsToMany(Project::class, 'groups', 'manager_id', 'project_id');
    }
    public function projectsAsPartner()
    {
        return Project::whereHas('groups', function (Builder $query) {
            $query->whereHas('partners', function (Builder $query) {
                $query->where('partner_id', $this->id);
            });
        })->get();
    }
    public function hasPermissionToManage($projectId)
    {
        // Verificar si el usuario es administrador del proyecto
        $isAdmin = $this->role_id == 2;

        // Verificar si el usuario es manager asignado al proyecto
        $isManager = $this->role_id == 3 && $this->managedProjects()->where('projects.id', $projectId)->exists();

        // Verificar si el usuario es socio asignado a algún grupo del proyecto
        $isPartner = $this->role_id == 4 && Group::where('project_id', $projectId)
            ->whereHas('partners', function ($query) {
                $query->where('partner_id', $this->id);
            })->exists();

        return $isAdmin || $isManager || $isPartner;
    }
    public function belongsToGroup($groupId)
    {
        if ($this->role_id == 4) {
            return $this->groupPartners()->where('group_id', $groupId)->exists();
        } elseif ($this->role_id == 3) {
            return $this->myGroupsManager($groupId);
        } elseif ($this->role_id == 2) {
            return true;
        } elseif ($this->role_id == 1) {
            return true;
        }
        return false;
    }
    public function myGroupsManager($groupId)
    {
        if ($this->role_id == 3) {
            return Group::where('id', $groupId)
                ->where('manager_id', $this->id)
                ->exists();
        }
        return false;
    }
    public function myGroups($projectId)
    {
        // Si el usuario es administrador, obtener todos los grupos del proyecto
        if ($this->role_id == 2) {
            return Group::where('project_id', $projectId)->get();
        }

        // Si el usuario es manager, obtener solo los grupos que él ha creado
        if ($this->role_id == 3) {
            return Group::where('project_id', $projectId)
                ->where('manager_id', $this->id)
                ->get();
        }

        if ($this->role_id == 4) {

            return Group::where('project_id', $projectId)
                ->whereHas('partners', function ($query) {
                    $query->where('partner_id', $this->id);
                })->get();
        }

        // Si el usuario tiene otro rol, no devolver ningún grupo
        return collect();
    }
    public function isAllowedToViewEvent($eventId)
    {
        $userRoleId = $this->role_id;

        $isSuperAdmin = $userRoleId == 1;

        $isAdmin = $userRoleId == 2;

        // Verificar si el usuario tiene acceso basado en su rol
        $hasRoleAccess = EventRole::where('event_id', $eventId)
            ->where('role_id', $userRoleId)
            ->exists();

        // Verificar si el usuario es el creador del evento
        $isCreator = Event::where('id', $eventId)
            ->where('created_by', $this->id)
            ->exists();

        // El usuario puede ver el evento si tiene acceso basado en su rol o es el creador del evento
        return $isSuperAdmin || $isAdmin || $hasRoleAccess || $isCreator;
    }
    public function isAllowedToViewArticle($articleId)
    {
        $userRoleId = $this->role_id;

        $isSuperAdmin = $userRoleId == 1;

        $isAdmin = $userRoleId == 2;

        // Verificar si el usuario tiene acceso basado en su rol
        $hasRoleAccess = PostVisibilityLevel::where('post_id', $articleId)
            ->where('visibility_level', $userRoleId)
            ->exists();

        // Verificar si el usuario es el creador de la noticia
        $isCreator = Post::where('id', $articleId)
            ->where('created_by', $this->id)
            ->exists();

        // El usuario puede ver el evento si tiene acceso basado en su rol o es el creador de la noticia
        return $isSuperAdmin || $isAdmin || $hasRoleAccess || $isCreator;
    }
    public function news()
    {
        // Obtener el role_id del usuario autenticado
        $roleId = $this->role_id;

        // Si el role_id es 1 o 2, devolver una query sin restricciones
        if (in_array($roleId, [1, 2])) {
            return Post::query();
        }

        // Obtener los IDs de los posts que tienen el mismo nivel de visibilidad que el role_id del usuario
        $postIds = PostVisibilityLevel::where('visibility_level', $roleId)
            ->pluck('post_id');

        // Devolver una query para los posts que coinciden con los IDs obtenidos
        return Post::whereIn('id', $postIds);
    }
    public function events()
    {
        // Obtener el role_id del usuario autenticado
        $roleId = $this->role_id;

        // Si el role_id es 1 o 2, devolver una query sin restricciones
        if (in_array($roleId, [1, 2])) {
            return Event::query();
        }

        // Obtener los IDs de los events que tienen el mismo nivel de visibilidad que el role_id del usuario
        $eventsIds = EventRole::where('role_id', $roleId)
            ->pluck('event_id');

        // Devolver una query para los posts que coinciden con los IDs obtenidos
        return Event::whereIn('id', $eventsIds);
    }
    public function documents()
    {
        // Obtener el role_id del usuario autenticado
        $roleId = $this->role_id;

        // Si el role_id es 1 o 2, devolver una query sin restricciones
        if (in_array($roleId, [1, 2])) {
            return Document::query();
        }

        // Obtener los IDs de los documents que tienen el mismo nivel de visibilidad que el role_id del usuario
        $documentsIds = DocumentRole::where('role_id', $roleId)
            ->pluck('document_id');

        // Devolver una query para los documents que coinciden con los IDs obtenidos
        return Document::whereIn('id', $documentsIds);
    }
    public function getChartPublishes()
    {
        // Obtener el role_id del usuario autenticado
        $roleId = $this->role_id;

        // Si el role_id es 1 o 2 (Super Administrador o Administrador), devolver todos los gráficos publicados sin restricciones
        if (in_array($roleId, [1, 2])) {
            return Chartpublish::query();
        }

        if ($roleId == 3) {
            // Si es Manager, obtener todos los gráficos donde el manager tiene proyectos a cargo
            $projectIds = $this->managedProjects()->pluck('project_id')->toArray();
            return Chartpublish::whereIn('project_id', $projectIds);
        }

        if ($roleId == 4) {
            // Si es Socio, obtener todos los gráficos para los proyectos en los que es socio
            $projectIds = $this->projectsAsPartner()->pluck('id')->toArray();
            return Chartpublish::whereIn('project_id', $projectIds);
        }

        // Si no coincide con ninguno de los roles anteriores, devolver una consulta vacía
        return Chartpublish::whereRaw('1 = 0'); // Consulta vacía
    }
}
