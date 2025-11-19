<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class GestionDocentesController extends Controller
{
    /**
     * Mostrar la vista de gestión de docentes.
     * Devuelve datos de ejemplo para que la vista funcione sin base de datos.
     */
    public function gestion()
    {
        // Obtener parámetros de búsqueda/filtrado
        $q = request()->get('search');
        $estado = request()->get('estado');

        // Consultar desde el modelo Docente (tabla 'docentes')
        $query = Docente::query();

        if ($q) {
            $query->where(function($s) use ($q) {
                $s->where('nombre_completo', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        if ($estado) {
            if (Schema::hasColumn('docentes', 'estado')) {
                $query->where('estado', $estado);
            }
        }

        $docentesCollection = $query->orderBy('nombre_completo')->get();

        // Intentar cargar materias desde la tabla 'materias' si existe
        $materias = [];
        $materiasPorDocente = [];
        if (Schema::hasTable('materias')) {
            $materias = DB::table('materias')->select('id', 'nombre', 'docente')->get();
            foreach ($materias as $m) {
                $key = trim(strtolower($m->docente ?? ''));
                if (!$key) continue;
                $materiasPorDocente[$key][] = $m->nombre;
            }
        }

        // Mapear para mantener compatibilidad con la vista existente
        $docentes = $docentesCollection->map(function($d) use ($materiasPorDocente) {
            $nombreDoc = trim(strtolower($d->nombre_completo ?? ($d->name ?? '')));
            $materiasText = null;
            if ($nombreDoc && isset($materiasPorDocente[$nombreDoc])) {
                $materiasText = collect($materiasPorDocente[$nombreDoc])->join(', ');
            }

            return (object)[
                'id' => $d->id,
                'nombre' => $d->nombre_completo ?? ($d->name ?? 'Nombre no disponible'),
                'materia' => $materiasText,
                'email' => $d->email ?? null,
                'telefono' => $d->telefono ?? null,
                'estado' => $d->estado ?? 'Activo'
            ];
        });

        return view('gestiondocentes.gestion', compact('docentes', 'materias'));
    }

    /**
     * Almacenar un nuevo docente en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:docentes,email'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'materias' => ['nullable', 'array'],
            'materias.*' => ['string', 'max:255'],
        ]);

        $materiaText = null;
        if (!empty($data['materias'])) {
            $materiaText = implode(', ', $data['materias']);
        }

        $docente = Docente::create([
            'nombre_completo' => $data['nombre'],
            'email' => $data['email'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'materia' => $materiaText,
        ]);

        return redirect()->route('gestiondocentes.gestion')->with('ok', 'Docente creado correctamente.');
    }

    /**
     * Actualizar un docente existente.
     */
    public function update(Request $request, $id)
    {
        $docente = Docente::findOrFail($id);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:docentes,email,' . $docente->id],
            'telefono' => ['nullable', 'string', 'max:50'],
            'materias' => ['nullable', 'array'],
            'materias.*' => ['string', 'max:255'],
        ]);

        $materiaText = null;
        if (!empty($data['materias'])) {
            $materiaText = implode(', ', $data['materias']);
        }

        $docente->update([
            'nombre_completo' => $data['nombre'],
            'email' => $data['email'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'materia' => $materiaText,
        ]);

        return redirect()->route('gestiondocentes.gestion')->with('ok', 'Docente actualizado correctamente.');
    }

    /**
     * Eliminar un docente.
     */
    public function destroy($id)
    {
        $docente = Docente::findOrFail($id);
        $docente->delete();

        return response()->json(['ok' => true, 'msg' => 'Docente eliminado']);
    }
}
