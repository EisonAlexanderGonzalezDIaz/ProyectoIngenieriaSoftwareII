<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Horario;
use App\Models\Nota;
use App\Models\Tarea;
use App\Models\Entrega;
use App\Models\Boletin;
use App\Models\PlanEstudio;
use App\Models\Certificacion;
use App\Models\ReporteDisciplinario;
use App\Models\Notificacion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    // CITAS ORIENTACIÓN
    public function viewSolicitarCita()
    {
        $orientadores = User::whereHas('role', function ($q) {
            $q->where('nombre', 'Orientador');
        })->get();
        return view('estudiante.solicitar_cita', compact('orientadores'));
    }

    public function crearCita(Request $request)
    {
        $data = $request->validate([
            'orientador_id' => 'nullable|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'motivo' => 'required|string',
        ]);

        $cita = Cita::create([
            'estudiante_id' => auth()->id(),
            'orientador_id' => $data['orientador_id'] ?? null,
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'motivo' => $data['motivo'],
            'estado' => 'solicitado',
        ]);

        return response()->json(['cita' => $cita], 201);
    }

    // HORARIOS
    public function viewConsultarHorario()
    {
        return view('estudiante.consultar_horario');
    }

    public function obtenerHorarios()
    {
        $horarios = Horario::with('docente', 'materia')->get();
        return response()->json(['horarios' => $horarios], 200);
    }

    public function descargarHorario()
    {
        $horarios = Horario::with('docente', 'materia')->get();
        $csv = "Día,Hora Inicio,Hora Fin,Materia,Docente,Aula\n";
        foreach ($horarios as $h) {
            $materia = $h->materia?->nombre ?? 'N/A';
            $docente = $h->docente?->name ?? 'N/A';
            $csv .= "{$h->dia},{$h->hora_inicio},{$h->hora_fin},{$materia},{$docente},{$h->aula}\n";
        }
        return response($csv, 200)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="horario.csv"');
    }

    // NOTAS
    public function viewConsultarNotas()
    {
        $materias = Subject::all();
        return view('estudiante.consultar_notas', compact('materias'));
    }

    public function obtenerNotasPorMateria(Request $request)
    {
        $data = $request->validate([
            'materia_id' => 'nullable|exists:subjects,id',
        ]);

        $query = Nota::where('estudiante_id', auth()->id());
        if ($data['materia_id'] ?? null) {
            $query->where('materia_id', $data['materia_id']);
        }
        $notas = $query->with('materia')->get();
        return response()->json(['notas' => $notas], 200);
    }

    // TAREAS Y ENTREGAS
    public function viewTareas()
    {
        return view('estudiante.tareas');
    }

    public function obtenerTareas()
    {
        $tareas = Tarea::with('docente', 'materia')->get();
        return response()->json(['tareas' => $tareas], 200);
    }

    public function entregarTarea(Request $request)
    {
        $data = $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'archivo' => 'required|file|max:10240',
        ]);

        $path = $request->file('archivo')->store('entregas', 'public');

        $entrega = Entrega::create([
            'tarea_id' => $data['tarea_id'],
            'estudiante_id' => auth()->id(),
            'archivo_url' => $path,
            'fecha_entrega' => now(),
        ]);

        return response()->json(['entrega' => $entrega], 201);
    }

    // BOLETINES
    public function viewConsultarBoletines()
    {
        return view('estudiante.consultar_boletines');
    }

    public function obtenerBoletines()
    {
        $boletines = Boletin::where('estudiante_id', auth()->id())->orderByDesc('fecha_emision')->get();
        return response()->json(['boletines' => $boletines], 200);
    }

    public function descargarBoletin($id)
    {
        $boletin = Boletin::findOrFail($id);
        if ($boletin->estudiante_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        if ($boletin->archivo_url && Storage::disk('public')->exists($boletin->archivo_url)) {
            return Storage::disk('public')->download($boletin->archivo_url);
        }
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    // PLAN DE ESTUDIO
    public function viewPlanEstudio()
    {
        return view('estudiante.plan_estudio');
    }

    public function obtenerPlanEstudio()
    {
        $curso = auth()->user()->curso;
        $plan = PlanEstudio::where('curso_id', $curso?->id)->with('materia')->get();
        return response()->json(['plan' => $plan], 200);
    }

    // CERTIFICACIONES
    public function viewSolicitarCertificacion()
    {
        return view('estudiante.solicitar_certificacion');
    }

    public function crearCertificacion(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
        ]);

        $certificacion = Certificacion::create([
            'estudiante_id' => auth()->id(),
            'tipo' => $data['tipo'],
            'estado' => 'solicitado',
            'fecha_solicitud' => now(),
        ]);

        return response()->json(['certificacion' => $certificacion], 201);
    }

    public function obtenerCertificaciones()
    {
        $certificaciones = Certificacion::where('estudiante_id', auth()->id())->orderByDesc('created_at')->get();
        return response()->json(['certificaciones' => $certificaciones], 200);
    }

    public function descargarCertificacion($id)
    {
        $cert = Certificacion::findOrFail($id);
        if ($cert->estudiante_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        if ($cert->estado !== 'listo' && $cert->estado !== 'descargado') {
            return response()->json(['error' => 'Certificación no está lista'], 400);
        }
        if ($cert->archivo_url && Storage::disk('public')->exists($cert->archivo_url)) {
            Certificacion::whereId($id)->update(['estado' => 'descargado']);
            return Storage::disk('public')->download($cert->archivo_url);
        }
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    // REPORTES DISCIPLINARIOS
    public function viewReportesDisciplinarios()
    {
        return view('estudiante.reportes_disciplinarios');
    }

    public function obtenerReportesDisciplinarios()
    {
        $reportes = ReporteDisciplinario::where('estudiante_id', auth()->id())->orderByDesc('fecha')->get();
        return response()->json(['reportes' => $reportes], 200);
    }

    // NOTIFICACIONES
    public function viewNotificaciones()
    {
        return view('estudiante.notificaciones');
    }

    public function obtenerNotificaciones()
    {
        $notificaciones = Notificacion::where('user_id', auth()->id())->orderByDesc('created_at')->paginate(15);
        return response()->json(['notificaciones' => $notificaciones], 200);
    }

    public function marcarNotificacionLeida($id)
    {
        $notif = Notificacion::findOrFail($id);
        if ($notif->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        $notif->update(['leida' => true]);
        return response()->json(['notificacion' => $notif], 200);
    }

    // Dashboard principal
    public function index()
    {
        return view('estudiante.dashboard');
    }
}
