<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Models\Boletin;
use App\Models\ReporteDisciplinario;
use App\Models\SolicitudPaz;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AcudienteController extends Controller
{
    // Notificaciones
    public function viewNotificaciones()
    {
        return view('acudiente.notificaciones');
    }

    public function obtenerNotificaciones(Request $request)
    {
        $notificaciones = Notificacion::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

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

    // Boletines
    public function viewBoletines()
    {
        return view('acudiente.boletines');
    }

    public function obtenerBoletines()
    {
        // Obtener boletines relacionados con los estudiantes vinculados al acudiente
        $acudiente = auth()->user();
        // relación existente en User: estudiantesACargo()
        $estudiantesIds = $acudiente->estudiantesACargo()->pluck('id')->toArray() ?? [];

        $boletines = Boletin::whereIn('estudiante_id', $estudiantesIds)
            ->orderByDesc('fecha_emision')
            ->get();

        return response()->json(['boletines' => $boletines], 200);
    }

    public function descargarBoletin($id)
    {
        $boletin = Boletin::findOrFail($id);
        $acudiente = auth()->user();
        $estudiantesIds = $acudiente->estudiantesACargo()->pluck('id')->toArray() ?? [];

        if (!in_array($boletin->estudiante_id, $estudiantesIds)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($boletin->archivo_url && Storage::disk('public')->exists($boletin->archivo_url)) {
            return Storage::disk('public')->download($boletin->archivo_url);
        }
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    // Reportes disciplinarios (de sus hijos)
    public function viewReportesDisciplinarios()
    {
        return view('acudiente.reportes_disciplinarios');
    }

    public function obtenerReportesDisciplinarios()
    {
        $acudiente = auth()->user();
        $estudiantesIds = $acudiente->estudiantesACargo()->pluck('id')->toArray() ?? [];

        $reportes = ReporteDisciplinario::whereIn('estudiante_id', $estudiantesIds)
            ->orderByDesc('fecha')
            ->get();

        return response()->json(['reportes' => $reportes], 200);
    }

    // Endpoint API: obtener estudiantes a cargo (JSON)
    public function obtenerEstudiantes()
    {
        $acudiente = auth()->user();
        $estudiantes = $acudiente->estudiantesACargo()
            ->select('id', 'name', 'email')
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'nombre_completo' => $e->name,
                    'email' => $e->email,
                ];
            });

        return response()->json(['estudiantes' => $estudiantes], 200);
    }

    // Solicitar Paz y Salvo
    public function viewSolicitarPaz()
    {
        $acudiente = auth()->user();
        $estudiantes = $acudiente->estudiantesACargo()->get();
        return view('acudiente.solicitar_paz', compact('estudiantes'));
    }

    public function crearSolicitudPaz(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'nullable|exists:users,id',
            'mensaje' => 'nullable|string',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('solicitudes_paz', 'public');
        }

        $sol = SolicitudPaz::create([
            'acudiente_id' => auth()->id(),
            'estudiante_id' => $data['estudiante_id'] ?? null,
            'mensaje' => $data['mensaje'] ?? null,
            'archivo_url' => $path,
            'estado' => 'solicitado',
            'fecha_solicitud' => now(),
        ]);

        return response()->json(['solicitud' => $sol], 201);
    }

    // =========================
    // Solicitud de becas / descuentos
    // =========================
    public function viewSolicitarBeca()
    {
        $acudiente = auth()->user();
        $estudiantes = $acudiente->estudiantesACargo()->get();
        return view('acudiente.solicitar_beca', compact('estudiantes'));
    }

    public function crearSolicitudBeca(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'nullable|exists:users,id',
            'tipo' => 'required|string',
            'monto_estimado' => 'nullable|numeric',
            'detalle' => 'nullable|string',
        ]);

        // Actualmente guardamos temporalmente en sesión o devolvemos success.
        // Implementación con persistencia se puede agregar luego.

        return response()->json(['success' => true, 'message' => 'Solicitud de beca recibida', 'data' => $data], 201);
    }
}
