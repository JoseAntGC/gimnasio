@extends('layouts.app')
@section('title','Gimnasios')

@section('content')
<div class="container py-4" style="max-width: 1000px; margin:0 auto;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Gimnasios</h1>
    <a class="btn btn-primary" href="{{ route('gimnasios.create') }}">Nuevo gimnasio</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th><th>Nombre</th><th>CIF</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($gimnasios as $g)
          <tr>
            <td>{{ $g->id_gimnasio }}</td>
            <td>{{ $g->nombre }}</td>
            <td>{{ $g->cif }}</td>
            <td>
              @if($g->activo) <span class="badge bg-success">Sí</span>
              @else <span class="badge bg-secondary">No</span> @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('gimnasios.edit',$g) }}">Editar</a>              
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Sin gimnasios.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $gimnasios->links() }}
</div>
@endsection
