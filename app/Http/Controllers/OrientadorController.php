<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\CasoDisciplinario;
use Illuminate\Support\Facades\Auth;

class OrientadorController extends Controller
{
    public function dashboard()
    {
        return view('orientacion.gestion');
    }

    // API: Obtener casos asignados / filtrados para el orientador
    public function apiCasos(Request $request)
    {
        $q = CasoDisciplinario::query();

        if ($request->filled('search')) {
            $s = $request->get('search');
            $q->where('codigo', 'like', "%$s%")
              ->orWhere('descripcion', 'like', "%$s%");
        }

        if ($request->filled('tipo')) {
            $q->where('tipo', $request->get('tipo'));
        }

        if ($request->filled('riesgo')) {
            $q->where('riesgo', $request->get('riesgo'));
        }

        if ($request->filled('estado')) {
            $q->where('estado', $request->get('estado'));
        }

        $casos = $q->with(['estudiante'])->get();
        return response()->json($casos);
    }

    // API: Agendar una sesión (crear Cita)
    public function apiAgendarSesion(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'motivo' => 'nullable|string',
        ]);

        $cita = Cita::create([
            'estudiante_id' => $data['estudiante_id'],
            'orientador_id' => Auth::id(),
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'motivo' => $data['motivo'] ?? null,
            'estado' => 'solicitado',
        ]);

        return response()->json(['success' => true, 'cita' => $cita]);
    }

    // API: Obtener agenda del orientador (hoy y próximos días)
    public function apiAgenda(Request $request)
    {
        $userId = Auth::id();
        $citas = Cita::where('orientador_id', $userId)->orderBy('fecha')->orderBy('hora')->get();
        return response()->json($citas);
    }
}
