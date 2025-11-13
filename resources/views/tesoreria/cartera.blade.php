@extends('layouts.app')

@section('title', 'Gestionar Carteras')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Cartera: Pagos Pendientes</h3>
    <div class="card">
        <div class="card-body">
            <button id="btn-refresh" class="btn btn-outline-primary mb-3">Refrescar</button>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
async function loadCartera(){
    const res = await fetch('/tesoreria/api/cartera', { headers: { 'Accept':'application/json' } });
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
}
document.getElementById('btn-refresh').addEventListener('click', loadCartera);
loadCartera();
</script>

@endsection
