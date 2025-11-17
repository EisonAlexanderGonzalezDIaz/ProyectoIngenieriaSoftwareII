<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante; // Ajusta al nombre real de tu modelo
use App\Models\Curso;      // Ajusta al nombre real de tu modelo

class AdminEstudiantesController extends Controller
{
    /**
     * Menú de opciones al hacer clic en "Consultar Estudiantes"
     * (solo para AdministradorSistema).
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
        $cursos = Curso::all();

        $cursoSeleccionado = null;
        $estudiantes = collect();

        if ($request->filled('curso_id')) {
            $cursoSeleccionado = Curso::findOrFail($request->curso_id);
            $estudiantes = Estudiante::where('curso_id', $cursoSeleccionado->id)->get();
        }

        return view('admin.estudiantes.por_curso', compact(
            'cursos',
            'cursoSeleccionado',
            'estudiantes'
        ));
    }
}
