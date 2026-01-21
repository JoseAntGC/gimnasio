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

  <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 align-items-center mb-3">
    <div class="col-12 col-md-6">
      <input type="text" name="q" value="{{ $q ?? request('q') }}" class="form-control" placeholder="Buscar por nombre, apellidos, email, DNI o teléfono">
    </div>
    <div class="col-md-3">
      <select name="estado" class="form-select">
        <option value="">Todas</option>
        <option value="activa" @selected(request('estado')==='activa')>Activas</option>
        <option value="inactiva" @selected(request('estado')==='inactiva')>Inactivas</option>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button class="btn btn-primary w-100">Filtrar</button>
      <a href="{{ route('usuarios.index') }}" class="btn btn-primary w-100">
        Limpiar
      </a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Nombre</th><th>Email</th><th>Teléfono</th><th>Categoría</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $u)
          <tr>
            <td> {{ $u->apellidos }}, {{ $u->nombre }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->telefono }}</td>
            <td>{{ $u->categoria ?? '—' }}</td>
            <td>
              @if($u->activo) <span class="badge bg-success">Sí</span>
              @else <span class="badge bg-secondary">No</span> @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('usuarios.edit',$u) }}">Editar</a>              
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
