<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;
use App\Models\RolesModel;

class AdminEstudiantesController extends Controller
{
    /**
     * Menú principal de "Consultar Estudiantes".
     *
     * Para simplificar, usamos la misma lógica de porCurso()
     * para que el AdministradorSistema vea directamente
     * el filtro por curso + listado de estudiantes.
     */
    public function menu(Request $request)
    {
        return $this->porCurso($request);
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

                // 3. Query base por curso
                $query = User::query()
                    ->where('curso_id', $cursoSeleccionado->id);

                // Si existe el rol, filtramos por ese rol
                if ($rolEstudiante) {
                    $query->where('roles_id', $rolEstudiante->id);
                }

                // 4. Traer estudiantes ordenados por nombre
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
