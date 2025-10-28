<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class AcudienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos específicos para el rol Acudiente
        $permisos = [
            // Estudiantes
            'ver_estudiantes',
            'ver_historial_academico',
            // Académico
            'ver_notas',
            // Disciplina
            'ver_reportes_disciplinarios',
            'justificar_inasistencias',
            // Comunicación
            'comunicarse_docentes',
            'ver_comunicados',
            // Reportes
            'ver_reportes_academicos',
            // Finanzas
            'ver_pagos',
            // Personales
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
            'ver_notificaciones',
            // Matrícula
            'cargar_documentos_matricula',
        ];

        // Crear o actualizar el rol Acudiente
        $rolAcudiente = RolesModel::firstOrCreate(
            ['nombre' => 'Acudiente'],
            [
                'descripcion' => 'Padre, madre o acudiente responsable del estudiante',
                'permisos' => $permisos,
            ]
        );

        // Actualizar permisos si el rol ya existía
        $rolAcudiente->permisos = $permisos;
        $rolAcudiente->save();

        // Crear usuario Acudiente por defecto
        User::updateOrCreate(
            ['email' => 'acudiente@colegio.edu.co'],
            [
                'name' => 'Acudiente',
                'email' => 'acudiente@colegio.edu.co',
                'password' => Hash::make('acudiente123'), // cambiar por una contraseña segura
                'roles_id' => $rolAcudiente->id,
                'email_verified_at' => now(),
            ]
        );

        // Mensajes de consola (manteniendo tu estilo)
        $this->command->info('Usuario Acudiente creado o actualizado exitosamente.');
        $this->command->info('Email: acudiente@colegio.edu.co');
        $this->command->info('Contraseña: acudiente123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}
