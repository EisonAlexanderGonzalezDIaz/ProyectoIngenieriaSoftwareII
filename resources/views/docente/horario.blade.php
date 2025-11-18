@extends('layouts.app')

@section('title', 'Consultar Horario - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mi Horario de Clases</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <label class="form-label">Filtrar por materia (opcional)</label>
                    <div class="row">
                        <div class="col-md-8">
                            <select id="materiaSelect" class="form-control">
                                <option value="">Todas las materias</option>
                                @foreach($materias as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary" onclick="descargarHorario()">Descargar PDF</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Horarios de Clase</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="horariosTable">
                        <thead class="table-light">
                            <tr>
                                <th>Día</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Materia</th>
                                <th>Salón</th>
                                <th>Curso</th>
                            </tr>
                        </thead>
                        <tbody id="horariosBody">
                            <tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cargarHorarios() {
    const materiaId = document.getElementById('materiaSelect').value;
    const url = new URL('{{ route('docente.obtener_horarios') }}', window.location.origin);
    if (materiaId) url.searchParams.append('materia_id', materiaId);

    fetch(url)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('horariosBody');
            const horarios = data.horarios || [];
            
            if (horarios.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay horarios registrados.</td></tr>';
                return;
            }

            let html = '';
            horarios.forEach(h => {
                html += `<tr>
                    <td>${h.dia_semana}</td>
                    <td>${h.hora_inicio}</td>
                    <td>${h.hora_fin}</td>
                    <td>${h.materia_nombre || '—'}</td>
                    <td>${h.salon}</td>
                    <td>${h.curso_nombre || '—'}</td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

function descargarHorario() {
    const materiaId = document.getElementById('materiaSelect').value;
    const url = new URL('{{ route('docente.descargar_horario') }}', window.location.origin);
    if (materiaId) url.searchParams.append('materia_id', materiaId);
    window.location.href = url.toString();
}

document.getElementById('materiaSelect').addEventListener('change', cargarHorarios);
cargarHorarios();
</script>
@endsection
