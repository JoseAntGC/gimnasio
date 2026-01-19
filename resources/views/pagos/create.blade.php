@extends('layouts.app')
@section('title','Registrar pago')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Registrar pago</h1>

  <div class="alert alert-info">
    Suscripción #{{ $suscripcion->id_suscripcion }} —
    Usuario: <strong>{{ optional($suscripcion->usuario)->nombre }} {{ optional($suscripcion->usuario)->apellidos }}</strong>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('pagos.store', $suscripcion) }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Periodo (mes a pagar)</label>
      <input
        type="month"
        name="periodo"
        class="form-control"
        value="{{ old('periodo', now()->format('Y-m')) }}"
        required>
      <div>
        Selecciona el mes al que corresponde el pago (por ejemplo: 2026-01).
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Importe (€)</label>
      <input
        type="number"
        class="form-control"
        value="{{ number_format(old('importe', $suscripcion->precio), 2, '.', '') }}"
        readonly>
    </div>

    {{-- IMPORTANTE: se envía hidden para compatibilidad, pero en el Controller se debe ignorar y forzar el importe --}}
    <input type="hidden" name="importe" value="{{ old('importe', $suscripcion->precio) }}">

    <div class="mb-3">
      <label class="form-label">Fecha de pago</label>
      <input
        type="date"
        name="fecha_pago"
        class="form-control"
        value="{{ old('fecha_pago', now()->toDateString()) }}"
        required>
    </div>

    <div class="mb-3">
      <label class="form-label">Método</label>
      <select name="metodo" class="form-select" required>
        @php $metodo = old('metodo','tarjeta'); @endphp
        <option value="tarjeta" @selected($metodo==='tarjeta')>Tarjeta</option>
        <option value="efectivo" @selected($metodo==='efectivo')>Efectivo</option>
        <option value="transferencia" @selected($metodo==='transferencia')>Transferencia</option>
        <option value="bizum" @selected($metodo==='bizum')>Bizum</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Estado</label>
      <select name="estado" class="form-select" required>
        @php $estado = old('estado','pagado'); @endphp
        <option value="pagado" @selected($estado==='pagado')>Pagado</option>
        <option value="pendiente" @selected($estado==='pendiente')>Pendiente</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Observaciones (opcional)</label>
      <input
        name="observaciones"
        class="form-control"
        value="{{ old('observaciones') }}"
        maxlength="255"
        placeholder="Ej: pagó en recepción, comprobante #123...">
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="{{ route('pagos.index', $suscripcion) }}">Cancelar</a>
      <button class="btn btn-primary">Guardar pago</button>
    </div>

  </form>
</div>
@endsection
