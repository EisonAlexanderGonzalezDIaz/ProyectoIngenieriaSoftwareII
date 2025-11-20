@extends('layouts.app')

@section('title', 'Reportes Disciplinarios')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mis Reportes Disciplinarios</h1>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Registro de Incidentes Disciplinarios</h5>
                </div>
                <div class="card-body">
                    <div id="reportesTable">
                        <p class="text-muted text-center">Cargando reportes...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarReportes();
});

function cargarReportes() {
    fetch('{{ route("estudiante.obtener_reportes_disciplinarios") }}')
        .then(response => response.json())
        .then(data => {
            if (data.reportes && data.reportes.length > 0) {
                renderizarTabla(data.reportes);
            } else {
                document.getElementById('reportesTable').innerHTML = `
                    <div class="alert alert-success">No tienes reportes disciplinarios registrados.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('reportesTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar los reportes.</div>
            `;
        });
}

function renderizarTabla(reportes) {
    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Fecha</th><th>Docente</th><th>Tipo de Falta</th><th>Descripción</th><th>Sanción</th></tr></thead>';
    html += '<tbody>';

    reportes.forEach(reporte => {
        const fecha = new Date(reporte.fecha).toLocaleDateString('es-ES');
        const docente = reporte.docente ? reporte.docente.name : 'N/A';
        const tipoFaltaBadgeClass = {
            'leve': 'warning',
            'grave': 'danger',
            'muy_grave': 'dark'
        }[reporte.tipo_falta] || 'secondary';

        html += `<tr>
            <td>${fecha}</td>
            <td>${docente}</td>
            <td><span class="badge bg-${tipoFaltaBadgeClass}">${reporte.tipo_falta}</span></td>
            <td>${reporte.descripcion}</td>
            <td><strong>${reporte.sancion}</strong></td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById('reportesTable').innerHTML = html;
}
</script>
@endsection
