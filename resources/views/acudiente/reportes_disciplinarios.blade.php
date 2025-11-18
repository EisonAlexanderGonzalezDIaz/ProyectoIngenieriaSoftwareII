@extends('layouts.app')

@section('title', 'Reportes Disciplinarios - Acudiente')

@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-10 mx-auto">
      <h1 class="mb-4">Reportes Disciplinarios de tus hijos</h1>

      <div class="card">
        <div class="card-body">
          <div id="reportesList"><p class="text-muted">Cargando reportes...</p></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function cargarReportes() {
  fetch('{{ route('acudiente.obtener_reportes') }}')
    .then(r => r.json())
    .then(data => {
      const items = data.reportes || [];
      if (!items.length) {
        document.getElementById('reportesList').innerHTML = '<div class="alert alert-info">No hay reportes disciplinarios para tus hijos.</div>';
        return;
      }
      let html = '';
      items.forEach(rp => {
        html += `<div class="mb-3">
          <h5>${rp.titulo || 'Reporte'}</h5>
          <p class="text-muted">Alumno: ${rp.estudiante_nombre || '—'} • ${new Date(rp.created_at).toLocaleString()}</p>
          <p>${rp.descripcion}</p>
        </div>`;
      });
      document.getElementById('reportesList').innerHTML = html;
    });
}

cargarReportes();
</script>
@endsection
