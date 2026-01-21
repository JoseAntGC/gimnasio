@extends('layouts.app')
@section('title','Mi panel')

@section('content')
<div class="container py-4">
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card p-3 fondoOpaco">
        <h2 class="h5 mb-2">Hola, {{ $usuario->nombre }}</h2>
        <p class="mb-1">Email: <strong>{{ $usuario->email }}</strong></p>
        <p class="mb-1">Teléfono: <strong>{{ $usuario->telefono }}</strong></p>
        <p class="mb-0">Categoría: <strong>{{ $usuario->categoria ?? 'Principiante' }}</strong></p>
      </div>
    </div>

    <div class="col-md-6 d-flex align-items-stretch">
      <div class="card p-3 w-100 fondoOpaco">
        <h2 class="h6 mb-3">Accesos rápidos</h2>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="{{ route('u.rutinas') }}">Ver rutinas</a>
          <a class="btn btn-primary" href="{{ route('u.perfil') }}">Mi perfil</a>
          <a class="btn btn-primary" href="{{ route('u.clases') }}">Clases</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
