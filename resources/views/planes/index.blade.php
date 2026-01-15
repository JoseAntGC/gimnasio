@extends('layouts.app')
@section('title','Planes')

@section('content')
<div class="container py-4" style="max-width: 900px; margin: 0 auto;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Planes</h1>
    <a class="btn btn-primary" href="{{ route('planes.create') }}">Nuevo plan</a>
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
          <th>#</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Activo</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($planes as $p)
          <tr>
            <td>{{ $p->id_plan }}</td>
            <td>{{ $p->nombre }}</td>
            <td>{{ number_format($p->precio,2,',','.') }} €</td>
            <td>
              @if($p->activo)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('planes.edit',$p) }}">Editar</a>

              <form method="POST" action="{{ route('planes.destroy',$p->id_plan) }}" class="d-inline"
                    onsubmit="return confirm('¿Eliminar plan? Si está usado por suscripciones, puede fallar por clave foránea.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Sin planes todavía.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $planes->links() }}
</div>
@endsection
