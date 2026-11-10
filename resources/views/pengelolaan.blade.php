@extends('layouts.app')

@section('title', 'Pengelolaan')

@section('content')
<section class="page-head">
    <div>
        <h1>Pengelolaan Menu</h1>
        <p class="muted">Tambah, ubah, dan hapus menu Coffeemist.</p>
    </div>
    <form method="GET" action="{{ route('pengelolaan') }}" class="search">
        <input type="hidden" name="username" value="{{ $username }}">
        <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari menu atau kategori…">
        <button type="submit" class="btn btn--sm">Cari</button>
    </form>
</section>

@if (session('sukses'))
    <div class="alert alert--success">{{ session('sukses') }}</div>
@endif

{{-- Form Tambah / Edit --}}
<section class="card form-card">
    <h2>{{ $editItem ? 'Edit Menu ' . $editItem['kode'] : 'Tambah Menu Baru' }}</h2>

    <form method="POST"
          action="{{ $editItem ? route('menu.update', $editItem['kode']) : route('menu.store') }}"
          novalidate>
        @csrf
        @if ($editItem)
            @method('PUT')
        @endif
        <input type="hidden" name="username" value="{{ $username }}">

        <div class="form-grid">
            <div>
                <label for="nama">Nama Menu</label>
                <input type="text" id="nama" name="nama" placeholder="contoh: Latte Caramel"
                       value="{{ old('nama', $editItem['nama'] ?? '') }}"
                       class="{{ $errors->has('nama') ? 'is-invalid' : '' }}">
                @error('nama') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="{{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                    <option value="">Pilih kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat }}" @selected(old('kategori', $editItem['kategori'] ?? '') === $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
                @error('kategori') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="harga">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" min="0" placeholder="contoh: 20000"
                       value="{{ old('harga', $editItem['harga'] ?? '') }}"
                       class="{{ $errors->has('harga') ? 'is-invalid' : '' }}">
                @error('harga') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="stok">Stok</label>
                <input type="number" id="stok" name="stok" min="0" placeholder="contoh: 15"
                       value="{{ old('stok', $editItem['stok'] ?? '') }}"
                       class="{{ $errors->has('stok') ? 'is-invalid' : '' }}">
                @error('stok') <small class="error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">{{ $editItem ? 'Simpan Perubahan' : 'Tambah Menu' }}</button>
            @if ($editItem)
                <a class="btn btn--ghost" href="{{ route('pengelolaan', ['username' => $username]) }}">Batal</a>
            @endif
        </div>
    </form>
</section>

{{-- Daftar menu dari controller --}}
<section class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Menu</th>
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
                        <td data-label="#">{{ $loop->iteration }}</td>
                        <td data-label="Kode">{{ $item['kode'] }}</td>
                        <td data-label="Nama Menu"><strong>{{ $item['nama'] }}</strong></td>
                        <td data-label="Kategori">{{ $item['kategori'] }}</td>
                        <td data-label="Harga" class="num">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td data-label="Stok" class="num">{{ $item['stok'] }}</td>
                        <td data-label="Status"><span class="pill pill--{{ $item['warna'] }}">{{ $item['status'] }}</span></td>
                        <td data-label="Aksi">
                            <div class="actions">
                                <a class="btn btn--ghost btn--sm"
                                   href="{{ route('pengelolaan', ['username' => $username, 'edit' => $item['kode']]) }}">Edit</a>
                                <form method="POST" action="{{ route('menu.destroy', $item['kode']) }}"
                                      onsubmit="return confirm('Hapus menu {{ addslashes($item['nama']) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="username" value="{{ $username }}">
                                    <button type="submit" class="btn btn--danger btn--sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            {{ $keyword !== '' ? 'Tidak ada menu yang cocok dengan “' . $keyword . '”.' : 'Belum ada menu. Tambahkan menu pertama di atas.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="muted small">Menampilkan {{ count($menu) }} menu.</p>
</section>
@endsection
