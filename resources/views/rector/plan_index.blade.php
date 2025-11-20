@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Planes Anuales</h1>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Año</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planes as $p)
                <tr>
                    <td>{{ $p->anio }}</td>
                    <td>{{ $p->titulo }}</td>
                    <td>{{ $p->estado }}</td>
                    <td>
                        @if($p->estado !== 'aprobado')
                            <a href="{{ url('/rector/plan/'.$p->id.'/aprobar') }}" class="btn btn-sm btn-success">Aprobar</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
