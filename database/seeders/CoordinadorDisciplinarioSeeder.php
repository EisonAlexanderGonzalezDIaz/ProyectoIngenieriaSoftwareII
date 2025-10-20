<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class CoordinadorDisciplinarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Coordinador Disciplinario
        $rolCoordinadorDisciplinario = RolesModel::where('nombre', 'CoordinadorDisciplinario')->first();

        if (!$rolCoordinadorDisciplinario) {
            $this->command->error('El rol de Coordinador Disciplinario no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario coordinador
        User::updateOrCreate(
            ['email' => 'coordinadordisciplinario@colegio.edu.co'],
            [
                'name' => 'CoordinadorDisciplinario',
                'email' => 'coordinadordisciplinario@colegio.edu.co',
                'password' => Hash::make('coordisc123'), // Cambiar por una contraseña segura
                'roles_id' => $rolCoordinadorDisciplinario->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Coordinador Disciplinario creado exitosamente.');
        $this->command->info('Email: coordinadordisciplinario@colegio.edu.co');
        $this->command->info('Contraseña: coordisc123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}