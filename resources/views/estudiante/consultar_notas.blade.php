<?php
@extends('layouts.app')

@section('title', 'Notas')

@section('content')
<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>Mis notas</h2>
    <div id="notas-area">Cargando...</div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // ejemplo carga sin filtro
    fetch("{{ route('estudiante.notas.obtener') }}", { method: 'POST', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({}) })
        .then(r=>r.json()).then(data=>{
            const rows = data.notas || [];
            if(!rows.length){ document.getElementById('notas-area').innerText='No hay notas disponibles.'; return; }
            let html = '<table class="table"><thead><tr><th>Materia</th><th>Periodo</th><th>Calificación</th></tr></thead><tbody>';
            rows.forEach(n=>{
                html += `<tr><td>${n.materia?.nombre ?? 'N/A'}</td><td>${n.periodo}</td><td>${n.calificacion}</td></tr>`;
            });
            html += '</tbody></table>';
            document.getElementById('notas-area').innerHTML = html;
        });
});
</script>
@endsection
