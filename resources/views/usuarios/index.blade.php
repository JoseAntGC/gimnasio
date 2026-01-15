@extends('layouts.app')
@section('title','Usuarios')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Usuarios</h1>
    <a class="btn btn-primary" href="{{ route('usuarios.create') }}">Nuevo usuario</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Categoría</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $u)
          <tr>
            <td>{{ $u->id_usuario }}</td>
            <td>{{ $u->nombre }} {{ $u->apellidos }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->telefono }}</td>
            <td>{{ $u->categoria ?? '—' }}</td>
            <td>
              @if($u->activo) <span class="badge bg-success">Sí</span>
              @else <span class="badge bg-secondary">No</span> @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('usuarios.edit',$u) }}">Editar</a>
              @if(auth('web')->check() && auth('web')->user()->rol === 'Administrador')
                <form class="d-inline" method="POST" action="{{ route('usuarios.destroy',$u) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7">Sin usuarios.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $usuarios->links() }}
</div>
@endsection
