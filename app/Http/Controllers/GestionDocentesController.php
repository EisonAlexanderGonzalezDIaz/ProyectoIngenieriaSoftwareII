<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionDocentesController extends Controller
{
    /**
     * Mostrar la vista de gestión de docentes.
     * Devuelve datos de ejemplo para que la vista funcione sin base de datos.
     */
    public function gestion()
    {
        // Datos de ejemplo — así no dependes de la DB todavía
        $docentes = [
            (object)[ 'id' => 1, 'nombre' => 'Prof. García',  'materia' => 'Matemáticas',   'email' => 'garcia@example.com',  'estado' => 'Activo' ],
            (object)[ 'id' => 2, 'nombre' => 'Prof. López',   'materia' => 'Español',       'email' => 'lopez@example.com',   'estado' => 'Activo' ],
            (object)[ 'id' => 3, 'nombre' => 'Prof. Martínez','materia' => 'Inglés',        'email' => 'martinez@example.com','estado' => 'Inactivo' ],
        ];

        // Puedes enviar más datos si los necesitas (filtros, estadísticas, etc)
        return view('gestiondocentes.gestion', compact('docentes'));
    }
}
