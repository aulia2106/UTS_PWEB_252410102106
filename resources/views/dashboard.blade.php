@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<x-page-header eyebrow="Dashboard" :title="'Selamat datang, ' . $username" subtitle="Ringkasan operasional CIGARRAY hari ini.">
    <a class="btn" href="{{ route('pengelolaan', ['username' => $username]) }}">Kelola Produk →</a>
</x-page-header>

<section class="stats">
    @foreach ($stats as $stat)
        @include('partials.stat-card', ['nilai' => $stat['nilai'], 'label' => $stat['label']])
    @endforeach
</section>

<section class="split">
    <div class="panel">
        <div class="panel__head"><h2>Aktivitas Hari Ini</h2></div>
        <ul class="timeline">
            @foreach ($aktivitas as $a)
                <li>
                    <span class="timeline__time">{{ $a['waktu'] }}</span>
                    <span>{{ $a['teks'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="panel">
        <div class="panel__head"><h2>Perlu Perhatian</h2><span class="muted small">Stok menipis / habis</span></div>
        <ul class="watch">
            @forelse ($perhatian as $p)
                <li>
                    <div>
                        {{ $p['nama'] }}
                        <small>{{ $p['kode'] }} · sisa {{ $p['stok'] }} bungkus</small>
                    </div>
                    <span class="pill pill--{{ $p['warna'] }}">{{ $p['status'] }}</span>
                </li>
            @empty
                <li class="muted">Semua stok aman.</li>
            @endforelse
        </ul>
    </div>
</section>
@endsection
