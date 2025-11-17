@extends('layouts.app')

@section('title', 'Plan de Estudio')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mi Plan de Estudio</h1>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Materias por Período</h5>
                </div>
                <div class="card-body">
                    <div id="planTable">
                        <p class="text-muted text-center">Cargando plan de estudio...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPlanEstudio();
});

function cargarPlanEstudio() {
    fetch('{{ route("estudiante.obtener_plan_estudio") }}')
        .then(response => response.json())
        .then(data => {
            if (data.plan && data.plan.length > 0) {
                renderizarTabla(data.plan);
            } else {
                document.getElementById('planTable').innerHTML = `
                    <div class="alert alert-info">No hay plan de estudio disponible.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('planTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar el plan de estudio.</div>
            `;
        });
}

function renderizarTabla(plan) {
    // Agrupar por período
    const porPeriodo = {};
    plan.forEach(item => {
        if (!porPeriodo[item.periodo]) porPeriodo[item.periodo] = [];
        porPeriodo[item.periodo].push(item);
    });

    let html = '';
    Object.keys(porPeriodo).sort().forEach(periodo => {
        html += `<h5 class="mt-4 mb-3">Período ${periodo}</h5>`;
        html += '<table class="table table-sm table-striped">';
        html += '<thead class="table-secondary"><tr><th>Materia</th><th>Intensidad Horaria</th></tr></thead>';
        html += '<tbody>';

        porPeriodo[periodo].forEach(item => {
            const materia = item.materia ? item.materia.nombre : 'N/A';
            html += `<tr>
                <td>${materia}</td>
                <td>${item.intensidad_horaria} horas</td>
            </tr>`;
        });

        html += '</tbody></table>';
    });

    if (Object.keys(porPeriodo).length === 0) {
        html = '<div class="alert alert-info">No hay materias en tu plan de estudio.</div>';
    }

    document.getElementById('planTable').innerHTML = html;
}
</script>
@endsection
