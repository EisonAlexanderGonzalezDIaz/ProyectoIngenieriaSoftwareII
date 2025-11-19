@extends('layouts.app')

@section('title', 'Reporte Financiero')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Reporte Financiero</h3>
    <div class="card">
        <div class="card-body">
            <form id="form-reporte">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Desde</label>
                        <input name="desde" class="form-control" placeholder="YYYY-MM-DD">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Hasta</label>
                        <input name="hasta" class="form-control" placeholder="YYYY-MM-DD">
                    </div>
                </div>
                <button class="btn btn-primary">Generar</button>
            </form>
            <hr>
            <pre id="result" style="white-space:pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('form-reporte').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const params = new URLSearchParams();
    if (form.desde.value) params.set('desde', form.desde.value);
    if (form.hasta.value) params.set('hasta', form.hasta.value);
    const res = await fetch('/tesoreria/reporte-financiero' + (params.toString() ? ('?' + params.toString()) : ''), { headers: { 'Accept':'application/json' } });
    const json = await res.json();
    document.getElementById('result').textContent = JSON.stringify(json, null, 2);
});
</script>

@endsection
