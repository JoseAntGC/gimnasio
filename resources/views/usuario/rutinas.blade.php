@extends('layouts.app')
@section('title','Mis rutinas')

@section('content')
<div class="container py-4">
  <div class="card p-3 fondoOpaco">
    <h1 class="h5 mb-3">
      Rutinas — {{ $categoria }}
    </h1>

    @if(empty($archivos))
      <p class="mb-0 text-muted">
        No hay rutinas disponibles para tu categoría todavía.
      </p>
    @else
      <div class="list-group list-group-flush">
        @foreach($archivos as $f)
          <a
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center fondoOpaco"
            href="{{ asset('rutinas/' . $categoria . '/' . $f['archivo']) }}"
            target="_blank"
            rel="noopener"
          >
            <span>{{ $f['nombre'] }}</span>
            <span class="badge bg-success">Ver</span>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
