<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudBeca;
use App\Models\InformacionColegio;
use App\Models\PlanAnual;
use Illuminate\Support\Facades\Auth;

class RectorController extends Controller
{
    // Mostrar solicitudes de beca para aprobar/rechazar
    public function becasIndex()
    {
        $becas = SolicitudBeca::orderBy('created_at', 'desc')->get();
        return view('rector.becas_index', compact('becas'));
    }

    public function aprobarBeca($id)
    {
        $beca = SolicitudBeca::findOrFail($id);
        $beca->estado = 'aprobado';
        $beca->save();
        return redirect()->back()->with('ok', 'Beca aprobada.');
    }

    public function rechazarBeca($id)
    {
        $beca = SolicitudBeca::findOrFail($id);
        $beca->estado = 'rechazado';
        $beca->save();
        return redirect()->back()->with('ok', 'Beca rechazada.');
    }

    // Información del colegio
    public function infoIndex()
    {
        $infos = InformacionColegio::orderBy('created_at', 'desc')->get();
        return view('rector.info_index', compact('infos'));
    }

    public function createInfo()
    {
        return view('rector.info_create');
    }

    public function storeInfo(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $info = InformacionColegio::create([
            'titulo' => $data['titulo'],
            'contenido' => $data['contenido'],
            'autor_id' => Auth::id(),
        ]);

        return redirect()->route('rector.info.index')->with('ok', 'Información creada.');
    }

    public function publicarInfo($id)
    {
        $info = InformacionColegio::findOrFail($id);
        $info->publicado = true;
        $info->published_at = now();
        $info->save();
        return redirect()->back()->with('ok', 'Información publicada.');
    }

    // Plan anual
    public function planIndex()
    {
        $planes = PlanAnual::orderBy('anio', 'desc')->get();
        return view('rector.plan_index', compact('planes'));
    }

    public function aprobarPlan($id)
    {
        $plan = PlanAnual::findOrFail($id);
        $plan->estado = 'aprobado';
        $plan->save();
        return redirect()->back()->with('ok', 'Plan aprobado.');
    }

    // Matriculas (vista básica)
    public function matriculasIndex()
    {
        return view('rector.matriculas_index');
    }

    // Gestión de docentes / administrativos (vista básica)
    public function docentesIndex()
    {
        return view('rector.docentes_index');
    }
}
