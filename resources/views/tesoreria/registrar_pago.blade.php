@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Registrar Pago de Acudiente</h3>
    <div class="card">
        <div class="card-body">
            <form id="form-pago">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Acudiente</label>
                    <input name="acudiente_id" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Monto</label>
                    <input name="monto" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Método</label>
                    <input name="metodo" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input name="descripcion" class="form-control" />
                </div>
                <button class="btn btn-primary">Registrar Pago</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-pago').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const data = {acudiente_id: form.acudiente_id.value, monto: form.monto.value, metodo: form.metodo.value, descripcion: form.descripcion.value};
    const res = await fetch('/tesoreria/pago/registrar', {method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify(data)});
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
