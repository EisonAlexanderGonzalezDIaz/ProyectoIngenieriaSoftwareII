@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Información del Colegio</h1>

    <a href="{{ route('rector.info.create') }}" class="btn btn-primary mb-3">Crear noticia/info</a>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <div class="list-group">
        @foreach($infos as $info)
            <div class="list-group-item">
                <h5>{{ $info->titulo }}</h5>
                <p>{{ Str::limit($info->contenido, 200) }}</p>
                <div>
                    <small>Autor: {{ optional($info->autor)->name }}</small>
                    @if($info->publicado)
                        <span class="badge bg-success">Publicado</span>
                    @else
                        <a href="{{ url('/rector/info/'.$info->id.'/publicar') }}" class="btn btn-sm btn-secondary">Publicar</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
