@extends('layouts.app')

@section('title', 'Registrar Beca o Descuento')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Registrar Beca / Descuento</h3>
    <div class="card">
        <div class="card-body">
            <form id="form-beca">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Acudiente</label>
                    <input name="acudiente_id" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">ID Matrícula (opcional)</label>
                    <input name="matricula_id" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Monto</label>
                    <input name="monto" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input name="descripcion" class="form-control" />
                </div>
                <button class="btn btn-primary">Registrar</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-beca').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const data = {acudiente_id: form.acudiente_id.value, matricula_id: form.matricula_id.value || null, monto: form.monto.value, descripcion: form.descripcion.value};
    const res = await fetch('/tesoreria/beca', {method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify(data)});
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
