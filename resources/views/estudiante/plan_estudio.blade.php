@extends('layouts.app')

@section('title', 'Plan de estudio')

@section('content')
<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-book me-2"></i>Plan de estudio</h2>
    <div id="plan-area">Cargando...</div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch("{{ route('estudiante.plan_estudio.obtener') }}")
        .then(r=>r.json()).then(data=>{
            const rows = data.plan || [];
            if(!rows.length){ document.getElementById('plan-area').innerText='No hay plan de estudio.'; return; }
            let html = '<ul class="list-group">';
            rows.forEach(p=>{
                html += `<li class="list-group-item">${p.materia?.nombre ?? 'N/A'} — ${p.periodo}</li>`;
            });
            html += '</ul>';
            document.getElementById('plan-area').innerHTML = html;
        });
});
</script>
@endsection
