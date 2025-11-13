@extends('layouts.app')

@section('title', 'Generar Paz y Salvo')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Generar Paz y Salvo</h3>

    <div class="card">
        <div class="card-body">
            <form id="form-pazysalvo">
                @csrf
                <div class="mb-3">
                    <label for="acudiente_id" class="form-label">ID Acudiente</label>
                    <input type="text" id="acudiente_id" name="acudiente_id" class="form-control" placeholder="Ingrese ID del acudiente">
                </div>
                <button class="btn btn-primary">Generar</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-pazysalvo').addEventListener('submit', async function(e){
    e.preventDefault();
    const id = document.getElementById('acudiente_id').value;
    if (!id) return alert('Ingresa un ID.');
    const res = await fetch('/tesoreria/paz-y-salvo/' + encodeURIComponent(id), { headers: { 'Accept': 'application/json' } });
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
