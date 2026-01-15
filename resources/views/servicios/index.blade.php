@extends('layouts.app')

@section('title','Servicios')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Servicios</h1>
    <a class="btn btn-primary" href="{{ route('servicios.create') }}">Nuevo servicio</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  
  @if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Gimnasio</th>
          <th>Nombre</th>
          <th>Activo</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($servicios as $s)
          <tr>
            <td>{{ optional($s->gimnasio)->nombre ?? '—' }}</td>
            <td>{{ $s->nombre }}</td>
            <td>
              @if($s->activo)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('servicios.edit',$s) }}">Editar</a>
              @if(auth('web')->check() && auth('web')->user()->rol === 'Administrador')
                <form method="POST" action="{{ route('servicios.destroy',$s) }}" class="d-inline"
                      onsubmit="return confirm('¿Eliminar servicio?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>                
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Sin servicios todavía.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $servicios->links() }}
</div>
@endsection
