<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Buscar el rol de Acudiente
        $rolAcudiente = RolesModel::where('nombre', 'Acudiente')->first();
        
        if (!$rolAcudiente) {
            $this->command->error('El rol de Rector no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario Acudiente
        User::updateOrCreate(
            ['email' => 'acudiente@colegio.edu.co'],
            [
                'name' => 'Acudiente',
                'email' => 'Acudiente@colegio.edu.co',
                'password' => Hash::make('acudiente123'), // Cambiar por una contraseña segura
                'roles_id' => $rolAcudiente->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Acudiente creado exitosamente.');
        $this->command->info('Email: acudiente@colegio.edu.co');
        $this->command->info('Contraseña: acudiente123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}