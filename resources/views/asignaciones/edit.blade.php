@extends('layouts.app')
@section('title','Editar asignación')

@section('content')
<div class="container py-4" style="max-width: 720px;">
  <h1 class="h4 mb-3">Editar asignación #{{ $asignacion->id_asignacion }}</h1>
  @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('asignaciones.update',$asignacion) }}">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Empleado</label>
      <select name="id_empleado" class="form-select" required>
        @foreach($empleados as $e)
          <option value="{{ $e->id_empleado }}" @selected(old('id_empleado',$asignacion->id_empleado)==$e->id_empleado)>
            {{ $e->nombre }} {{ $e->apellidos }} (G{{ $e->id_gimnasio }})
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Servicio</label>
      <select name="id_servicio" class="form-select" required>
        @foreach($servicios as $s)
          <option value="{{ $s->id_servicio }}" @selected(old('id_servicio',$asignacion->id_servicio)==$s->id_servicio)>
            {{ $s->nombre }} (G{{ $s->id_gimnasio }})
          </option>
        @endforeach
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Día</label>
        <select name="dia" class="form-select" required>
          @foreach($dias as $d)
            <option value="{{ $d }}" @selected(old('dia',$asignacion->dia)==$d)>{{ $d }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Hora</label>
        <input type="time" name="hora" class="form-control" value="{{ old('hora', \Illuminate\Support\Str::of($asignacion->hora)->substr(0,5)) }}" required>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
@endsection
