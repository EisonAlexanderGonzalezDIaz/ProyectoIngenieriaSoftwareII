<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class AdministradorSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔐 Permisos específicos para el rol Administrador del Sistema
        $permisos = [
            // Gestión de Usuarios y Roles
            'crear_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',
            'ver_usuarios',
            'asignar_roles',
            'ver_roles',
            'editar_roles',

            // Configuración del sistema
            'configurar_parametros',
            'ver_configuracion_sistema',

            // Seguridad
            'ver_auditorias',
            'restaurar_datos',
            'realizar_copias_seguridad',

            // Reportes
            'ver_reportes_generales',
            'exportar_reportes',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // ⚙️ Asegurar que el rol AdministradorSistema exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'AdministradorSistema'],
            [
                'descripcion' => 'Usuario con acceso completo al sistema y gestión de roles, usuarios y configuración general.',
                'permisos' => $permisos,
            ]
        );

        // 🧩 Actualizar los permisos del rol si ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👤 Crear o actualizar el usuario administrador principal
        $user = User::updateOrCreate(
            ['email' => 'admin@colegio.edu.co'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('admin123'), // 🔒 Recomendado cambiar después del primer login
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info('✅ Usuario Administrador del Sistema creado o actualizado correctamente.');
        $this->command?->info('   Email: admin@colegio.edu.co');
        $this->command?->info('   Contraseña: admin123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}
