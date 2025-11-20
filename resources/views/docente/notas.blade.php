@extends('layouts.app')

@section('title', 'Registrar Notas - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Registrar Notas</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Curso</label>
                            <select id="cursoSelect" class="form-control" required>
                                <option value="">Seleccionar curso...</option>
                                @foreach($cursos as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Materia</label>
                            <select id="materiaSelect" class="form-control" required onchange="cargarEstudiantes()">
                                <option value="">Seleccionar materia...</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-secondary" onclick="cargarEstudiantes()">Cargar Estudiantes</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Estudiantes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Período</th>
                                <th>Calificación (0-5)</th>
                                <th>Porcentaje (%)</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="estudiantesBody">
                            <tr><td colspan="6" class="text-center text-muted">Selecciona un curso y materia para cargar estudiantes.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Poblar materias según curso seleccionado
document.getElementById('cursoSelect').addEventListener('change', function() {
    const cursoId = this.value;
    const materiaSelect = document.getElementById('materiaSelect');
    materiaSelect.innerHTML = '<option value="">Seleccionar materia...</option>';
    
    if (!cursoId) return;
    
    fetch(`/api/docente/materias-por-curso?curso_id=${cursoId}`)
        .then(r => r.json())
        .then(data => {
            (data.materias || []).forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                materiaSelect.appendChild(opt);
            });
        });
});

function cargarEstudiantes() {
    const cursoId = document.getElementById('cursoSelect').value;
    const materiaId = document.getElementById('materiaSelect').value;

    if (!cursoId || !materiaId) {
        alert('Selecciona curso y materia');
        return;
    }

    fetch(`{{ route('docente.obtener_estudiantes_por_curso') }}?curso_id=${cursoId}&subject_id=${materiaId}`)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('estudiantesBody');
            const estudiantes = data.estudiantes || [];

            if (estudiantes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay estudiantes en este curso.</td></tr>';
                return;
            }

            let html = '';
            estudiantes.forEach(e => {
                html += `<tr>
                    <td>${e.name}</td>
                    <td>${e.email}</td>
                    <td><input type="text" class="form-control form-control-sm" placeholder="P1" data-periodo data-est="${e.id}"></td>
                    <td><input type="number" class="form-control form-control-sm" min="0" max="5" step="0.1" placeholder="0" data-calificacion data-est="${e.id}"></td>
                    <td><input type="number" class="form-control form-control-sm" min="0" max="100" placeholder="0" data-porcentaje data-est="${e.id}"></td>
                    <td><button class="btn btn-sm btn-success" onclick="guardarNota(${e.id}, ${materiaId})">Guardar</button></td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

function guardarNota(estudianteId, materiaId) {
    const fila = document.querySelector(`[data-est="${estudianteId}"]`).closest('tr');
    const periodo = fila.querySelector('[data-periodo]').value;
    const calificacion = fila.querySelector('[data-calificacion]').value;
    const porcentaje = fila.querySelector('[data-porcentaje]').value;

    if (!periodo || !calificacion || !porcentaje) {
        alert('Completa todos los campos');
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('{{ route('docente.guardar_nota') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            estudiante_id: estudianteId,
            subject_id: materiaId,
            periodo: periodo,
            calificacion: calificacion,
            porcentaje: porcentaje
        })
    })
    .then(r => r.json())
    .then(json => {
        if (json.success) {
            alert('Nota guardada');
            fila.style.backgroundColor = '#d4edda';
        } else {
            alert('Error: ' + (json.error || 'No guardado'));
        }
    });
}
</script>
@endsection
