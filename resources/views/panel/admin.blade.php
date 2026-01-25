@extends('layouts.app')
@section('title','Panel Administrador')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm fondoOpaco">
    <div class="card-body">
      <h1 class="h5 mb-2">Hola, {{ $empleado->nombre }} (Administrador)</h1>
      <p class="mb-3">Desde aquí podrás gestionar el gimnasio.</p>

      <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="{{ route('gimnasios.index') }}">Gestionar gimnasios</a>
        <a class="btn btn-primary" href="{{ route('servicios.index') }}">Gestionar servicios</a>
        <a class="btn btn-primary" href="{{ route('suscripciones.index') }}">Gestionar suscripciones</a>
        <a class="btn btn-primary" href="{{ route('empleados.index') }}">Gestionar empleados</a>
        <a class="btn btn-primary" href="{{ route('usuarios.index') }}">Gestionar usuarios</a>
        <a class="btn btn-primary" href="{{ route('asignaciones.index') }}">Gestionar Asignaciones</a>
        <a class="btn btn-primary" href="{{ route('planes.index') }}">Gestionar planes</a>
      </div>
    </div>
  </div>
</div>
@endsection
