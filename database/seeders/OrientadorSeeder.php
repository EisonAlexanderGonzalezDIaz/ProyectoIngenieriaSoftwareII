<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class OrientadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🎯 Permisos específicos del rol Orientador
        $permisos = [
            // Orientación y bienestar estudiantil
            'ver_listado_estudiantes',
            'ver_informes_conducta',
            'registrar_citaciones',
            'ver_historial_citaciones',
            'registrar_informes_psicologicos',
            'editar_informes_psicologicos',

            // Comunicación con acudientes y docentes
            'enviar_comunicados',
            'ver_mensajes_acudientes',
            'responder_mensajes_acudientes',
            'ver_mensajes_docentes',
            'responder_mensajes_docentes',

            // Seguimiento académico y disciplinario
            'consultar_faltas_disciplinarias',
            'ver_asistencia_estudiantes',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // 🧩 Asegurar que el rol Orientador exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'Orientador'],
            [
                'descripcion' => 'Rol responsable del acompañamiento psicológico y formativo de los estudiantes, gestionando el bienestar y las intervenciones institucionales.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos si el rol ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👩‍🏫 Crear o actualizar usuario Orientador
        $user = User::updateOrCreate(
            ['email' => 'orientador@colegio.edu.co'],
            [
                'name' => 'Orientador',
                'password' => Hash::make('orient123'), // 🔒 Cambiar después del primer inicio de sesión
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes en consola
        $this->command?->info('✅ Usuario Orientador creado o actualizado correctamente.');
        $this->command?->info('   Email: orientador@colegio.edu.co');
        $this->command?->info('   Contraseña: orient123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}
