<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\MatriculaAcudiente;
use App\Models\User;
use App\Models\BecaSolicitud;
use App\Models\Notificacion;
use Illuminate\Support\Facades\DB;

class TesoreroController extends Controller
{
    // Generar paz y salvo para un acudiente (verifica que no haya deudas pendientes)
    public function generarPazYSalvo($acudienteId)
    {
        $acudiente = User::find($acudienteId);
        if (!$acudiente) {
            return response()->json(['error' => 'Acudiente no encontrado.'], 404);
        }

        $deudas = Pago::where('acudiente_id', $acudienteId)->where('estado', 'pendiente')->sum('monto');
        if ($deudas > 0) {
            return response()->json([ 'paz_y_salvo' => false, 'monto_pendiente' => (float)$deudas ], 200);
        }

        // Generamos un token simple que representa el documento
        $token = bin2hex(random_bytes(8));
        $document = [
            'acudiente_id' => $acudienteId,
            'nombre' => $acudiente->name ?? ($acudiente->nombres ?? 'Sin nombre'),
            'fecha' => now()->toDateTimeString(),
            'token' => $token,
            'nota' => 'Paz y salvo generado automáticamente',
        ];

        return response()->json(['paz_y_salvo' => $document], 200);
    }

    // Generar factura de matrícula (crea un registro de tipo factura en pagos)
    public function generarFacturaMatricula(Request $request)
    {
        $data = $request->validate([
            'matricula_id' => 'nullable|integer|exists:matriculas_acudientes,id',
            'acudiente_id' => 'required|integer|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $factura = Pago::create([
            'matricula_id' => $data['matricula_id'] ?? null,
            'acudiente_id' => $data['acudiente_id'],
            'monto' => $data['monto'],
            'tipo' => 'factura',
            'estado' => 'pendiente',
            'descripcion' => $data['descripcion'] ?? 'Factura de matrícula',
        ]);

        return response()->json(['factura' => $factura], 201);
    }

    // Registrar un pago realizado por un acudiente
    public function registrarPagoAcudiente(Request $request)
    {
        $data = $request->validate([
            'pago_id' => 'nullable|integer|exists:pagos,id',
            'acudiente_id' => 'required|integer|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'metodo' => 'nullable|string',
            'descripcion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($data['pago_id'])) {
                $pago = Pago::find($data['pago_id']);
                if (!$pago) return response()->json(['error' => 'Pago no encontrado'], 404);
                $pago->update(['estado' => 'pagado', 'monto' => $data['monto'], 'metodo' => $data['metodo'] ?? $pago->metodo]);
            } else {
                $pago = Pago::create([
                    'acudiente_id' => $data['acudiente_id'],
                    'monto' => $data['monto'],
                    'tipo' => 'pago',
                    'estado' => 'pagado',
                    'metodo' => $data['metodo'] ?? null,
                    'descripcion' => $data['descripcion'] ?? 'Pago registrado por tesorero',
                ]);
            }

            DB::commit();
            return response()->json(['pago' => $pago], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al registrar pago', 'message' => $e->getMessage()], 500);
        }
    }

    // Gestionar devolución (marca pago como devuelto y crea entrada de devolución)
    public function gestionarDevolucion(Request $request)
    {
        $data = $request->validate([
            'pago_id' => 'required|integer|exists:pagos,id',
            'motivo' => 'nullable|string',
        ]);

        $pago = Pago::find($data['pago_id']);
        if (!$pago) return response()->json(['error' => 'Pago no encontrado'], 404);

        DB::beginTransaction();
        try {
            $pago->update(['estado' => 'devuelto']);

            $devolucion = Pago::create([
                'matricula_id' => $pago->matricula_id,
                'acudiente_id' => $pago->acudiente_id,
                'monto' => -1 * abs($pago->monto),
                'tipo' => 'devolucion',
                'estado' => 'pagado',
                'descripcion' => $data['motivo'] ?? 'Devolución de pago',
            ]);

            DB::commit();
            return response()->json(['devuelven' => $devolucion], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar devolución', 'message' => $e->getMessage()], 500);
        }
    }

    // Gestionar cartera: listar pagos pendientes agrupados por acudiente
    public function gestionarCartera()
    {
        $cartera = Pago::where('estado', 'pendiente')
            ->select('acudiente_id', DB::raw('SUM(monto) as total_pendiente'))
            ->groupBy('acudiente_id')
            ->with('acudiente')
            ->get();

        return response()->json(['cartera' => $cartera], 200);
    }

    // Entregar reportes simples (resumen por rango de fechas)
    public function entregarReportes(Request $request)
    {
        $data = $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
        ]);

        $desde = $data['desde'] ?? now()->subMonth()->toDateString();
        $hasta = $data['hasta'] ?? now()->toDateString();

        $pagos = Pago::whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->selectRaw("tipo, estado, SUM(monto) as total, COUNT(*) as count")
            ->groupBy('tipo', 'estado')
            ->get();

        return response()->json(['reporte' => $pagos, 'desde' => $desde, 'hasta' => $hasta], 200);
    }

    // Consultar estado de cuenta de un acudiente
    public function consultarEstadoCuenta($acudienteId)
    {
        $acudiente = User::find($acudienteId);
        if (!$acudiente) return response()->json(['error' => 'Acudiente no encontrado'], 404);

        $pagos = Pago::where('acudiente_id', $acudienteId)->orderByDesc('created_at')->get();
        $saldo = Pago::where('acudiente_id', $acudienteId)->sum('monto');

        return response()->json(['acudiente' => $acudiente, 'pagos' => $pagos, 'saldo' => (float)$saldo], 200);
    }

    // Registrar beca o descuento: crea registro con monto negativo
    public function registrarBecaDescuento(Request $request)
    {
        $data = $request->validate([
            'matricula_id' => 'nullable|integer|exists:matriculas_acudientes,id',
            'acudiente_id' => 'required|integer|exists:users,id',
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);

        $beca = Pago::create([
            'matricula_id' => $data['matricula_id'] ?? null,
            'acudiente_id' => $data['acudiente_id'],
            'monto' => -1 * abs($data['monto']),
            'tipo' => 'beca',
            'estado' => 'pagado',
            'descripcion' => $data['descripcion'] ?? 'Beca o descuento otorgado',
        ]);

        return response()->json(['beca' => $beca], 201);
    }

    // Generar reporte financiero resumido
    public function generarReporteFinanciero(Request $request)
    {
        $data = $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
        ]);

        $desde = $data['desde'] ?? now()->startOfYear()->toDateString();
        $hasta = $data['hasta'] ?? now()->toDateString();

        $totales = Pago::whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->selectRaw("SUM(CASE WHEN monto > 0 THEN monto ELSE 0 END) as ingresos, SUM(CASE WHEN monto < 0 THEN monto ELSE 0 END) as egresos")
            ->first();

        return response()->json(['desde' => $desde, 'hasta' => $hasta, 'totales' => $totales], 200);
    }

    // Consultar información del colegio (valores por defecto desde config/app)
    public function consultarInformacionColegio()
    {
        $info = [
            'nombre' => config('app.name', 'Colegio'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
        ];

        return response()->json(['colegio' => $info], 200);
    }

    // Vistas (métodos que muestran páginas para cada función)
    public function viewPazYSalvo()
    {
        return view('tesoreria.pazysalvo');
    }

    public function viewGenerarFactura()
    {
        return view('tesoreria.factura');
    }

    public function viewRegistrarPago()
    {
        return view('tesoreria.registrar_pago');
    }

    public function viewDevolucion()
    {
        return view('tesoreria.devolucion');
    }

    public function viewCartera()
    {
        return view('tesoreria.cartera');
    }

    public function viewReportes()
    {
        return view('tesoreria.reportes');
    }

    public function viewEstadoCuenta()
    {
        return view('tesoreria.estado_cuenta');
    }

    public function viewBeca()
    {
        return view('tesoreria.beca');
    }

    public function viewReporteFinanciero()
    {
        return view('tesoreria.reporte_financiero');
    }

    public function viewInfoColegio()
    {
        return view('tesoreria.info_colegio');
    }

    public function viewAprobarBecas()
    {
        return view('tesoreria.aprobar_becas');
    }

    // ===========================
    // Gestión de Solicitudes de Beca
    // ===========================

    /**
     * Ver lista de solicitudes de beca pendientes
     */
    public function viewSolicitudesBeca()
    {
        return view('tesoreria.solicitudes_beca');
    }

    /**
     * API: Obtener solicitudes de beca (filtradas por estado)
     */
    public function obtenerSolicitudesBeca(Request $request)
    {
        $data = $request->validate([
            'estado' => 'nullable|string|in:solicitado,en_revision,aprobado,rechazado',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = BecaSolicitud::with(['acudiente', 'estudiante'])
            ->orderByDesc('fecha_solicitud');

        if ($data['estado'] ?? false) {
            $query->where('estado', $data['estado']);
        }

        $solicitudes = $query->paginate($data['per_page'] ?? 15);

        return response()->json(['solicitudes' => $solicitudes], 200);
    }

    /**
     * Aprobar una solicitud de beca
     */
    public function aprobarSolicitud(Request $request, $solicitudId)
    {
        $data = $request->validate([
            'motivo' => 'nullable|string',
        ]);

        $solicitud = BecaSolicitud::findOrFail($solicitudId);

        DB::beginTransaction();
        try {
            // Actualizar estado a aprobado
            $solicitud->update([
                'estado' => 'aprobado',
                'fecha_resolucion' => now(),
            ]);

            // Si hay monto, crear pago negativo (beca/descuento)
            if ($solicitud->monto_estimado) {
                Pago::create([
                    'acudiente_id' => $solicitud->acudiente_id,
                    'monto' => -1 * abs($solicitud->monto_estimado),
                    'tipo' => 'beca',
                    'estado' => 'pagado',
                    'descripcion' => 'Beca aprobada: ' . $solicitud->tipo . ' - ' . ($data['motivo'] ?? ''),
                ]);
            }

            // Crear notificación para el acudiente
            Notificacion::create([
                'user_id' => $solicitud->acudiente_id,
                'titulo' => 'Solicitud de Beca Aprobada',
                'contenido' => 'Tu solicitud de ' . $solicitud->tipo . ' ha sido aprobada. Monto: $' . $solicitud->monto_estimado,
                'tipo' => 'beca',
                'leida' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de beca aprobada correctamente',
                'solicitud' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al aprobar solicitud', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Rechazar una solicitud de beca
     */
    public function rechazarSolicitud(Request $request, $solicitudId)
    {
        $data = $request->validate([
            'motivo' => 'required|string',
        ]);

        $solicitud = BecaSolicitud::findOrFail($solicitudId);

        DB::beginTransaction();
        try {
            // Actualizar estado a rechazado
            $solicitud->update([
                'estado' => 'rechazado',
                'fecha_resolucion' => now(),
            ]);

            // Crear notificación para el acudiente
            Notificacion::create([
                'user_id' => $solicitud->acudiente_id,
                'titulo' => 'Solicitud de Beca Rechazada',
                'contenido' => 'Tu solicitud de ' . $solicitud->tipo . ' ha sido rechazada. Motivo: ' . $data['motivo'],
                'tipo' => 'beca',
                'leida' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de beca rechazada correctamente',
                'solicitud' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al rechazar solicitud', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cambiar solicitud a "en revisión"
     */
    public function marcarEnRevision(Request $request, $solicitudId)
    {
        $solicitud = BecaSolicitud::findOrFail($solicitudId);

        $solicitud->update(['estado' => 'en_revision']);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud marcada en revisión',
            'solicitud' => $solicitud
        ], 200);
    }
}
