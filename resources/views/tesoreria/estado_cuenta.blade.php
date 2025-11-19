@extends('layouts.app')

@section('title', 'Estado de Cuenta')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Consultar Estado de Cuenta</h3>
    <div class="card">
        <div class="card-body">
            <form id="form-estado">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Acudiente</label>
                    <input name="acudiente_id" class="form-control" />
                </div>
                <button class="btn btn-primary">Consultar</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-estado').addEventListener('submit', async function(e){
    e.preventDefault();
    const id = e.target.acudiente_id.value;
    if (!id) return alert('Ingrese ID');
    const res = await fetch('/tesoreria/estado-cuenta/' + encodeURIComponent(id), { headers: { 'Accept':'application/json' } });
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
