<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title', 'Beranda') · Coffeemist</title>
</head>
<body class="@yield('body_class')">

    @hasSection('guest')
        {{-- Halaman tamu (login) tidak menampilkan navbar --}}
    @else
        <x-navbar :username="$username ?? null" />
    @endif

    <main class="container">
        @yield('content')
    </main>

    <x-footer />

</body>
</html>
