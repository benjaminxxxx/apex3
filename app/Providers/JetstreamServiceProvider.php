<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email)
                      ->orWhere('nickname', $request->email); // Asumiendo que usernick es el campo para nombre de usuario
            })
            ->first();
        
            // Verificar si el usuario existe
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => __('User not found.'),
                ]);
            }
        
            // Verificar si el usuario está bloqueado
            if ($user->status !== '1') {
                throw ValidationException::withMessages([
                    'email' => __('User is blocked.'),
                ]);
            }
        
            // Verificar la contraseña
            if (Hash::check($request->password, $user->password)) {
                return $user;
            } else {
                throw ValidationException::withMessages([
                    'password' => __('The provided password is incorrect.'),
                ]);
            }
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
