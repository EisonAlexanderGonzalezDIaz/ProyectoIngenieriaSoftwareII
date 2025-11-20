<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Habilidad: isRector
         * 
         * Permite el acceso solo a usuarios cuyo rol sea "Rector".
         * Si quieres que el Administrador del Sistema también pueda entrar,
         * lo dejamos incluido en el array.
         */
        Gate::define('isRector', function ($user) {
            $rol = optional($user->rol)->nombre;

            // SOLO Rector:
            // return $rol === 'Rector';

            // Rector o AdministradorSistema (recomendado para pruebas):
            return in_array($rol, ['Rector', 'AdministradorSistema']);
        });
    }
}
