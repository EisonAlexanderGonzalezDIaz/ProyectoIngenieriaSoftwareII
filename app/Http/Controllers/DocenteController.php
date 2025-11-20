<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Horario;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\MaterialAcademico;
use App\Models\InformeDelCurso;
use App\Models\User;
use App\Models\Subject;
use App\Models\Curso;
use Illuminate\Support\Facades\Storage;

class DocenteController extends Controller
{
    /**
     * ==================== CITAS A ORIENTACIÓN ====================
     */

    public function viewSolicitarCita()
    {
        return view('docente.solicitar_cita');
    }

    public function crearCita(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:users,id',
            'motivo' => 'required|string|max:500',
            'fecha_sugerida' => 'nullable|date',
        ]);

        $orientador = User::whereHas('role', function ($q) {
            $q->where('nombre', 'Orientador');
        })->first();

        if (!$orientador) {
            return response()->json(['error' => 'No hay orientador disponible'], 400);
        }

        $cita = Cita::create([
            'estudiante_id' => $data['estudiante_id'],
            'orientador_id' => $orientador->id,
            'fecha' => $data['fecha_sugerida'] ?? null,
            'motivo' => $data['motivo'],
            'estado' => 'solicitado',
        ]);

        return response()->json(['cita' => $cita, 'success' => true], 201);
    }

    /**
     * ==================== HORARIOS ====================
     */

    public function viewConsultarHorario()
    {
        $docente = auth()->user();
        $materias = $docente->materiasDictadas()->get();
        $cursos = $docente->cursosDictados()->get();
        return view('docente.horario', compact('materias', 'cursos'));
    }

    public function obtenerHorarios(Request $request)
    {
        $docente = auth()->user();
        $subjectId = $request->query('subject_id');
        $query = Horario::where('docente_id', $docente->id);
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        $horarios = $query->orderBy('dia_semana')->orderBy('hora_inicio')->get();
        return response()->json(['horarios' => $horarios], 200);
    }

    public function descargarHorario(Request $request)
    {
        $docente = auth()->user();
        $subjectId = $request->query('subject_id');
        $query = Horario::where('docente_id', $docente->id);
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
            $subject = Subject::find($subjectId);
            $fileName = 'horario_' . ($subject->name ?? 'materia') . '.pdf';
        } else {
            $fileName = 'horario_completo.pdf';
        }

        $horarios = $query->get();
        $html = '<h2>Horario de Clases</h2>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>Día</th><th>Hora Inicio</th><th>Hora Fin</th><th>Materia</th><th>Salón</th></tr>';

        foreach ($horarios as $h) {
            $subject = $h->subject ? $h->subject->name : '—';
            $html .= "<tr><td>{$h->dia_semana}</td><td>{$h->hora_inicio}</td><td>{$h->hora_fin}</td><td>{$subject}</td><td>{$h->salon}</td></tr>";
        }
        $html .= '</table>';

        return response()->streamDownload(
            function() use ($html) { echo $html; },
            $fileName,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    /**
     * ==================== NOTAS ====================
     */

    public function viewRegistrarNotas()
    {
        $docente = auth()->user();
        $cursos = $docente->cursosDictados()->distinct()->get();
        return view('docente.notas', compact('cursos'));
    }

    public function obtenerEstudiantesPorCurso(Request $request)
    {
        $cursoId = $request->query('curso_id');
        $subjectId = $request->query('subject_id');

        if (!$cursoId || !$subjectId) {
            return response()->json(['error' => 'curso_id y subject_id requeridos'], 400);
        }

        $docente = auth()->user();
        $dictaMateria = $docente->materiasDictadas()
            ->wherePivot('curso_id', $cursoId)
            ->where('subject_id', $subjectId)
            ->exists();

        if (!$dictaMateria) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $estudiantes = User::where('curso_id', $cursoId)
            ->whereHas('role', function ($q) { $q->where('nombre', 'Estudiante'); })
            ->select('id', 'name', 'email')->get();

        return response()->json(['estudiantes' => $estudiantes], 200);
    }

    public function guardarNota(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'periodo' => 'required|string',
            'calificacion' => 'required|numeric|min:0|max:5',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $docente = auth()->user();
        $dictaMateria = $docente->materiasDictadas()
            ->where('subject_id', $data['subject_id'])->exists();

        if (!$dictaMateria) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $nota = Nota::updateOrCreate(
            ['estudiante_id' => $data['estudiante_id'], 'subject_id' => $data['subject_id'], 'periodo' => $data['periodo']],
            $data
        );

        return response()->json(['nota' => $nota, 'success' => true], 200);
    }

    /**
     * ==================== ASISTENCIA ====================
     */

    public function viewRegistrarAsistencia()
    {
        $docente = auth()->user();
        $cursos = $docente->cursosDictados()->get();
        return view('docente.asistencia', compact('cursos'));
    }

    public function guardarAsistencia(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:presente,ausente,justificado',
            'observaciones' => 'nullable|string',
        ]);

        $docente = auth()->user();
        $data['docente_id'] = $docente->id;

        $asistencia = Asistencia::updateOrCreate(
            ['estudiante_id' => $data['estudiante_id'], 'subject_id' => $data['subject_id'], 'fecha' => $data['fecha']],
            $data
        );

        return response()->json(['asistencia' => $asistencia, 'success' => true], 200);
    }

    /**
     * ==================== MATERIAL ACADÉMICO ====================
     */

    public function viewSubirMaterial()
    {
        $docente = auth()->user();
        $materias = $docente->materiasDictadas()->get();
        return view('docente.materiales', compact('materias'));
    }

    public function subirMaterial(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|max:51200',
            'tipo' => 'required|in:documento,video,presentacion,tarea,otro',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $docente = auth()->user();
        $dictaMateria = $docente->materiasDictadas()
            ->where('subject_id', $data['subject_id'])->exists();

        if (!$dictaMateria) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $path = $request->file('archivo')->store('materiales_academicos', 'public');

        $material = MaterialAcademico::create([
            'docente_id' => $docente->id,
            'subject_id' => $data['subject_id'],
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'] ?? null,
            'archivo_url' => $path,
            'tipo' => $data['tipo'],
            'fecha_publicacion' => now()->toDateString(),
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
        ]);

        return response()->json(['material' => $material, 'success' => true], 201);
    }

    /**
     * ==================== INFORME DEL CURSO ====================
     */

    public function viewGenerarInforme()
    {
        $docente = auth()->user();
        $cursos = $docente->cursosDictados()->get();
        $materias = $docente->materiasDictadas()->get();
        return view('docente.informe_curso', compact('cursos', 'materias'));
    }

    public function generarInforme(Request $request)
    {
        $data = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'subject_id' => 'required|exists:subjects,id',
            'periodo' => 'required|string',
            'desempeno_general' => 'required|string',
            'fortalezas' => 'required|string',
            'debilidades' => 'required|string',
            'recomendaciones' => 'required|string',
        ]);

        $docente = auth()->user();
        $dictaMateria = $docente->materiasDictadas()
            ->wherePivot('curso_id', $data['curso_id'])
            ->where('subject_id', $data['subject_id'])->exists();

        if (!$dictaMateria) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notas = Nota::where('subject_id', $data['subject_id'])
            ->whereHas('estudiante', function ($q) use ($data) { $q->where('curso_id', $data['curso_id']); })
            ->where('periodo', $data['periodo'])->get();

        $aprobados = $notas->filter(fn($n) => $n->calificacion >= 3)->count();
        $reprobados = $notas->filter(fn($n) => $n->calificacion < 3)->count();
        $promedio = $notas->count() > 0 ? $notas->avg('calificacion') : 0;

        $informe = InformeDelCurso::create([
            'docente_id' => $docente->id,
            'subject_id' => $data['subject_id'],
            'curso_id' => $data['curso_id'],
            'periodo' => $data['periodo'],
            'desempeno_general' => $data['desempeno_general'],
            'fortalezas' => $data['fortalezas'],
            'debilidades' => $data['debilidades'],
            'recomendaciones' => $data['recomendaciones'],
            'estudiantes_aprobados' => $aprobados,
            'estudiantes_reprobados' => $reprobados,
            'estudiantes_total' => $notas->count(),
            'promedio_curso' => round($promedio, 2),
            'fecha_generacion' => now()->toDateString(),
        ]);

        return response()->json(['informe' => $informe, 'success' => true], 201);
    }

    public function obtenerDatosAutorellenado(Request $request)
    {
        $cursoId = $request->query('curso_id');
        $subjectId = $request->query('subject_id');

        if (!$cursoId || !$subjectId) {
            return response()->json(['error' => 'Parámetros requeridos'], 400);
        }

        $docente = auth()->user();
        $dictaMateria = $docente->materiasDictadas()
            ->wherePivot('curso_id', $cursoId)
            ->where('subject_id', $subjectId)->exists();

        if (!$dictaMateria) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notas = Nota::where('subject_id', $subjectId)
            ->whereHas('estudiante', function ($q) use ($cursoId) { $q->where('curso_id', $cursoId); })
            ->get();

        $curso = Curso::find($cursoId);
        $subject = Subject::find($subjectId);

        return response()->json([
            'curso' => $curso,
            'subject' => $subject,
            'estudiantes_total' => $notas->count(),
            'promedio_general' => round($notas->avg('calificacion'), 2),
        ], 200);
    }

    public function obtenerMateriasXCurso(Request $request)
    {
        $cursoId = $request->query('curso_id');
        $docente = auth()->user();
        $materias = $docente->materiasDictadas()
            ->wherePivot('curso_id', $cursoId)->get();
        return response()->json(['materias' => $materias], 200);
    }

    public function obtenerMateriales()
    {
        $docente = auth()->user();
        $materiales = MaterialAcademico::where('docente_id', $docente->id)
            ->orderByDesc('created_at')->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'titulo' => $m->titulo,
                    'materia_nombre' => $m->subject ? $m->subject->name : '—',
                    'tipo' => $m->tipo,
                    'fecha_publicacion' => $m->fecha_publicacion,
                    'fecha_vencimiento' => $m->fecha_vencimiento,
                    'archivo_url' => $m->archivo_url,
                ];
            });
        return response()->json(['materiales' => $materiales], 200);
    }

    public function obtenerInformes()
    {
        $docente = auth()->user();
        $informes = InformeDelCurso::where('docente_id', $docente->id)
            ->orderByDesc('fecha_generacion')->get()
            ->map(function ($i) {
                return [
                    'id' => $i->id,
                    'curso_nombre' => $i->curso ? $i->curso->nombre : '—',
                    'materia_nombre' => $i->subject ? $i->subject->name : '—',
                    'periodo' => $i->periodo,
                    'fecha_generacion' => $i->fecha_generacion,
                    'promedio_curso' => $i->promedio_curso,
                    'estudiantes_total' => $i->estudiantes_total,
                    'estudiantes_aprobados' => $i->estudiantes_aprobados,
                ];
            });
        return response()->json(['informes' => $informes], 200);
    }

    public function buscarEstudiantes(Request $request)
    {
        $query = $request->query('q');
        $estudiantes = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->whereHas('role', function ($q) { $q->where('nombre', 'Estudiante'); })
            ->limit(10)->select('id', 'name', 'email')->get();
        return response()->json(['estudiantes' => $estudiantes], 200);
    }
}
