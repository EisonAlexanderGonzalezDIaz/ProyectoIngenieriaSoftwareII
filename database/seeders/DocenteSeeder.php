<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Docente
        $rolDocente = RolesModel::where('nombre', 'Docente')->first();

        if (!$rolDocente) {
            $this->command->error('El rol de Docente no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario docente
        User::updateOrCreate(
            ['email' => 'docente@colegio.edu.co'],
            [
                'name' => 'Docente',
                'email' => 'docente@colegio.edu.co',
                'password' => Hash::make('doc123'), // Cambiar por una contraseña segura
                'roles_id' => $rolDocente->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Docente creado exitosamente.');
        $this->command->info('Email: docente@colegio.edu.co');
        $this->command->info('Contraseña: doc123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}