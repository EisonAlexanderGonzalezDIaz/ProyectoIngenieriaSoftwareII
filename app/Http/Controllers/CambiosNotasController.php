<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CambiosNotasController extends Controller
{
    public function gestion()
    {
        return view('cambios-notas.gestion');
    }

    public function aprobar(Request $request, $id)
    {
        // Validar datos
        $validated = $request->validate([
            'razon_cambio' => 'required|string',
            'comentario' => 'nullable|string',
        ]);

        // Aprobar cambio en base de datos
        // CambioNota::where('id', $id)->update(['estado' => 'aprobado']);

        return response()->json(['message' => '✅ Cambio de nota aprobado correctamente']);
    }

    public function rechazar(Request $request, $id)
    {
        // Rechazar cambio en base de datos
        // CambioNota::where('id', $id)->update(['estado' => 'rechazado']);

        return response()->json(['message' => '❌ Cambio de nota rechazado']);
    }

    public function ver($id)
    {
        // Obtener detalles del cambio de nota
        return response()->json(['message' => 'Detalles del cambio de nota']);
    }
}