@extends('layouts.app')
@section('title','Panel Empleado')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <div class="card shadow-sm fondoOpaco">
    <div class="card-body">
      <h1 class="h5 mb-2">Hola, {{ $empleado->nombre }} ({{ $empleado->rol }})</h1>
      <p class="mb-3">Acceso a tareas operativas.</p>

      <div class="d-flex flex-wrap gap-2">
        @if($empleado->rol === 'Monitor')
           <a class="btn btn-primary" href="{{ route('usuarios.index') }}">Gestionar usuarios</a>
          <a class="btn btn-primary" href="{{ route('suscripciones.index') }}">Gestionar suscripciones</a>
          <a class="btn btn-primary" href="{{ route('asignaciones.index') }}">Ver asignaciones</a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
