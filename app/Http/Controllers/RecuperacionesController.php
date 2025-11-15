<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecuperacionesController extends Controller
{
    public function gestion()
    {
        return view('recuperaciones.gestion');
    }

    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'estudiante' => 'required|string',
            'materia' => 'required|string',
            'fecha_recuperacion' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'tipo_recuperacion' => 'required|string',
            'docente' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        // Guardar en base de datos
        // Recuperacion::create($validated);

        return response()->json(['message' => '✅ Recuperación registrada correctamente']);
    }

    public function calificar(Request $request, $id)
    {
        // Validar datos
        $validated = $request->validate([
            'nota' => 'required|numeric|min:0|max:5',
            'resultado' => 'required|string',
            'comentarios' => 'nullable|string',
        ]);

        // Guardar calificación en base de datos
        // Recuperacion::where('id', $id)->update($validated);

        return response()->json(['message' => '✅ Recuperación calificada correctamente']);
    }

    public function aprobar($id)
    {
        // Aprobar recuperación
        // Recuperacion::where('id', $id)->update(['estado' => 'aprobado']);

        return response()->json(['message' => '✅ Recuperación aprobada']);
    }

    public function rechazar($id)
    {
        // Rechazar recuperación
        // Recuperacion::where('id', $id)->update(['estado' => 'rechazado']);

        return response()->json(['message' => '❌ Recuperación rechazada']);
    }

    public function ver($id)
    {
        // Obtener detalles de la recuperación
        return response()->json(['message' => 'Detalles de la recuperación']);
    }
}