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
     * - Agrupación de estudiantes por curso
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

        // --- Traer todos los usuarios (sin paginar) para poder agrupar por curso ---
        $usuarios = $query
            ->orderBy('curso_id')
            ->orderBy('name', 'asc')
            ->get();

        // Roles disponibles para el select de rol
        $roles = RolesModel::all();

        // Cursos disponibles (1A, 1B, ..., 11B) ordenados bien
        $cursos = Curso::orderByRaw("CAST(SUBSTRING(nombre, 1, LENGTH(nombre) - 1) AS UNSIGNED) ASC")
            ->orderByRaw("RIGHT(nombre, 1) ASC")
            ->get();

        // ==========================
        // AGRUPACIÓN
        // ==========================

        // Estudiantes con curso asignado
        $estudiantes = $usuarios->filter(function ($u) {
            return optional($u->rol)->nombre === 'Estudiante' && !is_null($u->curso_id);
        });

        // Estudiantes agrupados por curso_id
        $estudiantesAgrupados = $estudiantes->groupBy('curso_id');

        // Otros usuarios (no estudiantes o estudiantes sin curso)
        $otrosUsuarios = $usuarios->reject(function ($u) {
            return optional($u->rol)->nombre === 'Estudiante' && !is_null($u->curso_id);
        });

        return view('admin.usuarios.perfiles', [
            'usuarios'            => $usuarios,            // para los modales
            'roles'               => $roles,
            'cursos'              => $cursos,
            'estudiantesAgrupados'=> $estudiantesAgrupados,
            'otrosUsuarios'       => $otrosUsuarios,
        ]);
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

        // Asociar el rol usando la RELACIÓN 'rol' (FK: roles_id)
        $user->rol()->associate($request->rol_id);

        // Solo intentamos guardar 'activo' si la columna existe
        if (Schema::hasColumn('users', 'activo')) {
            $user->activo = $request->boolean('activo');
        }

        // Guardar curso solo si existe la columna 'curso_id'
        if (Schema::hasColumn('users', 'curso_id')) {
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

    /**
     * Mostrar pantalla para gestionar acudientes de un estudiante.
     */
    public function editarAcudientes(User $user)
    {
        // Solo tiene sentido si el usuario es Estudiante
        if (optional($user->rol)->nombre !== 'Estudiante') {
            return redirect()
                ->route('admin.usuarios.perfiles')
                ->with('ok', 'Solo se pueden gestionar acudientes para usuarios con rol Estudiante.');
        }

        // Acudientes disponibles: todos los usuarios con rol "Acudiente"
        $acudientesDisponibles = User::whereHas('rol', function ($q) {
                $q->where('nombre', 'Acudiente');
            })
            ->orderBy('name', 'asc')
            ->get();

        // IDs de acudientes ya vinculados a este estudiante (tabla pivote acudiente_estudiante)
        $acudientesSeleccionados = $user->acudientes()
            ->pluck('users.id')
            ->toArray();

        return view('admin.usuarios.acudientes', compact(
            'user',
            'acudientesDisponibles',
            'acudientesSeleccionados'
        ));
    }

    /**
     * Guardar vínculos de acudientes de un estudiante.
     */
    public function guardarAcudientes(Request $request, User $user)
    {
        // Solo si es Estudiante
        if (optional($user->rol)->nombre !== 'Estudiante') {
            return redirect()
                ->route('admin.usuarios.perfiles')
                ->with('ok', 'Solo se pueden gestionar acudientes para usuarios con rol Estudiante.');
        }

        $request->validate([
            'acudientes'   => 'nullable|array',
            'acudientes.*' => 'exists:users,id',
        ]);

        $idsAcudientes = $request->input('acudientes', []);

        // Sincronizar en la tabla pivote acudiente_estudiante
        $user->acudientes()->sync($idsAcudientes);

        return redirect()
            ->route('admin.usuarios.perfiles')
            ->with('ok', 'Acudientes actualizados correctamente para el estudiante ' . $user->name . '.');
    }
}
