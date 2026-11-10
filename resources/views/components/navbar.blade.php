@props(['username' => null])

{{-- Navbar berbentuk sidebar vertikal (di layar kecil berubah menjadi bar atas dengan menu buka-tutup) --}}
<aside class="sidebar">
    <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Buka menu">

    <div class="sidebar__top">
        <a class="logo" href="{{ route('dashboard', ['username' => $username]) }}">CIGARRAY</a>
        <label for="nav-toggle" class="nav-burger" aria-hidden="true"><span></span><span></span><span></span></label>
    </div>

    <div class="sidebar__panel">
        <p class="eyebrow">Tobacco Inventory</p>

        <nav class="menu">
            <a href="{{ route('dashboard', ['username' => $username]) }}"
               class="menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu__no">01</span> Dashboard
            </a>
            <a href="{{ route('pengelolaan', ['username' => $username]) }}"
               class="menu__item {{ request()->routeIs('pengelolaan') ? 'active' : '' }}">
                <span class="menu__no">02</span> Pengelolaan
            </a>
            <a href="{{ route('profile', ['username' => $username]) }}"
               class="menu__item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <span class="menu__no">03</span> Profile
            </a>
        </nav>

        <div class="sidebar__user">
            <div class="avatar-sm">{{ strtoupper(substr($username ?? '?', 0, 1)) }}</div>
            <div>
                <small>Masuk sebagai</small>
                <span class="hello">Halo, {{ $username }}</span>
            </div>
        </div>

        <a href="{{ route('login') }}" class="btn btn--light btn--block">Keluar</a>
    </div>
</aside>
