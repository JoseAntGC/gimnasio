@extends('layouts.app')
@section('title','Acceso')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center login-wrap">
  <div class="card" style="max-width: 420px; width: 100%;">
    <div class="fondo p-4">
      <h1 class="h4 text-center mb-3">Acceso</h1>

      @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email"
                 class="form-control @error('email') is-invalid @enderror"
                 value="{{ old('email') }}" required autofocus>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
          <label for="password" class="form-label">Contraseña</label>
          <input id="password" type="password" name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Recordarme</label>
        </div>

        <button class="btn btn-primary w-100">Entrar</button>
      </form>

      <div class="text-center mt-3">
        <a class="small text-decoration-none" href="{{ url('/') }}">← Volver al inicio</a>
      </div>
    </div>
  </div>
</div>
@endsection
