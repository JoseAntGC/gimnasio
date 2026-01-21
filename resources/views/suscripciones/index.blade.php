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

  <form method="GET" class="row g-2 mb-3">
  <div class="col-md-6">
    <input type="search" name="q" class="form-control" placeholder="Buscar por nombre, apellidos, email o DNI…" value="{{ request('q') }}">
  </div>

  <div class="col-md-3">
    <select name="estado" class="form-select">
      <option value="">Todas</option>
      <option value="activa" @selected(request('estado')==='activa')>Activas</option>
      <option value="inactiva" @selected(request('estado')==='inactiva')>Inactivas</option>
    </select>
  </div>

  <div class="col-md-3 d-flex gap-2">
    <button class="btn btn-primary w-100">Buscar</button>
    <a class="btn btn-primary" href="{{ route('suscripciones.index') }}">Limpiar</a>
  </div>
</form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Plan</th>
          <th>Precio</th>
          <th>Alta</th>
          <th>Baja</th>
          <th>Activa</th>
          <th>Último pago</th>
          <th>Estado pago</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($suscripciones as $s)
          <tr>
            <td>{{ optional($s->usuario)->apellidos }}, {{ optional($s->usuario)->nombre }} </td>

            {{-- Si tienes relación plan() en el modelo: --}}
            <td>{{ optional($s->plan)->nombre ?? '—' }}</td>

            <td>{{ number_format($s->precio,2,',','.') }} €</td>
            <td>{{ $s->fecha_alta->format('Y-m-d') }}</td>
            <td>{{ $s->fecha_baja ? $s->fecha_baja->format('Y-m-d') : '—' }}</td>
            <td>
              @if($s->activa)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>            
            @php
              $p = $s->ultimoPago;

              $mesActual = now()->startOfMonth();
              $mesBaja = $s->fecha_baja ? \Carbon\Carbon::parse($s->fecha_baja)->startOfMonth() : null;

              $alDia = false;

              if ($p && $p->periodo) {
                // Activa: debe cubrir el mes actual
                if ($s->activa && $p->periodo->greaterThanOrEqualTo($mesActual)) {
                    $alDia = true;
                }

                // Inactiva: último periodo coincide con el mes de baja
                if (!$s->activa && $mesBaja && $p->periodo->equalTo($mesBaja)) {
                    $alDia = true;
                }
              }
            @endphp
            <td>
              {{ $p && $p->periodo ? $p->periodo->format('Y-m') : '—' }}
            </td>
            <td>
              @if($alDia)
                <span class="badge bg-success">Al día</span>
              @else
                <span class="badge bg-warning text-dark">Pendiente</span>
              @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('suscripciones.edit',$s) }}">Editar</a>
              <a href="{{ route('pagos.index', $s) }}" class="btn btn-sm btn-outline-success">Pagos</a>              
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

