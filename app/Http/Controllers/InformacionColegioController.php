<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformacionColegioController extends Controller
{
    public function dashboard()
    {
        return view('informacion.gestion');
    }
}

