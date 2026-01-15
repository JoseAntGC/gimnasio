@extends('layouts.app')
@section('title','Mis rutinas')

@section('content')
<div class="container py-4">
  <div class="card p-3 fondoOpaco">
    <h1 class="h5 mb-3">Rutinas — {{ $categoria }}</h1>

    @if(empty($archivos))
      <p class="mb-0">No hay rutinas disponibles para tu categoría todavía.</p>
    @else
      <div class="list-group">
        @foreach($archivos as $f)
          <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center fondoOpaco" href="{{ $f['url'] }}" target="_blank">
            <span>{{ $f['nombre'] }}</span>
            <span class="badge bg-success">Ver</span>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
