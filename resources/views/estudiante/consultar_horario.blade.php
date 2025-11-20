@extends('layouts.app')

@section('title', 'Horario')

@section('content')
<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-clock me-2"></i>Horario</h2>
    <div id="horario-area">Cargando...</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch("{{ route('estudiante.horario.obtener') }}")
        .then(r=>r.json()).then(data=>{
            const rows = data.horarios || [];
            if(!rows.length){ document.getElementById('horario-area').innerText='No hay clases.'; return; }
            let html = '<table class="table"><thead><tr><th>Día</th><th>Inicio</th><th>Fin</th><th>Materia</th><th>Docente</th><th>Aula</th></tr></thead><tbody>';
            rows.forEach(h=>{
                html += `<tr><td>${h.dia}</td><td>${h.hora_inicio}</td><td>${h.hora_fin}</td><td>${h.materia?.nombre ?? 'N/A'}</td><td>${h.docente?.name ?? 'N/A'}</td><td>${h.aula ?? ''}</td></tr>`;
            });
            html += '</tbody></table>';
            document.getElementById('horario-area').innerHTML = html;
        });
});
</script>
@endsection
