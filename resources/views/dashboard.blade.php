@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="page-head">
    <div>
        <h1>Selamat datang, {{ $username }}!</h1>
        <p class="muted">Berikut ringkasan operasional Coffeemist hari ini.</p>
    </div>
    <a class="btn" href="{{ route('pengelolaan', ['username' => $username]) }}">Kelola Menu →</a>
</section>

<section class="grid grid--4">
    @foreach ($stats as $stat)
        @include('partials.stat-card', ['nilai' => $stat['nilai'], 'label' => $stat['label']])
    @endforeach
</section>

<section class="card">
    <h2>Aktivitas Hari Ini</h2>
    <ul class="timeline">
        @foreach ($aktivitas as $a)
            <li>
                <span class="timeline__time">{{ $a['waktu'] }}</span>
                <span>{{ $a['teks'] }}</span>
            </li>
        @endforeach
    </ul>
</section>
@endsection
