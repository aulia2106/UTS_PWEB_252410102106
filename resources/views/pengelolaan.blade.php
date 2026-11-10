@extends('layouts.app')

@section('title', 'Pengelolaan')

@section('content')
<x-page-header eyebrow="Pengelolaan" title="Katalog Rokok" subtitle="Tambah, ubah, dan hapus rokok yang dijual di CIGARRAY." />

@if (session('sukses'))
    <div class="alert">{{ session('sukses') }}</div>
@endif

<div class="workspace">

    {{-- Daftar produk dari controller --}}
    <section class="panel">
        <div class="toolbar">
            <h2>Daftar Rokok <span class="muted small">· {{ count($menu) }} item</span></h2>
            <form method="GET" action="{{ route('pengelolaan') }}" class="search">
                <input type="hidden" name="username" value="{{ $username }}">
                <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari produk atau kategori…">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th class="num">Harga</th>
                        <th class="num">Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menu as $item)
                        <tr>
                            <td data-label="Produk">
                                <div class="prod"><strong>{{ $item['nama'] }}</strong><small>{{ $item['kode'] }}</small></div>
                            </td>
                            <td data-label="Kategori">{{ $item['kategori'] }}</td>
                            <td data-label="Harga" class="num">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td data-label="Stok" class="num">{{ $item['stok'] }}</td>
                            <td data-label="Status"><span class="pill pill--{{ $item['warna'] }}">{{ $item['status'] }}</span></td>
                            <td data-label="Aksi">
                                <div class="actions">
                                    <a class="btn btn--xs btn--ghost"
                                       href="{{ route('pengelolaan', ['username' => $username, 'edit' => $item['kode']]) }}">Edit</a>
                                    <form method="POST" action="{{ route('menu.destroy', $item['kode']) }}"
                                          onsubmit="return confirm('Hapus produk {{ addslashes($item['nama']) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="username" value="{{ $username }}">
                                        <button type="submit" class="btn btn--xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                {{ $keyword !== '' ? 'Tidak ada produk yang cocok dengan “' . $keyword . '”.' : 'Belum ada produk. Tambahkan produk pertama di panel samping.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Panel form Tambah / Edit --}}
    <aside class="form-panel">
        <p class="eyebrow">{{ $editItem ? 'Ubah data' : 'Produk baru' }}</p>
        <h2>{{ $editItem ? 'Edit ' . $editItem['kode'] : 'Tambah Produk' }}</h2>

        <form method="POST"
              action="{{ $editItem ? route('menu.update', $editItem['kode']) : route('menu.store') }}"
              novalidate>
            @csrf
            @if ($editItem)
                @method('PUT')
            @endif
            <input type="hidden" name="username" value="{{ $username }}">

            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" placeholder="contoh: Gudang Garam Surya 12"
                   value="{{ old('nama', $editItem['nama'] ?? '') }}"
                   class="{{ $errors->has('nama') ? 'is-invalid' : '' }}">
            @error('nama') <small class="error">{{ $message }}</small> @enderror

            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="{{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                <option value="">Pilih kategori</option>
                @foreach ($kategoriList as $kat)
                    <option value="{{ $kat }}" @selected(old('kategori', $editItem['kategori'] ?? '') === $kat)>{{ $kat }}</option>
                @endforeach
            </select>
            @error('kategori') <small class="error">{{ $message }}</small> @enderror

            <label for="harga">Harga per Bungkus (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" placeholder="contoh: 29000"
                   value="{{ old('harga', $editItem['harga'] ?? '') }}"
                   class="{{ $errors->has('harga') ? 'is-invalid' : '' }}">
            @error('harga') <small class="error">{{ $message }}</small> @enderror

            <label for="stok">Stok (Bungkus)</label>
            <input type="number" id="stok" name="stok" min="0" placeholder="contoh: 15"
                   value="{{ old('stok', $editItem['stok'] ?? '') }}"
                   class="{{ $errors->has('stok') ? 'is-invalid' : '' }}">
            @error('stok') <small class="error">{{ $message }}</small> @enderror

            <div class="form-actions">
                <button type="submit" class="btn btn--light">{{ $editItem ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
                @if ($editItem)
                    <a class="btn btn--outline-light" href="{{ route('pengelolaan', ['username' => $username]) }}">Batal</a>
                @endif
            </div>
        </form>
    </aside>
</div>
@endsection
