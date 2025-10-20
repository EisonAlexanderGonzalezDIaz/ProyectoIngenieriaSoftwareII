<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Buscar el rol de Administrador Sistema
        $rolAdmin = RolesModel::where('nombre', 'AdministradorSistema')->first();

        if (!$rolAdmin) {
            $this->command->error('El rol de Administrador Sistema no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@colegio.edu.co'],
            [
                'name' => 'AdministradorSistema',
                'email' => 'admin@colegio.edu.co',
                'password' => Hash::make('admin123'), // Cambiar por una contraseña segura
                'roles_id' => $rolAdmin->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Administrador creado exitosamente.');
        $this->command->info('Email: admin@colegio.edu.co');
        $this->command->info('Contraseña: admin123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}