<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title', 'Beranda') · CIGARRAY</title>
</head>
<body class="@yield('body_class')">

    @hasSection('guest')
        {{-- Halaman tamu (login): layar penuh tanpa sidebar --}}
        @yield('content')
    @else
        <div class="shell">
            <x-navbar :username="$username ?? null" />

            <div class="main">
                <main class="content">
                    @yield('content')
                </main>
                <x-footer />
            </div>
        </div>
    @endif

</body>
</html>
