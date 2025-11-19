<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\MatriculaAcudiente;
use App\Models\User;
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

    // ========================================
    // MÉTODOS DE VISTA (retornan Blade views)
    // ========================================

    public function dashboard()
    {
        return view('tesoreria.dashboard');
    }

    public function viewPazYSalvo()
    {
        return view('tesoreria.paz_y_salvo');
    }

    public function viewFactura()
    {
        return view('tesoreria.factura');
    }

    public function viewPagos()
    {
        return view('tesoreria.pagos');
    }

    public function viewDevoluciones()
    {
        return view('tesoreria.devoluciones');
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

    public function viewBecas()
    {
        return view('tesoreria.becas');
    }

    public function viewReporte()
    {
        return view('tesoreria.reporte_financiero');
    }

}
