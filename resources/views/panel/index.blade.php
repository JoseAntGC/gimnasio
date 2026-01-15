@extends('layouts.app')
@section('title','Panel')
@section('content')
<div class="container py-4" style="max-width: 720px; margin: 0 auto;">
  <h1 class="h4 mb-3">Panel</h1>
  <p class="mb-0">
    Bienvenido, <strong>{{ $empleado->nombre }}</strong>
    (<span class="text-muted">{{ $empleado->rol }}</span>)
  </p>
</div>
@endsection
