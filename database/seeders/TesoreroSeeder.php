<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class TesoreroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info(' Iniciando creación del usuario Tesorero...');

        // Buscar el rol de Tesorero
        $rolTesorero = RolesModel::where('nombre', 'Tesorero')->first();

        if (!$rolTesorero) {
            $this->command->error(' El rol de Tesorero no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Permisos específicos del Tesorero (coherentes con RolesSeeder)
        $permisosTesorero = [
            'gestionar_pagos_matriculas',
            'gestionar_facturacion',
            'gestionar_devoluciones',
            'gestionar_carteras',
            'generar_reportes_financieros',
            'gestionar_becas_y_descuentos',
            'consultar_estado_de_cuentas',
            'gestionar_paz_y_salvo'
        ];

        // Crear o actualizar usuario Tesorero
        $usuario = User::updateOrCreate(
            ['email' => 'tesorero@colegio.edu.co'],
            [
                'name' => 'Tesorero',
                'email' => 'tesorero@colegio.edu.co',
                'password' => Hash::make('tesorero123'), // Cambiar por una contraseña segura
                'roles_id' => $rolTesorero->id,
                'email_verified_at' => now(),
            ]
        );

        // Asignar permisos directamente al rol si el modelo lo soporta
        if (method_exists($rolTesorero, 'permisos')) {
            $rolTesorero->update(['permisos' => $permisosTesorero]);
        }

        $this->command->info(' Usuario Tesorero creado o actualizado exitosamente.');
        $this->command->info(' Email: tesorero@colegio.edu.co');
        $this->command->info(' Contraseña: tesorero123');
        $this->command->warn(' ¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}
