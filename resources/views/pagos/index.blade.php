@extends('layouts.app')

@section('title','Pagos')

@section('content')
<div class="container py-4" style="max-width: 1000px; margin: 0 auto;">

  {{-- CABECERA --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 m-0">Pagos</h1>
      <div class="text-white small">
        Suscripción #{{ $suscripcion->id_suscripcion }}
        —
        Usuario: {{ optional($suscripcion->usuario)->nombre }}
        {{ optional($suscripcion->usuario)->apellidos }}
      </div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary"
         href="{{ route('suscripciones.index') }}">
        ← Volver a suscripciones
      </a>

      <a class="btn btn-primary"
         href="{{ route('pagos.create', $suscripcion) }}">
        Registrar pago
      </a>
    </div>
  </div>

  {{-- MENSAJES --}}
  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  {{-- INFO SUSCRIPCIÓN --}}
  <div class="card mb-3">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <div class="small text-muted">Plan actual</div>
          <div class="fw-semibold">
            {{ optional($suscripcion->plan)->nombre ?? '—' }}
          </div>
        </div>

        <div class="col-md-3">
          <div class="small text-muted">Precio actual</div>
          <div class="fw-semibold">
            {{ number_format($suscripcion->precio,2,',','.') }} €
          </div>
        </div>

        <div class="col-md-3">
          <div class="small text-muted">Fecha de alta</div>
          <div class="fw-semibold">
            {{ \Carbon\Carbon::parse($suscripcion->fecha_alta)->format('d/m/Y') }}
          </div>
        </div>

        <div class="col-md-3">
          <div class="small text-muted">Estado</div>
          <div class="fw-semibold">
            @if($suscripcion->activa)
              <span class="badge bg-success">Activa</span>
            @else
              <span class="badge bg-secondary">Inactiva</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TABLA DE PAGOS --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Periodo</th>
              <th>Importe</th>
              <th>Fecha de pago</th>
              <th>Método</th>
              <th>Estado</th>
              <th>Observaciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pagos as $p)
              <tr>
                <td>{{ $p->id_pago }}</td>

                <td>
                  {{ optional($p->periodo)->format('m/Y') ?? '—' }}
                </td>

                <td>
                  {{ number_format($p->importe,2,',','.') }} €
                </td>

                <td>
                  {{ optional($p->fecha_pago)->format('d/m/Y') ?? '—' }}
                </td>

                <td>
                  {{ ucfirst($p->metodo ?? '—') }}
                </td>

                <td>
                  @php $estado = $p->estado ?? '—'; @endphp

                  @if($estado === 'pagado')
                    <span class="badge bg-success">Pagado</span>
                  @elseif($estado === 'pendiente')
                    <span class="badge bg-warning text-dark">Pendiente</span>
                  @elseif($estado === '—')
                    <span class="badge bg-secondary">—</span>
                  @else
                    <span class="badge bg-secondary">
                      {{ ucfirst($estado) }}
                    </span>
                  @endif
                </td>

                <td class="text-muted">
                  {{ $p->observaciones ?? '—' }}
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                    href="{{ route('pagos.edit', [$suscripcion, $p]) }}">
                    Editar
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                  No hay pagos registrados para esta suscripción.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- PAGINACIÓN --}}
  @if(method_exists($pagos, 'links'))
    <div class="mt-3">
      {{ $pagos->links() }}
    </div>
  @endif

</div>
@endsection
