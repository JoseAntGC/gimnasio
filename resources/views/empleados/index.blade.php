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
  
  <form class="row g-2 mb-3" method="GET" action="{{ route('empleados.index') }}">
  <div class="col-md-4">
    <input type="text" name="q" class="form-control" placeholder="Buscar por nombre, apellidos, email, DNI..." value="{{ request('q') }}">
  </div>

  @if(auth('web')->user()->rol === 'Administrador')
    <div class="col-md-4">
      <select name="id_gimnasio" class="form-select">
        <option value="">— Todos los gimnasios —</option>
        @foreach($gimnasios as $g)
          <option value="{{ $g->id_gimnasio }}" @selected((string)request('id_gimnasio') === (string)$g->id_gimnasio)>
            {{ $g->nombre }}
          </option>
        @endforeach
      </select>
    </div>
  @endif

  <div class="col-md-2">
    <button class="btn btn-primary w-100">Filtrar</button>
  </div>

  <div class="col-md-2">
    <a class="btn btn-primary w-100" href="{{ route('empleados.index') }}">Limpiar</a>
  </div>
</form>


  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Gimnasio</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($empleados as $e)
          <tr>
            <td>{{ optional($e->gimnasio)->nombre ?? '—' }}</td>
            <td>{{ $e->apellidos }}, {{ $e->nombre }}</td>
            <td>{{ $e->email }}</td>
            <td>{{ $e->rol }}</td>
            <td>
              @if($e->activo) <span class="badge bg-success">Sí</span>
              @else <span class="badge bg-secondary">No</span> @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('empleados.edit',$e) }}">Editar</a>
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
