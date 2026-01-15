@extends('layouts.app')
@section('title','Editar servicio')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Editar servicio</h1>

  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('servicios.update',$servicio) }}">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Gimnasio</label>
      <select name="id_gimnasio" class="form-select" required>
        @foreach($gimnasios as $g)
          <option value="{{ $g->id_gimnasio }}" @selected(old('id_gimnasio',$servicio->id_gimnasio)==$g->id_gimnasio)>
            {{ $g->nombre }} 
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input name="nombre" class="form-control" value="{{ old('nombre',$servicio->nombre) }}" required maxlength="120">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <input name="descripcion" class="form-control" value="{{ old('descripcion',$servicio->descripcion) }}" maxlength="255">
    </div>
    
    <div class="mb-3">
      <label class="form-label">Activo</label>
      <select name="activo" class="form-select">
        <option value="1" @selected(old('activo',$servicio->activo)=='1')>Sí</option>
        <option value="0" @selected(old('activo',$servicio->activo)=='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Actualizar</button>
    </div>
  </form>
</div>
@endsection
