<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorariosController extends Controller
{
    public function gestion()
    {
        $horarios = DB::table('horarios')->orderBy('grado', 'asc')->get();
        return view('horarios.gestion', ['horarios' => $horarios]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grado' => 'required|string',
            'seccion' => 'required|string',
            'dia' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'aula' => 'required|string',
            'materia_nombre' => 'required|string',
            'docente_nombre' => 'required|string',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        DB::table('horarios')->insert([
            'grado' => $validated['grado'],
            'seccion' => $validated['seccion'],
            'dia' => $validated['dia'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'aula' => $validated['aula'],
            'materia_nombre' => $validated['materia_nombre'],
            'docente_nombre' => $validated['docente_nombre'],
            'estado' => $validated['estado'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => '✅ Horario guardado correctamente']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'grado' => 'required|string',
            'seccion' => 'required|string',
            'dia' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'aula' => 'required|string',
            'materia_nombre' => 'required|string',
            'docente_nombre' => 'required|string',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        DB::table('horarios')->where('id', $id)->update([
            'grado' => $validated['grado'],
            'seccion' => $validated['seccion'],
            'dia' => $validated['dia'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'aula' => $validated['aula'],
            'materia_nombre' => $validated['materia_nombre'],
            'docente_nombre' => $validated['docente_nombre'],
            'estado' => $validated['estado'],
            'updated_at' => now(),
        ]);

        return response()->json(['message' => '✅ Horario actualizado correctamente']);
    }

    public function destroy($id)
    {
        DB::table('horarios')->where('id', $id)->delete();

        return response()->json(['message' => '✅ Horario eliminado correctamente']);
    }
}