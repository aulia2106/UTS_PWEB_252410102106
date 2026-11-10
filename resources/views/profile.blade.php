@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<section class="page-head">
    <div>
        <h1>Profile</h1>
        <p class="muted">Informasi akun yang sedang masuk.</p>
    </div>
</section>

<section class="profile">
    <div class="card profile__side">
        <div class="avatar">{{ strtoupper(substr($username, 0, 1)) }}</div>
        <h2>{{ $username }}</h2>
        <p class="muted">{{ $profil['Peran'] }}</p>
        <div class="chips">
            @foreach ($keahlian as $k)
                <span class="chip">{{ $k }}</span>
            @endforeach
        </div>
    </div>

    <div class="card profile__main">
        <h2>Detail Akun</h2>
        <dl class="details">
            @foreach ($profil as $label => $isi)
                <div>
                    <dt>{{ $label }}</dt>
                    <dd>{{ $isi }}</dd>
                </div>
            @endforeach
        </dl>
        <a class="btn btn--ghost" href="{{ route('dashboard', ['username' => $username]) }}">← Kembali ke Dashboard</a>
    </div>
</section>
@endsection
