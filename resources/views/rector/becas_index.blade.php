@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Solicitudes de Beca</h1>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Acudiente</th>
                <th>Estudiante</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($becas as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ optional($b->acudiente)->name }}</td>
                    <td>{{ optional($b->estudiante)->name }}</td>
                    <td>{{ $b->estado }}</td>
                    <td>
                        @if($b->estado !== 'aprobado')
                            <a href="{{ url('/rector/becas/'.$b->id.'/aprobar') }}" class="btn btn-sm btn-success">Aprobar</a>
                        @endif
                        @if($b->estado !== 'rechazado')
                            <a href="{{ url('/rector/becas/'.$b->id.'/rechazar') }}" class="btn btn-sm btn-danger">Rechazar</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
