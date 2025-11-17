@extends('layouts.app')

@section('title', 'Consultar Horario')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mi Horario de Clases</h1>
                <a href="{{ route('estudiante.descargar_horario') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Descargar Horario
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div id="horarioTable">
                        <p class="text-muted text-center">Cargando horario...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarHorarios();
});

function cargarHorarios() {
    fetch('{{ route("estudiante.obtener_horarios") }}')
        .then(response => response.json())
        .then(data => {
            if (data.horarios && data.horarios.length > 0) {
                renderizarTabla(data.horarios);
            } else {
                document.getElementById('horarioTable').innerHTML = `
                    <div class="alert alert-info">No hay horarios disponibles.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('horarioTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar el horario.</div>
            `;
        });
}

function renderizarTabla(horarios) {
    const diasOrden = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const horariosPorDia = {};

    // Agrupar por día
    horarios.forEach(h => {
        if (!horariosPorDia[h.dia]) horariosPorDia[h.dia] = [];
        horariosPorDia[h.dia].push(h);
    });

    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Día</th><th>Hora</th><th>Materia</th><th>Docente</th><th>Aula</th></tr></thead>';
    html += '<tbody>';

    diasOrden.forEach(dia => {
        if (horariosPorDia[dia]) {
            horariosPorDia[dia].forEach(h => {
                const docente = h.docente ? h.docente.name : 'N/A';
                const materia = h.materia ? h.materia.nombre : 'N/A';
                html += `<tr>
                    <td>${h.dia}</td>
                    <td>${h.hora_inicio} - ${h.hora_fin}</td>
                    <td>${materia}</td>
                    <td>${docente}</td>
                    <td>${h.aula}</td>
                </tr>`;
            });
        }
    });

    html += '</tbody></table>';
    document.getElementById('horarioTable').innerHTML = html;
}
</script>
@endsection
