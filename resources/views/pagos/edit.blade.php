@extends('layouts.app')
@section('title','Editar pago')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Editar pago #{{ $pago->id_pago }}</h1>

  <div class="alert alert-info">
    Suscripción #{{ $suscripcion->id_suscripcion }} —
    Usuario: <strong>{{ optional($suscripcion->usuario)->nombre }} {{ optional($suscripcion->usuario)->apellidos }}</strong>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('pagos.update', [$suscripcion, $pago]) }}">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Periodo</label>
      <input type="month" class="form-control"
             value="{{ optional($pago->periodo)->format('Y-m') ?? '' }}" readonly>
      <div class="form-text">El periodo no se cambia (evita duplicados).</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Importe (€)</label>
      <input type="number" class="form-control"
             value="{{ old('importe', $pago->importe) }}" readonly>
      <div class="form-text">El importe no se edita desde aquí.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Fecha de pago</label>
      <input type="date" name="fecha_pago" class="form-control"
             value="{{ old('fecha_pago', optional($pago->fecha_pago)->toDateString()) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Método</label>
      @php $metodo = old('metodo', $pago->metodo); @endphp
      <select name="metodo" class="form-select" required>
        <option value="tarjeta" @selected($metodo==='tarjeta')>Tarjeta</option>
        <option value="efectivo" @selected($metodo==='efectivo')>Efectivo</option>
        <option value="transferencia" @selected($metodo==='transferencia')>Transferencia</option>
        <option value="bizum" @selected($metodo==='bizum')>Bizum</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Estado</label>
      @php $estado = old('estado', $pago->estado ?? 'pagado'); @endphp
      <select name="estado" class="form-select" required>
        <option value="pagado" @selected($estado==='pagado')>Pagado</option>
        <option value="pendiente" @selected($estado==='pendiente')>Pendiente</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Referencia (opcional)</label>
      <input name="referencia" class="form-control"
             value="{{ old('referencia', $pago->referencia) }}" maxlength="100">
    </div>

    <div class="mb-3">
      <label class="form-label">Observaciones (opcional)</label>
      <input name="observaciones" class="form-control"
             value="{{ old('observaciones', $pago->observaciones) }}" maxlength="255">
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="{{ route('pagos.index', $suscripcion) }}">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
@endsection
