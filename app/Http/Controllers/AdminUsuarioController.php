<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\RolesModel;
use App\Models\Curso;

class AdminUsuarioController extends Controller
{
    /**
     * Listado para gestionar perfiles de usuario:
     * - Búsqueda
     * - Filtros por rol y estado
     * - Paginación
     */
    public function index(Request $request)
    {
        // Base query con relaciones rol y curso
        $query = User::with(['rol', 'curso']);

        // --- Filtro de búsqueda por nombre o email ---
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);

            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'LIKE', "%{$buscar}%")
                  ->orWhere('email', 'LIKE', "%{$buscar}%");
                // Si tienes 'documento', puedes agregar:
                // ->orWhere('documento', 'LIKE', "%{$buscar}%");
            });
        }

        // --- Filtro por rol usando la RELACIÓN 'rol' ---
        if ($request->filled('rol_id')) {
            $rolId = $request->rol_id;

            $query->whereHas('rol', function ($q) use ($rolId) {
                // 'id' de la tabla roles
                $q->where('id', $rolId);
            });
        }

        // --- Filtro por estado (activo / inactivo) SOLO si existe la columna ---
        if ($request->filled('estado') && Schema::hasColumn('users', 'activo')) {
            if ($request->estado === 'activo') {
                $query->where('activo', 1);
            } elseif ($request->estado === 'inactivo') {
                $query->where('activo', 0);
            }
        }

        // --- Orden y paginación ---
        $usuarios = $query
            ->orderBy('name', 'asc')
            ->paginate(10)               // 10 usuarios por página
            ->withQueryString();         // Mantener filtros en la paginación

        // Roles disponibles para el select
        $roles = RolesModel::all();

        // Cursos disponibles para asignar a estudiantes
        $cursos = Curso::orderBy('nombre')->get();

        return view('admin.usuarios.perfiles', compact('usuarios', 'roles', 'cursos'));
    }

    /**
     * Actualizar rol, estado (activo/inactivo) y curso de un usuario.
     */
    public function updatePerfil(Request $request, $id)
    {
        $request->validate([
            'rol_id'   => 'required|exists:roles,id',
            'activo'   => 'nullable|in:0,1',
            'curso_id' => 'nullable|exists:cursos,id',
        ]);

        $user = User::findOrFail($id);

        // Asociar el rol usando la RELACIÓN 'rol'
        $user->rol()->associate($request->rol_id);

        // Solo intentamos guardar 'activo' si la columna existe
        if (Schema::hasColumn('users', 'activo')) {
            $user->activo = $request->boolean('activo');
        }

        // Guardar curso solo si existe la columna 'curso_id'
        if (Schema::hasColumn('users', 'curso_id')) {
            // En tu interfaz solo vas a mostrar el select de curso
            // para usuarios con rol Estudiante, pero aquí es opcional:
            $user->curso_id = $request->curso_id ?: null;
        }

        $user->save();

        return redirect()
            ->route('admin.usuarios.perfiles')
            ->with('ok', 'Perfil de usuario actualizado correctamente.');
    }

    /**
     * Actualizar datos básicos del usuario:
     * - Nombre
     * - Email
     */
    public function updateBasicos(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->name  = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()
            ->route('admin.usuarios.perfiles')
            ->with('ok', 'Datos básicos del usuario actualizados correctamente.');
    }
}
