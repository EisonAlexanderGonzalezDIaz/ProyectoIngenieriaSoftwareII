@extends('layouts.app')

@section('title', 'Tareas')

@section('content')
<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-tasks me-2"></i>Tareas</h2>
    <div id="tareas-area">Cargando...</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch("{{ route('estudiante.tareas.obtener') }}")
        .then(r=>r.json()).then(data=>{
            const rows = data.tareas || [];
            if(!rows.length){ document.getElementById('tareas-area').innerText='No hay tareas.'; return; }
            let html = '<ul class="list-group">';
            rows.forEach(t=>{
                html += `<li class="list-group-item"><strong>${t.titulo}</strong> — ${t.fecha_entrega}</li>`;
            });
            html += '</ul>';
            document.getElementById('tareas-area').innerHTML = html;
        });
});
</script>
@endsection
