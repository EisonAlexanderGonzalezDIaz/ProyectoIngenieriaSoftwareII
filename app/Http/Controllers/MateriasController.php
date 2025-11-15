<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MateriasController extends Controller
{
    public function gestion()
    {
        return view('materias.gestion');
    }

    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'codigo' => 'required|string|unique:materias,codigo',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1',
            'horas_semanales' => 'required|integer|min:1',
            'docente' => 'required|string',
            'grado' => 'required|string',
            'estado' => 'required|string',
        ]);

        // Guardar en base de datos
        // Materia::create($validated);

        return response()->json(['message' => '✅ Materia guardada correctamente']);
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar materia
        return response()->json(['message' => '✅ Materia actualizada correctamente']);
    }

    public function destroy($id)
    {
        // Lógica para eliminar materia
        return response()->json(['message' => '✅ Materia eliminada correctamente']);
    }
}