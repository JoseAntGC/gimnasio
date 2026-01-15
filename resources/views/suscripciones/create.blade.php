@extends('layouts.app')
@section('title','Nueva suscripción')

@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Nueva suscripción</h1>

  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('suscripciones.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <select name="id_usuario" class="form-select" required>
        <option value="">— Selecciona —</option>
        @foreach($usuarios as $u)
          <option value="{{ $u->id_usuario }}" @selected(old('id_usuario')==$u->id_usuario)>
            {{ $u->nombre }} {{ $u->apellidos }} (G{{ $u->id_gimnasio }})
          </option>
        @endforeach
      </select>
      <div class="form-text">Solo aparecen usuarios activos del gimnasio permitido.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Plan</label>
      <select name="id_plan" class="form-select" required id="planSelect">
        <option value="">— Selecciona —</option>
        @foreach($planes as $p)
          <option value="{{ $p->id_plan }}"
                  data-precio="{{ $p->precio }}"
                  @selected(old('id_plan')==$p->id_plan)>
            {{ $p->nombre }} — {{ number_format($p->precio,2,',','.') }} €
          </option>
        @endforeach
      </select>
      <div class="form-text">El precio se guardará automáticamente según el plan elegido.</div>
    </div>

    {{-- Precio solo informativo (NO se envía) --}}
    <div class="mb-3">
      <label class="form-label">Precio</label>
      <input type="text" class="form-control" id="precioPreview" value="" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Fecha de alta</label>
      <input type="date" name="fecha_alta" class="form-control"
             value="{{ old('fecha_alta', now()->toDateString()) }}" required>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>

<script>
  // Previsualiza el precio del plan seleccionado 
  document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('planSelect');
    const precio = document.getElementById('precioPreview');

    function refresh() {
      const opt = select.options[select.selectedIndex];
      const p = opt ? opt.getAttribute('data-precio') : '';
      if (!p) { precio.value = ''; return; }
      precio.value = Number(p).toLocaleString('es-ES', { style: 'currency', currency: 'EUR' });
    }

    select.addEventListener('change', refresh);
    refresh();
  });
</script>
@endsection
