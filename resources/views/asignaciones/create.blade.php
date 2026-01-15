@extends('layouts.app')
@section('title','Nueva asignación')

@section('content')
<div class="container py-4" style="max-width: 720px;">
  <h1 class="h4 mb-3">Nueva asignación</h1>
  @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('asignaciones.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Empleado</label>
      <select name="id_empleado" class="form-select" required>
        <option value="">— Selecciona —</option>
        @foreach($empleados as $e)
          <option value="{{ $e->id_empleado }}" @selected(old('id_empleado')==$e->id_empleado)>{{ $e->nombre }} {{ $e->apellidos }} (G{{ $e->id_gimnasio }})</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Servicio</label>
      <select name="id_servicio" class="form-select" required>
        <option value="">— Selecciona —</option>
        @foreach($servicios as $s)
          <option value="{{ $s->id_servicio }}" @selected(old('id_servicio')==$s->id_servicio)>{{ $s->nombre }} (G{{ $s->id_gimnasio }})</option>
        @endforeach
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Día</label>
        <select name="dia" class="form-select" required>
          @foreach($dias as $d)
            <option value="{{ $d }}" @selected(old('dia')==$d)>{{ $d }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Hora</label>
        <input type="time" name="hora" class="form-control" value="{{ old('hora','18:00') }}" required>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
@endsection
