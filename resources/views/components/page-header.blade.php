@props(['eyebrow' => null, 'title', 'subtitle' => null])

{{-- Komponen judul halaman. Isi slot (opsional) tampil di sisi kanan, mis. tombol aksi. --}}
<header class="page-header">
    <div>
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif
        <h1>{{ $title }}</h1>
        @if ($subtitle)
            <p class="lead">{{ $subtitle }}</p>
        @endif
    </div>
    @if (! $slot->isEmpty())
        <div class="page-header__actions">{{ $slot }}</div>
    @endif
</header>
