@extends('layouts.app')
@section('title','Mi perfil')

@section('content')
<div class="container py-4">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="card p-3 mb-3 fondoOpaco">
    <h2 class="h5 mb-3">Mis datos</h2>
    <div class="row">
      <div class="col-md-6 mb-2"><strong>Nombre:</strong> {{ $usuario->nombre }} {{ $usuario->apellidos }}</div>
      <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $usuario->email }}</div>
      <div class="col-md-6 mb-2"><strong>Teléfono:</strong> {{ $usuario->telefono }}</div>
      <div class="col-md-6 mb-2"><strong>Categoría:</strong> {{ $usuario->categoria ?? 'Principiante' }}</div>
    </div>
    <small class="text-muted">Si necesitas cambiar tus datos personales, contacta con recepción.</small>
  </div>

  <div class="card p-3 fondoOpaco">
    <h2 class="h6 mb-3">Cambiar contraseña</h2>
    <form method="POST" action="{{ route('u.perfil.password') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Contraseña actual</label>
        <input type="password" name="password_actual" class="form-control" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nueva contraseña</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Repetir nueva contraseña</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
      </div>
      <button class="btn btn-primary">Guardar nueva contraseña</button>
    </form>
  </div>
</div>
@endsection
