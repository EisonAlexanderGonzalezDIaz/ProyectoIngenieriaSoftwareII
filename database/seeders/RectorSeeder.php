<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class RectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🎯 Permisos específicos del rol Rector
        $permisos = [
            // Gestión institucional
            'ver_dashboard_institucional',
            'gestionar_usuarios',
            'gestionar_roles_y_permisos',
            'gestionar_cursos',
            'ver_reportes_generales',
            'aprobar_informes',
            'ver_informes_financieros',
            'ver_informes_disciplinarios',
            'ver_informes_academicos',
            'ver_informes_asistencia',

            // Comunicación institucional
            'enviar_comunicados_generales',
            'ver_mensajes_importantes',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // 🧩 Crear o actualizar el rol Rector
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'Rector'],
            [
                'descripcion' => 'Rol directivo máximo de la institución educativa, responsable de la gestión administrativa, académica y disciplinaria.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos si ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👨‍💼 Crear o actualizar usuario Rector
        $user = User::updateOrCreate(
            ['email' => 'rector@colegio.edu.co'],
            [
                'name' => 'Rector del Colegio',
                'password' => Hash::make('rector123'), // 🔒 Cambiar después del primer inicio de sesión
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes en consola
        $this->command?->info('✅ Usuario Rector creado o actualizado correctamente.');
        $this->command?->info('   Email: rector@colegio.edu.co');
        $this->command?->info('   Contraseña: rector123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}
