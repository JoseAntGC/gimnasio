@extends('layouts.app')
@section('title','Editar plan')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Editar plan #{{ $plan->id_plan }}</h1>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('planes.update',$plan) }}">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input name="nombre" class="form-control" value="{{ old('nombre',$plan->nombre) }}" required maxlength="120">
    </div>

    <div class="mb-3">
      <label class="form-label">Precio (€)</label>
      <input name="precio" type="number" step="0.01" min="0" class="form-control"
             value="{{ old('precio',$plan->precio) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Activo</label>
      <select name="activo" class="form-select">
        <option value="1" @selected(old('activo',$plan->activo)=='1')>Sí</option>
        <option value="0" @selected(old('activo',$plan->activo)=='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('planes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
@endsection
