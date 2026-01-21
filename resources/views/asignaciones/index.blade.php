@extends('layouts.app')
@section('title','Asignaciones')

@section('content')
@php
  $isAdmin = auth('web')->check() && auth('web')->user()->rol === 'Administrador';
@endphp

<div class="container py-4" >
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Asignaciones</h1>

    @if($isAdmin)
      <a class="btn btn-primary" href="{{ route('asignaciones.create') }}">Nueva asignación</a>
    @endif
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <select name="dia" class="form-select">
        <option value="">Todos los días</option>
        @foreach($dias as $d)
          <option value="{{ $d }}" @selected(request('dia') === $d)>
            {{ $d }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <button class="btn btn-primary w-100">Filtrar</button>
    </div>

    @if(request('dia'))
      <div class="col-md-2">
        <a href="{{ route('asignaciones.index') }}" class="btn btn-primary w-100">Limpiar</a>
      </div>
    @endif
  </form>


  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Empleado</th>
          <th>Servicio</th>
          <th>Día</th>
          <th>Hora</th>
          @if($isAdmin)
            <th class="text-end">Acciones</th>
          @endif
        </tr>
      </thead>

      <tbody>
        @forelse($asignaciones as $a)
          <tr>
            <td>
              {{ optional($a->empleado)->nombre }} {{ optional($a->empleado)->apellidos }}
            </td>
            <td>{{ optional($a->servicio)->nombre ?? '—' }}</td>
            <td>{{ $a->dia }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($a->hora)->format('H:i') }}</td>

            @if($isAdmin)
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('asignaciones.edit',$a) }}">Editar</a>

                <form method="POST"
                      action="{{ route('asignaciones.destroy',$a) }}"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar asignación?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            @endif
          </tr>
        @empty
          <tr>
            <td colspan="{{ $isAdmin ? 5 : 4 }}">Sin asignaciones.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $asignaciones->links() }}
</div>
@endsection
