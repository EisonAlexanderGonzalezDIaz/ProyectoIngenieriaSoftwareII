<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================
    // Mostrar formulario de login
    // ==========================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ==========================
    // Procesar login
    // ==========================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Si el usuario está marcado como inactivo, no lo dejamos entrar
            if (Schema::hasColumn('users', 'activo') && $user->activo === 0) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Tu usuario está inactivo. Contacta al Administrador del Sistema.',
                ])->withInput($request->only('email'));
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->withInput($request->only('email'));
    }

    // ==========================
    // Dashboard / Menú principal
    // ==========================
    public function dashboard()
    {
        $usuario = Auth::user();

        // Total de estudiantes (rol = Estudiante)
        $totalEstudiantes = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'Estudiante');
        })->count();

        // Total de docentes (rol = Docente)
        $totalDocentes = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'Docente');
        })->count();

        // Total de materias (si existe tabla 'materias')
        $totalMaterias = Schema::hasTable('materias')
            ? DB::table('materias')->count()
            : 0;

        // Total de eventos (si existe tabla 'eventos')
        $totalEventos = Schema::hasTable('eventos')
            ? DB::table('eventos')->count()
            : 0;

        return view('dashboard', [
            'usuario'          => $usuario,
            'totalEstudiantes' => $totalEstudiantes,
            'totalDocentes'    => $totalDocentes,
            'totalMaterias'    => $totalMaterias,
            'totalEventos'     => $totalEventos,
        ]);
    }

    // ==========================
    // Logout
    // ==========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
