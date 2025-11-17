@extends('layouts.app')

@section('title', 'Consultar Notas')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mis Notas por Materia</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <label for="materiaSelect" class="form-label">Seleccionar Materia</label>
                        <select id="materiaSelect" class="form-control">
                            <option value="">-- Todas las Materias --</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Calificaciones</h5>
                </div>
                <div class="card-body">
                    <div id="notasTable">
                        <p class="text-muted text-center">Cargando notas...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarNotas();
    
    document.getElementById('materiaSelect').addEventListener('change', function() {
        cargarNotas(this.value);
    });
});

function cargarNotas(materiaId = null) {
    const url = new URL('{{ route("estudiante.obtener_notas") }}', window.location.origin);
    if (materiaId) url.searchParams.append('materia_id', materiaId);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.notas && data.notas.length > 0) {
                renderizarTabla(data.notas);
            } else {
                document.getElementById('notasTable').innerHTML = `
                    <div class="alert alert-info">No hay notas disponibles.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('notasTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar las notas.</div>
            `;
        });
}

function renderizarTabla(notas) {
    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Materia</th><th>Período</th><th>Calificación</th><th>Porcentaje</th></tr></thead>';
    html += '<tbody>';

    notas.forEach(nota => {
        const materia = nota.materia ? nota.materia.nombre : 'N/A';
        const badgeClass = nota.calificacion >= 3 ? 'success' : 'danger';
        html += `<tr>
            <td>${materia}</td>
            <td>${nota.periodo}</td>
            <td><span class="badge bg-${badgeClass}">${nota.calificacion}</span></td>
            <td>${nota.porcentaje}%</td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById('notasTable').innerHTML = html;
}
</script>
@endsection
