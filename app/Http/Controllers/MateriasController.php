<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriasController extends Controller
{
    public function gestion()
    {
        // Obtener todas las materias de la tabla materias
        $materias = DB::table('materias')->orderBy('codigo', 'asc')->get();
        
        return view('materias.gestion', [
            'materias' => $materias,
        ]);
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
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        // Guardar en base de datos
        DB::table('materias')->insert([
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'creditos' => $validated['creditos'],
            'horas_semanales' => $validated['horas_semanales'],
            'docente' => $validated['docente'],
            'grado' => $validated['grado'],
            'estado' => $validated['estado'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => '✅ Materia guardada correctamente']);
    }

    public function update(Request $request, $id)
    {
        // Validar datos
        $validated = $request->validate([
            'codigo' => 'required|string|unique:materias,codigo,' . $id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1',
            'horas_semanales' => 'required|integer|min:1',
            'docente' => 'required|string',
            'grado' => 'required|string',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        // Actualizar en base de datos
        DB::table('materias')
            ->where('id', $id)
            ->update([
                'codigo' => $validated['codigo'],
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'creditos' => $validated['creditos'],
                'horas_semanales' => $validated['horas_semanales'],
                'docente' => $validated['docente'],
                'grado' => $validated['grado'],
                'estado' => $validated['estado'],
                'updated_at' => now(),
            ]);

        return response()->json(['message' => '✅ Materia actualizada correctamente']);
    }

    public function destroy($id)
    {
        // Eliminar de base de datos
        DB::table('materias')->where('id', $id)->delete();

        return response()->json(['message' => '✅ Materia eliminada correctamente']);
    }
}