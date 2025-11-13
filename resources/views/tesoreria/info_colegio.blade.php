@extends('layouts.app')

@section('title', 'Información del Colegio')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Información del Colegio</h3>
    <div class="card">
        <div class="card-body">
            <button id="btn-info" class="btn btn-outline-primary mb-3">Cargar Información</button>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-info').addEventListener('click', async function(){
    const res = await fetch('/tesoreria/api/info-colegio', { headers: { 'Accept':'application/json' } });
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
