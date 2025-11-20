<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BecaSolicitud;

// Create test beca solicitud
$beca = BecaSolicitud::create([
    'acudiente_id' => 1,
    'estudiante_id' => 6,
    'tipo' => 'Beca Completa',
    'monto_estimado' => 5000000,
    'detalle' => 'Solicitud de prueba para beca completa',
    'estado' => 'solicitado',
    'fecha_solicitud' => now(),
]);

echo "✓ Beca created:\n";
echo json_encode($beca->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

// Create another one
$beca2 = BecaSolicitud::create([
    'acudiente_id' => 1,
    'estudiante_id' => 7,
    'tipo' => 'Beca Parcial',
    'monto_estimado' => 2500000,
    'detalle' => 'Segunda solicitud de prueba',
    'estado' => 'solicitado',
    'fecha_solicitud' => now(),
]);

echo "\n✓ Second beca created:\n";
echo json_encode($beca2->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

// List all becas
$all = BecaSolicitud::all();
echo "\nTotal becas in DB: " . $all->count() . "\n";
echo json_encode($all->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
