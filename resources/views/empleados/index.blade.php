@extends('layouts.app')
@section('title','Empleados')

@section('content') 
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Empleados</h1>
    <a class="btn btn-primary" href="{{ route('empleados.create') }}">Nuevo empleado</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th><th>Gimnasio</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($empleados as $e)
          <tr>
            <td>{{ $e->id_empleado }}</td>
            <td>{{ optional($e->gimnasio)->nombre ?? '—' }}</td>
            <td>{{ $e->nombre }} {{ $e->apellidos }}</td>
            <td>{{ $e->email }}</td>
            <td>{{ $e->rol }}</td>
            <td>
              @if($e->activo) <span class="badge bg-success">Sí</span>
              @else <span class="badge bg-secondary">No</span> @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('empleados.edit',$e) }}">Editar</a>
              <form class="d-inline" method="POST" action="{{ route('empleados.destroy',$e) }}" onsubmit="return confirm('¿Eliminar empleado?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7">Sin empleados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $empleados->links() }}
</div>
@endsection
