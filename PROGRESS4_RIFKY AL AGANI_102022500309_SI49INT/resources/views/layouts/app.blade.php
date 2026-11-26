<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Outpatient Clinic</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  <nav class="navbar">
    <div class="nav-brand">🏥 Outpatient Clinic</div>
    <div class="nav-links">
      @auth
        <a href="{{ route('drugs.index') }}" class="nav-item {{ request()->routeIs('drugs.index') ? 'active' : '' }}">Catalogue</a>
        @if (auth()->user()->role === 'admin')
          <a href="{{ route('drugs.create') }}" class="nav-item {{ request()->routeIs('drugs.create', 'drugs.edit') ? 'active' : '' }}">Add Drug Form</a>
        @endif
      @endauth
    </div>
    <div class="nav-links">
      @auth
        <span class="nav-item">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav-item" style="background:none;border:none;color:#fca5a5;cursor:pointer;">Logout</button>
        </form>
      @endauth
    </div>
  </nav>

  <div class="container">
    @yield('content')

    <div class="footer">© ripky punya 2026</div>
  </div>

</body>
</html>
