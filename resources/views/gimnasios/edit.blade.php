@extends('layouts.app')
@section('title','Editar gimnasio')

@section('content')
<div class="container py-4" style="max-width:720px;margin:0 auto;">
  <h1 class="h4 mb-3">Editar gimnasio #{{ $gimnasio->nombre}}</h1>
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('gimnasios.update',$gimnasio) }}">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input name="nombre" class="form-control" value="{{ old('nombre',$gimnasio->nombre) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">CIF (bloqueado)</label>
      <input class="form-control" value="{{ $gimnasio->cif }}" disabled>
    </div>

    <div class="mb-3">
      <label class="form-label">Dirección</label>
      <input name="direccion" class="form-control" value="{{ old('direccion',$gimnasio->direccion) }}">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" value="{{ old('telefono',$gimnasio->telefono) }}">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email',$gimnasio->email) }}">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Activo</label>
      <select name="activo" class="form-select">
        <option value="1" @selected(old('activo',$gimnasio->activo)=='1')>Sí</option>
        <option value="0" @selected(old('activo',$gimnasio->activo)=='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('gimnasios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
@endsection
