@extends('layouts.app')

@section('title', 'Masuk')
@section('guest', 'true')
@section('body_class', 'body-login')

@section('content')
<section class="auth">
    <div class="auth__brand">
        <a class="logo" href="{{ route('login') }}">CIGARRAY</a>

        <div>
            <p class="eyebrow">Sistem Toko Rokok</p>
            <h1>Website Kelola Toko Rokok</h1>
            <p>CIGARRAY membantu memantau stok produk, melihat ringkasan harian, dan mengatur profil admin dalam satu tempat.</p>
            <ul class="auth__points">
                <li>Dashboard ringkasan harian</li>
                <li>Pengelolaan stok produk real-time</li>
                <li>Profil admin yang rapi</li>
            </ul>
        </div>

        <small class="age">18+ · Produk tembakau hanya untuk usia dewasa.</small>
    </div>

    <div class="auth__form">
        <div class="auth__box">
            <p class="eyebrow">Selamat datang</p>
            <h2>Masuk</h2>

            <form method="POST" action="{{ route('login.process') }}" novalidate>
                @csrf

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                       placeholder="contoh: dany" autofocus
                       class="{{ $errors->has('username') ? 'is-invalid' : '' }}">
                @error('username')
                    <small class="error">{{ $message }}</small>
                @enderror

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password')
                    <small class="error">{{ $message }}</small>
                @enderror

                <button type="submit" class="btn btn--block">Masuk ke Dashboard</button>
            </form>
        </div>

        <x-footer />
    </div>
</section>
@endsection
