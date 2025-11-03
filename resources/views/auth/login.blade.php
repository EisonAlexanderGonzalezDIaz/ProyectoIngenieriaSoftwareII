@extends('layouts.app')

@section('title', 'Iniciar Sesión - Colegio San Martín')

@section('content')
<style>
    body {
        background: url('{{ asset('img/colegio-fondo.jpg') }}') no-repeat center center fixed !important;
        background-size: cover;
        backdrop-filter: blur(4px);
        font-family: 'Poppins', sans-serif;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 41, 70, 0.45);
    }

    .login-card {
        background: rgba(255, 255, 255, 0.82); /* Más transparencia */
        border-radius: 20px;
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.3);
        padding: 2.5rem;
        transition: all 0.3s ease-in-out;
        position: relative;
        backdrop-filter: blur(10px);
    }

    .login-card:hover {
        transform: translateY(-4px);
    }

    .login-card h3 {
        color: #0d47a1;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .login-card p {
        color: #5f6368;
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: #0d47a1;
        font-weight: 600;
    }

    .form-control {
        border: none;
        border-radius: 30px;
        padding: 10px 15px;
        font-size: 15px;
        background-color: rgba(240, 246, 255, 0.8);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border: 1px solid #1976d2;
        box-shadow: 0 0 6px rgba(25, 118, 210, 0.3);
        background-color: rgba(255, 255, 255, 0.9);
    }

    /* Campo de contraseña estilo píldora */
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background-color: rgba(240, 246, 255, 0.8);
        border-radius: 30px;
        border: 1px solid #d0d7de;
        transition: all 0.3s ease;
    }

    .password-wrapper:focus-within {
        border-color: #1976d2;
        box-shadow: 0 0 6px rgba(25, 118, 210, 0.3);
        background-color: rgba(255, 255, 255, 0.9);
    }

    .password-input {
        border: none;
        background: transparent;
        flex: 1;
        padding: 10px 45px 10px 15px;
        border-radius: 30px;
        font-size: 15px;
        color: #333;
        outline: none;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: #6b7280;
        font-size: 1.1rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    .password-toggle:hover {
        color: #1976d2;
    }

    .btn-primary {
        background: linear-gradient(90deg, #1565c0, #42a5f5);
        border: none;
        border-radius: 30px;
        font-weight: bold;
        letter-spacing: 0.5px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(21, 101, 192, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0d47a1, #1976d2);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(21, 101, 192, 0.4);
    }

    .text-muted a {
        color: #0d47a1;
        text-decoration: none;
        font-weight: 600;
    }

    .text-muted a:hover {
        text-decoration: underline;
        color: #1565c0;
    }

    .school-logo {
        background: #1565c0;
        color: white;
        width: 75px;
        height: 75px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 10px rgba(21, 101, 192, 0.3);
    }
</style>

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card text-center">
                    <div class="school-logo mb-3">
                        <i class="fas fa-school fa-2x"></i>
                    </div>
                    <h3>Colegio San Martín</h3>
                    <p>Plataforma institucional — Inicia sesión</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1 text-primary"></i>Correo electrónico
                            </label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="nombre@colegio.edu.co" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1 text-primary"></i>Contraseña
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" class="password-input" placeholder="••••••••••" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check text-start">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}">Regístrate aquí</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection
