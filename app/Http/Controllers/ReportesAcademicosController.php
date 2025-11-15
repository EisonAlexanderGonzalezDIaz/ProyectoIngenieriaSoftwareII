<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesAcademicosController extends Controller
{
    public function gestion()
    {
        return view('reportes-academicos.gestion');
    }

    public function generar(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'tipo_reporte' => 'required|string',
            'grado' => 'required|string',
            'periodo' => 'required|string',
            'formato' => 'required|string',
        ]);

        // Generar reporte en base de datos
        // Reporte::create($validated);

        return response()->json(['message' => '✅ Reporte generado correctamente']);
    }

    public function descargar($id)
    {
        // Lógica para descargar reporte
        return response()->json(['message' => '📥 Descargando reporte: ' . $id]);
    }

    public function enviar(Request $request, $id)
    {
        // Validar datos
        $validated = $request->validate([
            'destinatarios' => 'required|string',
            'mensaje' => 'nullable|string',
        ]);

        // Enviar reporte por email
        // Reporte::where('id', $id)->update(['enviado' => true]);

        return response()->json(['message' => '📧 Reporte enviado correctamente']);
    }

    public function eliminar($id)
    {
        // Eliminar reporte
        // Reporte::where('id', $id)->delete();

        return response()->json(['message' => '🗑️ Reporte eliminado']);
    }

    public function ver($id)
    {
        // Obtener detalles del reporte
        return response()->json(['message' => 'Detalles del reporte']);
    }
}