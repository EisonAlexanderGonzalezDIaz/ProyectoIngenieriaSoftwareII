@extends('layouts.app')

@section('title', 'Gestionar Devolución')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Gestionar Devolución</h3>
    <div class="card">
        <div class="card-body">
            <form id="form-devolucion">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Pago</label>
                    <input name="pago_id" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Motivo</label>
                    <input name="motivo" class="form-control" />
                </div>
                <button class="btn btn-primary">Procesar Devolución</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-devolucion').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const data = {pago_id: form.pago_id.value, motivo: form.motivo.value};
    const res = await fetch('/tesoreria/devolucion', {method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify(data)});
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
