<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\User;
use App\Models\RolesModel;

class RectorEstudianteController extends Controller
{
    /**
     * Devuelve la lista de cursos y, si se selecciona uno,
     * la lista de estudiantes de ese curso.
     */
    protected function baseData(Request $request): array
    {
        // Todos los cursos (puedes ajustar el orden si quieres)
        $cursos = Curso::orderBy('nombre', 'asc')->get();

        $cursoId    = $request->get('curso_id');
        $estudianteId = $request->get('estudiante_id');

        $cursoSeleccionado     = null;
        $estudiantes           = collect();
        $estudianteSeleccionado = null;

        if ($cursoId) {
            $cursoSeleccionado = Curso::find($cursoId);

            if ($cursoSeleccionado) {
                // Rol Estudiante (para filtrar solo estudiantes)
                $rolEstudiante = RolesModel::where('nombre', 'Estudiante')->first();

                $query = User::where('curso_id', $cursoSeleccionado->id);

                if ($rolEstudiante) {
                    $query->where('roles_id', $rolEstudiante->id);
                }

                $estudiantes = $query->orderBy('name', 'asc')->get();
            }
        }

        if ($estudianteId) {
            $estudianteSeleccionado = User::find($estudianteId);
        }

        return compact(
            'cursos',
            'cursoSeleccionado',
            'estudiantes',
            'estudianteSeleccionado'
        );
    }

    /**
     * Vista: Consultar boletines de un estudiante (filtrando por curso).
     */
    public function boletines(Request $request)
    {
        $data = $this->baseData($request);

        // Aquí, más adelante, puedes cargar los boletines reales
        // según $data['estudianteSeleccionado']
        $boletines = collect(); // placeholder

        return view('rector.boletines', $data + compact('boletines'));
    }

    /**
     * Vista: Consultar notas de un estudiante.
     */
    public function notas(Request $request)
    {
        $data = $this->baseData($request);

        // Más adelante puedes cargar notas reales
        $notas = collect(); // placeholder

        return view('rector.notas', $data + compact('notas'));
    }

    /**
     * Vista: Consultar materias por curso/estudiante.
     */
    public function materias(Request $request)
    {
        $data = $this->baseData($request);

        // Aquí podrías cargar materias reales desde tus modelos
        $materias = collect(); // placeholder

        return view('rector.materias', $data + compact('materias'));
    }
}
