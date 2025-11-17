@extends('layouts.app')

@section('title', 'Consultar Boletines')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mis Boletines</h1>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Boletines por Período</h5>
                </div>
                <div class="card-body">
                    <div id="boletinesTable">
                        <p class="text-muted text-center">Cargando boletines...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarBoletines();
});

function cargarBoletines() {
    fetch('{{ route("estudiante.obtener_boletines") }}')
        .then(response => response.json())
        .then(data => {
            if (data.boletines && data.boletines.length > 0) {
                renderizarTabla(data.boletines);
            } else {
                document.getElementById('boletinesTable').innerHTML = `
                    <div class="alert alert-info">No hay boletines disponibles.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('boletinesTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar los boletines.</div>
            `;
        });
}

function renderizarTabla(boletines) {
    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Período</th><th>Fecha de Emisión</th><th>Acciones</th></tr></thead>';
    html += '<tbody>';

    boletines.forEach(boletin => {
        const fechaEmision = new Date(boletin.fecha_emision).toLocaleDateString('es-ES');
        html += `<tr>
            <td><strong>${boletin.periodo}</strong></td>
            <td>${fechaEmision}</td>
            <td>
                <a href="{{ route('estudiante.descargar_boletin', '') }}/${boletin.id}" class="btn btn-sm btn-success">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById('boletinesTable').innerHTML = html;
}
</script>
@endsection
