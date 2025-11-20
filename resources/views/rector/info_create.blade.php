@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear información del colegio</h1>

    <form action="{{ route('rector.info.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="contenido" class="form-control" rows="6" required></textarea>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
