@extends('layouts.app')

@section('title', 'Boletines')

@section('content')
<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-newspaper me-2"></i>Boletines</h2>
    <div id="boletines-area">Cargando...</div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch("{{ route('estudiante.boletines.obtener') }}")
        .then(r=>r.json()).then(data=>{
            const rows = data.boletines || [];
            if(!rows.length){ document.getElementById('boletines-area').innerText='No hay boletines.'; return; }
            let html = '<ul class="list-group">';
            rows.forEach(b=>{
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    ${b.periodo} <a href="{{ url('estudiante/boletines') }}/${b.id}/descargar" class="btn btn-sm btn-outline-primary">Descargar</a>
                </li>`;
            });
            html += '</ul>';
            document.getElementById('boletines-area').innerHTML = html;
        });
});
</script>
@endsection
