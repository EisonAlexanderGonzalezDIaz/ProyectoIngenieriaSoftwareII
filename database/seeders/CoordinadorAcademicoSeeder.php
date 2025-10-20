<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class CoordinadorAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Coordinador Academico
        $rolCoordinador = RolesModel::where('nombre', 'CoordinadorAcademico')->first();

        if (!$rolCoordinador) {
            $this->command->error('El rol de Coordinador Academico no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario coordinador
        User::updateOrCreate(
            ['email' => 'coordinador@colegio.edu.co'],
            [
                'name' => 'CoordinadorAcademico',
                'email' => 'coordinador@colegio.edu.co',
                'password' => Hash::make('cooracad123'), // Cambiar por una contraseña segura
                'roles_id' => $rolCoordinador->id,
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info('Usuario Coordinador Academico creado exitosamente.');
        $this->command->info('Email: coordinador@colegio.edu.co');
        $this->command->info('Contraseña: cooracad123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}