@extends('layouts.app')

@section('title', 'Masuk')
@section('guest', 'true')
@section('body_class', 'body-login')

@section('content')
<section class="login">
    <div class="login__hero">
        <span class="badge">Sistem Kedai Kopi</span>
        <h1>Kelola kedai kopimu,<br>satu cangkir sekali waktu.</h1>
        <p>Coffeemist membantu memantau stok menu, melihat ringkasan harian, dan mengatur profil admin dalam satu tempat.</p>
        <ul class="login__points">
            <li>Dashboard ringkasan harian</li>
            <li>Pengelolaan stok menu real-time</li>
            <li>Profil admin yang rapi</li>
        </ul>
    </div>

    <div class="card login__card">
        <h2>Masuk</h2>

        <form method="POST" action="{{ route('login.process') }}" novalidate>
            @csrf

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                   placeholder="contoh: aulia" autofocus
                   class="{{ $errors->has('username') ? 'is-invalid' : '' }}">
            @error('username')
                <small class="error">{{ $message }}</small>
            @enderror

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimal 4 karakter"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            @error('password')
                <small class="error">{{ $message }}</small>
            @enderror

            <button type="submit" class="btn btn--block">Masuk ke Dashboard</button>
        </form>
    </div>
</section>
@endsection
