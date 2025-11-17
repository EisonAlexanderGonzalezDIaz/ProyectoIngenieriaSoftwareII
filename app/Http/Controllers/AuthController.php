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

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Si está inactivo, no lo dejamos entrar
        if ($user->activo === 0) {
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