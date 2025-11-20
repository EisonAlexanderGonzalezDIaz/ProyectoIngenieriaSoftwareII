@extends('layouts.app')

@section('title', 'Solicitar Cita de Orientación')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">Solicitar Cita de Orientación</h1>

            <!-- Formulario para crear cita -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Nueva Solicitud de Cita</h5>
                </div>
                <div class="card-body">
                    <form id="citaForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="orientador_id" class="form-label">Orientador (Opcional)</label>
                            <select id="orientador_id" name="orientador_id" class="form-control">
                                <option value="">-- Seleccionar Orientador --</option>
                                @foreach($orientadores as $orientador)
                                    <option value="{{ $orientador->id }}">{{ $orientador->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fecha" class="form-label">Fecha Deseada *</label>
                            <input type="date" id="fecha" name="fecha" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hora" class="form-label">Hora Deseada *</label>
                            <input type="time" id="hora" name="hora" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="motivo" class="form-label">Motivo de la Cita *</label>
                            <textarea id="motivo" name="motivo" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Solicitar Cita</button>
                    </form>
                </div>
            </div>

            <!-- Lista de citas del estudiante -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Mis Citas Solicitadas</h5>
                </div>
                <div class="card-body">
                    <div id="citasList">
                        <p class="text-muted">Cargando citas...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar citas existentes
    cargarCitas();

    // Manejar envío del formulario
    document.getElementById('citaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        crearCita();
    });
});

function cargarCitas() {
    // Aquí iría la lógica AJAX para cargar las citas del estudiante
    // Por ahora mostrar un ejemplo estático
    document.getElementById('citasList').innerHTML = `
        <div class="alert alert-info">
            No tienes citas solicitadas aún.
        </div>
    `;
}

function crearCita() {
    const formData = {
        orientador_id: document.getElementById('orientador_id').value || null,
        fecha: document.getElementById('fecha').value,
        hora: document.getElementById('hora').value,
        motivo: document.getElementById('motivo').value,
        _token: document.querySelector('input[name="_token"]').value
    };

    fetch('{{ route("estudiante.crear_cita") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.cita) {
            alert('Cita solicitada exitosamente');
            document.getElementById('citaForm').reset();
            cargarCitas();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al solicitar la cita');
    });
}
</script>
@endsection
