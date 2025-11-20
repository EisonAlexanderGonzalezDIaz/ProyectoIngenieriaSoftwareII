<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CasoDisciplinario;
use Illuminate\Support\Facades\Auth;

class CasosDisciplinariosController extends Controller
{
    /**
     * Mostrar la vista de gestión de casos disciplinarios (solo maqueta).
     */
    public function gestion()
    {
        // Listado simple de casos desde BD
        $casos = CasoDisciplinario::with(['estudiante', 'encargado'])->orderBy('created_at', 'desc')->get();
        return view('casos.gestion', compact('casos'));
    }

    // API: obtener casos con filtros básicos
    public function apiList(Request $request)
    {
        $q = CasoDisciplinario::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $q->where('codigo', 'like', "%$search%")
              ->orWhere('descripcion', 'like', "%$search%");
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

        $casos = $q->with(['estudiante', 'encargado'])->get();
        return response()->json($casos);
    }

    // API: crear nuevo caso
    public function apiCreate(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:casos_disciplinarios,codigo',
            'estudiante_id' => 'nullable|exists:users,id',
            'encargado_id' => 'nullable|exists:users,id',
            'tipo' => 'nullable|string',
            'riesgo' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'fecha_apertura' => 'nullable|date',
        ]);

        $data['estado'] = $data['estado'] ?? 'Activo';
        $caso = CasoDisciplinario::create($data);
        return response()->json(['success' => true, 'caso' => $caso]);
    }

    // API: registrar seguimiento (actualiza ultimo_seguimiento y puede cambiar estado)
    public function apiSeguimiento(Request $request, $id)
    {
        $caso = CasoDisciplinario::findOrFail($id);
        $data = $request->validate([
            'nota' => 'nullable|string',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|string',
        ]);

        if (!empty($data['fecha'])) {
            $caso->ultimo_seguimiento = $data['fecha'];
        } else {
            $caso->ultimo_seguimiento = now();
        }

        if (!empty($data['estado'])) {
            $caso->estado = $data['estado'];
        }

        $caso->save();

        // (Opcional) aquí podríamos persistir un historial de seguimientos en otra tabla

        return response()->json(['success' => true, 'caso' => $caso]);
    }
}
