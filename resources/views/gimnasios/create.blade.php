@extends('layouts.app')
@section('title','Nuevo gimnasio')

@section('content')
<div class="container py-4" style="max-width:720px;margin:0 auto;">
  <h1 class="h4 mb-3">Nuevo gimnasio</h1>
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('gimnasios.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input name="nombre" class="form-control" value="{{ old('nombre') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">CIF (no editable después)</label>
      <input name="cif" class="form-control" value="{{ old('cif') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Dirección</label>
      <input name="direccion" class="form-control" value="{{ old('direccion') }}">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" value="{{ old('telefono') }}">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Activo</label>
      <select name="activo" class="form-select">
        <option value="1" @selected(old('activo','1')==='1')>Sí</option>
        <option value="0" @selected(old('activo')==='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('gimnasios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
@endsection

