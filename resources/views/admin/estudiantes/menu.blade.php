@extends('layouts.app')

@section('title', 'Consultar estudiantes')

@section('content')
@include('partials.navbar')

<div class="container py-4">
    <h1 class="h4 text-primary mb-4">Consultar estudiantes</h1>

    <div class="row">
        <div class="col-md-6 mb-3">
            <a href="{{ route('admin.estudiantes.porCurso') }}" class="btn btn-primary w-100">
                <i class="fas fa-layer-group me-2"></i> Ver estudiantes por curso
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('admin.usuarios.perfiles') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-users-cog me-2"></i> Gestionar perfiles de usuario
            </a>
        </div>
    </div>
</div>
@endsection
