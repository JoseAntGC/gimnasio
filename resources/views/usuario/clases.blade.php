@extends('layouts.app')
@section('title','Clases')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 m-0">Clases</h1>
      <div class="text-white small">Horario del gimnasio</div>
    </div>
    <a class="btn btn-primary" href="{{ route('u.panel') }}">← Volver</a>
  </div>

  <form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
    <select name="dia" class="form-select">
        <option value="">Todos los días</option>
        @foreach(['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'] as $d)
            <option value="{{ $d }}" @selected(request('dia') === $d)>
            {{ $d }}
            </option>
        @endforeach
        </select>
    </div>
     <div class="col-md-4">
        <select name="servicio" class="form-select">
        <option value="">Todos los servicios</option>
        @foreach($servicios as $srv)
            <option value="{{ $srv->id_servicio }}"
            @selected(request('servicio') == $srv->id_servicio)>
            {{ $srv->nombre }}
            </option>
        @endforeach
        </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary w-75">Filtrar</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('u.clases') }}" class="btn btn-primary w-75">Quitar filtros</a>
    </div>
  </form>

  <div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Servicio</th>
                    <th>Monitor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $a)
                    <tr>
                        <td>{{ $a->dia }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->hora)->format('H:i') }}</td>
                        <td>{{ $a->servicio->nombre }}</td>
                        <td>{{ $a->empleado->nombre }} {{ $a->empleado->apellidos }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                        No hay clases con estos filtros
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

      <div class="mt-3">
        {{ $asignaciones->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
