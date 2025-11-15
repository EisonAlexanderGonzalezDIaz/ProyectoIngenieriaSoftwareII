<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HorariosController extends Controller
{
    public function gestion()
    {
        return view('horarios.gestion');
    }

    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'grado' => 'required|string',
            'seccion' => 'required|string',
            'aula' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'materia' => 'required|string',
            'docente' => 'required|string',
            'estado' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        // Guardar en base de datos
        // Horario::create($validated);

        return response()->json(['message' => '✅ Horario guardado correctamente']);
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar horario
        return response()->json(['message' => '✅ Horario actualizado correctamente']);
    }

    public function destroy($id)
    {
        // Lógica para eliminar horario
        return response()->json(['message' => '✅ Horario eliminado correctamente']);
    }
}