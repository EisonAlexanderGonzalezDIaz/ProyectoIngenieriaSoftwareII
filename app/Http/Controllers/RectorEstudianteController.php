<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RectorEstudianteController extends Controller
{
    public function boletines()
    {
        return view('rector.boletines_placeholder');
    }

    public function notas()
    {
        return view('rector.notas_placeholder');
    }

    public function materias()
    {
        return view('rector.materias_placeholder');
    }
}
