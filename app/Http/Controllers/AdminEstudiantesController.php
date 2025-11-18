<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;
use App\Models\RolesModel;

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
        // Todos los cursos ordenados por nombre (1A, 1B, 2A, ..., 11B)
        $cursos = Curso::orderByRaw("CAST(SUBSTRING(nombre, 1, LENGTH(nombre) - 1) AS UNSIGNED) ASC")
               ->orderByRaw("RIGHT(nombre, 1) ASC")
               ->get();

        $cursoSeleccionado = null;
        $estudiantes = collect();

        if ($request->filled('curso_id')) {

            // 1. Buscar el curso seleccionado
            $cursoSeleccionado = Curso::find($request->curso_id);

            if ($cursoSeleccionado) {

                // 2. Buscar el rol "Estudiante"
                $rolEstudiante = RolesModel::where('nombre', 'Estudiante')->first();

                // Si existe el rol, filtramos por ese rol
                $query = User::query()
                    ->where('curso_id', $cursoSeleccionado->id);

                if ($rolEstudiante) {
                    $query->where('roles_id', $rolEstudiante->id);
                }

                // 3. Traer estudiantes ordenados por nombre
                $estudiantes = $query
                    ->orderBy('name', 'asc')
                    ->get();
            }
        }

        return view('admin.estudiantes.por_curso', compact(
            'cursos',
            'cursoSeleccionado',
            'estudiantes'
        ));
    }
}
