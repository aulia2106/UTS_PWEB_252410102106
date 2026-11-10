# UTS PWEB — CIGARRAY (Laravel + Blade, MVC)

**NIM:** 252410102106

Aplikasi web **CIGARRAY**: sistem pengelolaan toko rokok sederhana (tampilan hitam putih) yang dibuat dengan Laravel murni (tanpa template UI siap pakai) untuk mempelajari alur **request → route → controller → view → response**.

## Fitur

- **Login (simulasi)** — form username & password, divalidasi di controller, lalu `redirect` ke dashboard membawa `?username=...`.
- **Dashboard** — menyapa pengguna, menampilkan kartu statistik dan aktivitas hari ini (data array dari controller, di-loop dengan `@foreach`).
- **Pengelolaan (CRUD)** — admin dapat menambah, mengubah, dan menghapus produk rokok (kode, nama, kategori, harga, stok, status). Data berupa array di controller yang disimpan di session, dirender dengan `@forelse`, lengkap dengan pencarian dan validasi.
- **Profile** — detail akun; username diteruskan lewat request parameter.
- **Tata letak eksklusif** — sidebar hitam (navbar) di kiri, login layar terbelah, panel form di samping tabel. Di layar kecil sidebar menjadi bar atas dengan menu buka-tutup dan tabel berubah menjadi kartu.

## Struktur File

```
routes/web.php
app/Http/Controllers/PageController.php
resources/views/
├── layouts/app.blade.php
├── login.blade.php
├── dashboard.blade.php
├── pengelolaan.blade.php
├── profile.blade.php
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   └── page-header.blade.php
└── partials/
    ├── head.blade.php
    └── stat-card.blade.php
public/css/style.css
```

## Penerapan Konsep

| Konsep | Lokasi |
| --- | --- |
| MVC (view dipanggil dari controller) | `PageController` memanggil `view(...)` |
| `@extends` | semua view halaman |
| `@section` / `@endsection` | semua view halaman |
| `@yield` | `layouts/app.blade.php` |
| `@include` | `layouts/app` → `partials.head`, `dashboard` → `partials.stat-card` |
| `x-component` | `<x-navbar>`, `<x-footer>` di layout; `<x-page-header>` di tiap halaman |
| Request parameter | `?username=` dibaca lewat `$request->query('username')` |
| Array rendering | `@foreach` / `@forelse` pada dashboard, pengelolaan, profile |

## Cara Menjalankan

```bash
composer create-project laravel/laravel UTS_PWEB_252410102106
cd UTS_PWEB_252410102106

php artisan serve
```

Buka `http://127.0.0.1:8000`, isi username & password bebas (min. 3 dan 6 karakter).

## Screenshot

| Login | Dashboard |
| --- | --- |
| ![Login](screenshots/login.png) | ![Dashboard](screenshots/dashboard.png) |

| Pengelolaan | Profile |
| --- | --- |
| ![Pengelolaan](screenshots/pengelolaan.png) | ![Profile](screenshots/profile.png) |
