<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login (con verificación de activo)
    public function login(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Intentar autenticación
        if (Auth::attempt($credentials)) {
            // Regenerar la sesión por seguridad
            $request->session()->regenerate();

            $user = Auth::user();

            // Si el usuario tiene columna 'activo' y está en 0, no lo dejamos entrar
            if (isset($user->activo) && $user->activo === 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Tu usuario está inactivo. Contacta al Administrador del Sistema.',
                ])->onlyInput('email');
            }

            // Si todo está bien, lo enviamos al dashboard
            return redirect()->intended('dashboard');
        }

        // Credenciales incorrectas
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Mostrar dashboard/menú principal
    public function dashboard()
    {
        return view('dashboard');
    }

    // Procesar logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
