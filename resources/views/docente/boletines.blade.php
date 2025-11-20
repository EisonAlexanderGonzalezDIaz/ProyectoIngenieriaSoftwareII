<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Boletines - Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-newspaper me-2"></i>Consultar Boletines de Estudiantes</h2>
            
            <div class="card border-0 shadow">
                <div class="card-body">
                    <p class="text-muted">Revisa los boletines generados de tus estudiantes por período académico.</p>
                    
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Seleccionar Curso</label>
                                <select class="form-select" name="curso_id">
                                    <option value="">-- Todos los cursos --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Período</label>
                                <select class="form-select" name="periodo">
                                    <option value="">-- Todos los períodos --</option>
                                    <option value="periodo_1">Período 1</option>
                                    <option value="periodo_2">Período 2</option>
                                    <option value="periodo_3">Período 3</option>
                                    <option value="periodo_4">Período 4</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
                                    <th>Período</th>
                                    <th>Fecha Generación</th>
                                    <th>Promedio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-3"></i><br>
                                        No hay boletines disponibles
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
