<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Estudiante;

class AdminEstudiantesController extends Controller
{
    /**
     * Menú principal de "Consultar Estudiantes"
     */
    public function menu()
    {
        return view('admin.estudiantes.menu');
    }

    /**
     * Ver estudiantes filtrados por curso.
     */
    public function porCurso(Request $request)
    {
        // 🔹 Traer todos los cursos ordenados de forma lógica:
        // primero 1A, 1B, luego 2A, 2B, ..., hasta 11A, 11B
        $cursos = Curso::orderByRaw("
                CAST(SUBSTRING(nombre, 1, LENGTH(nombre) - 1) AS UNSIGNED),
                RIGHT(nombre, 1)
            ")
            ->get();

        $cursoSeleccionado = null;
        $estudiantes = collect();

        if ($request->filled('curso_id')) {
            // 1. Buscar el curso seleccionado
            $cursoSeleccionado = Curso::findOrFail($request->curso_id);

            // 2. Base de consulta para estudiantes de ese curso
            $query = Estudiante::where('curso_id', $cursoSeleccionado->id);

            // 🔎 (Opcional) filtro de búsqueda por nombre o identificación
            if ($request->filled('buscar')) {
                $buscar = trim($request->buscar);

                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                      ->orWhere('identificacion', 'LIKE', "%{$buscar}%");
                });
            }

            // 3. Obtener estudiantes ordenados por nombre
            $estudiantes = $query
                ->orderBy('nombre', 'asc')
                ->get();
        }

        return view('admin.estudiantes.por_curso', compact(
            'cursos',
            'cursoSeleccionado',
            'estudiantes'
        ));
    }
}
