@extends('layouts.app')
@section('title','Editar empleado')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Editar empleado #{{ $empleado->id_empleado }}</h1>
  @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('empleados.update',$empleado) }}">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Gimnasio</label>
      <select name="id_gimnasio" class="form-select" required>
        @foreach($gimnasios as $g)
          <option value="{{ $g->id_gimnasio }}" @selected(old('id_gimnasio',$empleado->id_gimnasio)==$g->id_gimnasio)>{{ $g->nombre }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="{{ old('nombre',$empleado->nombre) }}" required></div>
      <div class="col-md-6 mb-3"><label class="form-label">Apellidos</label><input name="apellidos" class="form-control" value="{{ old('apellidos',$empleado->apellidos) }}" required></div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label">DNI</label><input name="DNI" class="form-control" value="{{ old('DNI',$empleado->DNI) }}" required></div>
      <div class="col-md-6 mb-3"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" value="{{ old('telefono',$empleado->telefono) }}" required></div>
    </div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$empleado->email) }}" required></div>
    <div class="mb-3">
      <label class="form-label">Contraseña (dejar en blanco para no cambiarla)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-select" required>
          @foreach(['Administrador','Monitor','Limpieza'] as $rol)
            <option value="{{ $rol }}" @selected(old('rol',$empleado->rol)===$rol)>{{ $rol }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Activo</label>
        <select name="activo" class="form-select">
          <option value="1" @selected(old('activo',$empleado->activo)=='1')>Sí</option>
          <option value="0" @selected(old('activo',$empleado->activo)=='0')>No</option>
        </select>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
@endsection
