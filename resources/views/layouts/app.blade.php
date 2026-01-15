<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>@yield('title','Gimnasio')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
  </head>
  <body>
    @php
      // Sesiones activas en cada guard
      $isEmpleado = auth('web')->check();      // empleados (Administrador/Monitor/Limpieza)
      $isUsuario  = auth('usuario')->check();  // usuarios finales
      $empleado   = $isEmpleado ? auth('web')->user() : null;
      $isAdmin = auth('web')->check() && $empleado?->rol === 'Administrador';
    @endphp 

    <nav class="navbar navbar-dark bg-dark">
      <div class="container d-flex align-items-center">
        <a class="navbar-brand" href="{{ url('/') }}">Gimnasio</a>

        <div class="ms-auto d-flex align-items-center gap-2">
          @if($isAdmin)
            @php
              // Cargamos gimnasios aquí (simple).
              $gimnasiosNav = \App\Models\Gimnasio::orderBy('nombre')->get();
              $gimAct = session('gimnasio_activo');
            @endphp

            <form method="POST" action="{{ route('admin.ctx.gimnasio.set') }}" class="d-flex align-items-center gap-2 ms-3">
              @csrf
              <select name="id_gimnasio" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">— Elegir gimnasio —</option>
                @foreach($gimnasiosNav as $g)
                  <option value="{{ $g->id_gimnasio }}" @selected((string)$gimAct === (string)$g->id_gimnasio)>
                    {{ $g->nombre }}
                  </option>
                @endforeach
              </select>
            </form>

            @if($gimAct)
              <form method="POST" action="{{ route('admin.ctx.gimnasio.clear') }}" class="ms-2">
                @csrf
                <button class="btn btn-sm btn-outline-light">Quitar filtro</button>
              </form>
            @endif
          @endif
          @if($isEmpleado)
            @if($empleado->rol === 'Administrador')
              <a class="btn btn-sm btn-outline-light" href="{{ route('panel.admin') }}">Panel Administrador</a>
            @else
              <a class="btn btn-sm btn-outline-light" href="{{ route('panel.empleado') }}">Panel Empleado</a>
            @endif
          @elseif($isUsuario)
            <a class="btn btn-sm btn-outline-light" href="{{ route('u.panel') }}">Mi panel</a>
          @else
            <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Acceder</a>
          @endif

          @if($isEmpleado || $isUsuario)
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="btn btn-sm btn-outline-light">Salir</button>
            </form>
          @endif
        </div>
      </div>
    </nav>
    
    @yield('content')
    
  </body>
</html>


