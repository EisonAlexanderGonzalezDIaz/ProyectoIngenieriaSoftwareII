@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Solicitar beca / descuento</h3>

    <form id="becaForm">
        <div class="mb-3">
            <label for="estudiante_id" class="form-label">Estudiante</label>
            <select class="form-select" id="estudiante_id" name="estudiante_id">
                <option value="">-- Seleccionar (opcional) --</option>
                @foreach($estudiantes as $est)
                    <option value="{{ $est->id }}">{{ $est->name ?? $est->nombre ?? 'Estudiante '.$est->id }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de beca</label>
            <input class="form-control" id="tipo" name="tipo" required />
        </div>

        <div class="mb-3">
            <label for="monto_estimado" class="form-label">Monto estimado</label>
            <input class="form-control" id="monto_estimado" name="monto_estimado" type="number" step="0.01" />
        </div>

        <div class="mb-3">
            <label for="detalle" class="form-label">Detalle</label>
            <textarea class="form-control" id="detalle" name="detalle" rows="4"></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Enviar solicitud</button>
    </form>

    <div id="becaResult" class="mt-3" style="display:none"></div>
</div>

@push('scripts')
<script>
document.getElementById('becaForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const body = {};
    data.forEach((v,k)=> body[k]=v);

    const res = await fetch("{{ route('acudiente.crear_solicitud_beca') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
        body: new URLSearchParams(body)
    });
    const json = await res.json();
    const div = document.getElementById('becaResult');
    div.style.display = 'block';
    if(res.ok){
        div.className = 'alert alert-success';
        div.innerText = json.message || 'Solicitud enviada';
        form.reset();
    } else {
        div.className = 'alert alert-danger';
        div.innerText = (json.message || 'Error al enviar') + '\n' + (JSON.stringify(json.errors || {}));
    }
});
</script>
@endpush

@endsection
