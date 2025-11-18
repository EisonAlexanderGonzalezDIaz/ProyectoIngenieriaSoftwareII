@extends('layouts.app')

@section('title', 'Boletines - Acudiente')

@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-10 mx-auto">
      <h1 class="mb-4">Boletines</h1>

      <div class="card">
        <div class="card-body">
          <div id="boletinesList"><p class="text-muted">Cargando boletines...</p></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function cargarBoletines() {
  fetch('{{ route('acudiente.obtener_boletines') }}')
    .then(r => r.json())
    .then(data => {
      const items = data.boletines.data || [];
      if (items.length === 0) {
        document.getElementById('boletinesList').innerHTML = '<div class="alert alert-info">No hay boletines publicados.</div>';
        return;
      }
      let html = '';
      items.forEach(b => {
        html += `<div class="mb-3">
          <h5>${b.titulo}</h5>
          <p class="text-muted">${new Date(b.created_at).toLocaleDateString()}</p>
          <p>${b.resumen || ''}</p>
          <a class="btn btn-sm btn-outline-primary" href="${b.archivo_url ? '/storage/' + b.archivo_url : '#'}" target="_blank">Ver/Descargar</a>
        </div>`;
      });
      document.getElementById('boletinesList').innerHTML = html;
    });
}

cargarBoletines();
</script>
@endsection
