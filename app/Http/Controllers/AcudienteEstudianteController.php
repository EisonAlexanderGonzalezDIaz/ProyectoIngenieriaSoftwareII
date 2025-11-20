<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AcudienteEstudianteController extends Controller
{
    /**
     * Ver y gestionar los acudientes de un estudiante.
     */
    public function index($estudianteId)
    {
        $estudiante = User::with(['rol', 'acudientes.rol'])
            ->findOrFail($estudianteId);

        // Validar que realmente sea estudiante
        if (optional($estudiante->rol)->nombre !== 'Estudiante') {
            abort(404, 'El usuario no es un estudiante.');
        }

        return view('admin.estudiantes.acudientes', [
            'estudiante'  => $estudiante,
            'acudientes'  => $estudiante->acudientes,
        ]);
    }

    /**
     * Vincular un acudiente a un estudiante.
     */
    public function vincular(Request $request, $estudianteId)
    {
        $request->validate([
            'email_acudiente' => 'required|email',
        ]);

        $estudiante = User::with('acudientes')->findOrFail($estudianteId);

        if (optional($estudiante->rol)->nombre !== 'Estudiante') {
            abort(404, 'El usuario no es un estudiante.');
        }

        // Buscar un usuario con ese email y rol "Acudiente"
        $acudiente = User::where('email', $request->email_acudiente)
            ->whereHas('rol', function ($q) {
                $q->where('nombre', 'Acudiente');
            })
            ->first();

        if (!$acudiente) {
            return back()
                ->withErrors([
                    'email_acudiente' => 'No se encontró un acudiente con ese correo o no tiene rol Acudiente.',
                ])
                ->withInput();
        }

        // Evitar duplicados
        if (!$estudiante->acudientes->contains($acudiente->id)) {
            $estudiante->acudientes()->attach($acudiente->id);
        }

        return back()->with('ok', 'Acudiente vinculado correctamente.');
    }

    /**
     * Desvincular un acudiente de un estudiante.
     */
    public function desvincular($estudianteId, $acudienteId)
    {
        $estudiante = User::findOrFail($estudianteId);

        if (optional($estudiante->rol)->nombre !== 'Estudiante') {
            abort(404, 'El usuario no es un estudiante.');
        }

        $estudiante->acudientes()->detach($acudienteId);

        return back()->with('ok', 'Acudiente desvinculado correctamente.');
    }
}
