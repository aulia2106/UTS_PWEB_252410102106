@props(['username' => null])

<header class="navbar">
    <div class="navbar__inner">
        <div class="brand-wrap">
            <a class="brand" href="{{ route('dashboard', ['username' => $username]) }}">Coffeemist</a>
            <span class="nav-user">Halo, <strong>{{ $username }}</strong></span>
        </div>

        <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Buka menu">
        <label for="nav-toggle" class="nav-burger" aria-hidden="true"><span></span><span></span><span></span></label>

        <nav class="nav-links">
            <a href="{{ route('dashboard', ['username' => $username]) }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('pengelolaan', ['username' => $username]) }}"
               class="{{ request()->routeIs('pengelolaan') ? 'active' : '' }}">Pengelolaan</a>
            <a href="{{ route('profile', ['username' => $username]) }}"
               class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
            <a href="{{ route('login') }}" class="btn btn--ghost btn--sm">Keluar</a>
        </nav>
    </div>
</header>
