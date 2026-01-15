@extends('layouts.app')
@section('title','Nuevo usuario')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Nuevo usuario</h1>
  @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('usuarios.store') }}">
    @csrf
    @if(auth('web')->user()->rol === 'Administrador')
      {{-- Select gimnasio --}}
      <div class="mb-3">
        <label class="form-label">Gimnasio</label>
        <select name="id_gimnasio" class="form-select" required>
           <option value="">— Selecciona —</option>
          @foreach($gimnasios as $g)
            <option value="{{ $g->id_gimnasio }}" @selected(old('id_gimnasio') === $g->id_gimnasio)>{{ $g->nombre }}</option>
          @endforeach
        </select>
      </div>
    @endif
    @if(auth('web')->user()->rol === 'Monitor')
      <div class="alert alert-info">
        Este usuario se creará en tu gimnasio. 
      </div>
    @endif
    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="{{ old('nombre') }}" required></div>
      <div class="col-md-6 mb-3"><label class="form-label">Apellidos</label><input name="apellidos" class="form-control" value="{{ old('apellidos') }}" required></div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label">DNI</label><input name="DNI" pattern="[0-9]{8}[A-Za-z]" maxlength="9" class="form-control" value="{{ old('DNI') }}" required></div>
      <div class="col-md-6 mb-3"><label class="form-label">Teléfono</label><input name="telefono" inputmode="numeric" pattern="[6789][0-9]{8}" maxlength="9" class="form-control" value="{{ old('telefono') }}" required></div>
    </div>

    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
    <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div>
    <div class="mb-3">
      <label class="form-label">Categoría</label>
      <select name="categoria" class="form-select" required>
        @foreach(['Principiante','Intermedio','Experto'] as $c)
          <option value="{{ $c }}" @selected(old('categoria','Principiante')===$c)>{{ $c }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Activo</label>
      <select name="activo" class="form-select">
        <option value="1" @selected(old('activo','1')==='1')>Sí</option>
        <option value="0" @selected(old('activo')==='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
@endsection
