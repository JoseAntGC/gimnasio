@extends('layouts.app')
@section('title','Editar suscripción')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Editar suscripción #{{($suscripcion->usuario)->apellidos }}, {{ ($suscripcion->usuario)->nombre }}</h1>

  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('suscripciones.update',$suscripcion) }}">
    @csrf @method('PUT')

    {{-- Usuario fijo (no editable) --}}
    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input class="form-control" type="text" readonly
             value="{{ optional($suscripcion->usuario)->nombre }} {{ optional($suscripcion->usuario)->apellidos }} (ID {{ $suscripcion->id_usuario }})">
    </div>

    <div class="mb-3">
      <label class="form-label">Plan</label>
      <select name="id_plan" class="form-select" required id="planSelect">
        @foreach($planes as $p)
          <option value="{{ $p->id_plan }}"
                  data-precio="{{ $p->precio }}"
                  @selected(old('id_plan',$suscripcion->id_plan)==$p->id_plan)>
            {{ $p->nombre }} — {{ number_format($p->precio,2,',','.') }} €
          </option>
        @endforeach
      </select>
    </div>

    {{-- Precio mostrado (NO editable) --}}
    <div class="mb-3">
      <label class="form-label">Precio</label>
      <input type="text" class="form-control" id="precioPreview"
             value="{{ number_format($suscripcion->precio,2,',','.') }} €" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Activa</label>
      <select name="activa" class="form-select">
        <option value="1" @selected(old('activa',$suscripcion->activa)=='1')>Sí</option>
        <option value="0" @selected(old('activa',$suscripcion->activa)=='0')>No</option>
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>

<script>
  // Previsualiza el precio del plan seleccionado
  document.addEventListener('DOMContentLoaded', () => {
    let select = document.getElementById('planSelect');
    let precio = document.getElementById('precioPreview');

    function refresh() {
      let opt = select.options[select.selectedIndex];
      let p = opt ? opt.getAttribute('data-precio') : '';
      if (!p) return;
      precio.value = Number(p).toLocaleString('es-ES', { style: 'currency', currency: 'EUR' });
    }

    select.addEventListener('change', refresh);
  });
</script>
@endsection
