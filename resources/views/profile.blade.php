@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<x-page-header eyebrow="Profile" title="Akun Admin" subtitle="Informasi akun yang sedang masuk." />

<section class="profile">
    <div class="profile__side">
        <div class="avatar">{{ strtoupper(substr($username, 0, 1)) }}</div>
        <h2>{{ $username }}</h2>
        <p class="eyebrow">{{ $profil['Peran'] }}</p>
        <div class="chips">
            @foreach ($keahlian as $k)
                <span class="chip">{{ $k }}</span>
            @endforeach
        </div>
    </div>

    <div class="panel">
        <div class="panel__head"><h2>Detail Akun</h2></div>
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
