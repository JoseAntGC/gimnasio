@extends('layouts.app')
@section('title','Suscripciones')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Suscripciones</h1>
    <a class="btn btn-primary" href="{{ route('suscripciones.create') }}">Nueva suscripción</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Usuario</th>
          <th>Plan</th>
          <th>Precio</th>
          <th>Alta</th>
          <th>Baja</th>
          <th>Activa</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($suscripciones as $s)
          <tr>
            <td>{{ $s->id_suscripcion }}</td>
            <td>{{ optional($s->usuario)->nombre }} {{ optional($s->usuario)->apellidos }}</td>

            {{-- Si tienes relación plan() en el modelo: --}}
            <td>{{ optional($s->plan)->nombre ?? '—' }}</td>

            <td>{{ number_format($s->precio,2,',','.') }} €</td>
            <td>{{ $s->fecha_alta }}</td>
            <td>{{ $s->fecha_baja ?? '—' }}</td>
            <td>
              @if($s->activa)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>

            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('suscripciones.edit',$s) }}">Editar</a>

              {{-- Solo Admin ve eliminar --}}
              @if(auth('web')->check() && auth('web')->user()->rol === 'Administrador')
                <form method="POST" action="{{ route('suscripciones.destroy',$s) }}" class="d-inline"
                      onsubmit="return confirm('¿Eliminar suscripción? Esta acción no se puede deshacer.')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="8">Sin suscripciones.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $suscripciones->links() }}
</div>
@endsection

