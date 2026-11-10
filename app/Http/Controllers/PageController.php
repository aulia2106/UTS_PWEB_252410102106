<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    private const KATEGORI = ['Kretek' => 'KR', 'Mild' => 'ML', 'Putihan' => 'PT'];

    /**
     * Data awal produk rokok (contoh). Setelah itu data disimpan di session sehingga bisa di-CRUD.
     */
    private function dataAwal(): array
    {
        return [
            ['kode' => 'KR-001', 'nama' => 'Gudang Garam Surya 12', 'kategori' => 'Kretek',  'harga' => 29000, 'stok' => 120],
            ['kode' => 'KR-002', 'nama' => 'Djarum Super 12',       'kategori' => 'Kretek',  'harga' => 26000, 'stok' => 8],
            ['kode' => 'KR-003', 'nama' => 'Dji Sam Soe 12',        'kategori' => 'Kretek',  'harga' => 30000, 'stok' => 64],
            ['kode' => 'ML-001', 'nama' => 'Sampoerna A Mild 16',   'kategori' => 'Mild',    'harga' => 34000, 'stok' => 90],
            ['kode' => 'ML-002', 'nama' => 'LA Lights 16',          'kategori' => 'Mild',    'harga' => 33000, 'stok' => 7],
            ['kode' => 'ML-003', 'nama' => 'Class Mild 16',         'kategori' => 'Mild',    'harga' => 32000, 'stok' => 0],
            ['kode' => 'PT-001', 'nama' => 'Marlboro Red 20',       'kategori' => 'Putihan', 'harga' => 45000, 'stok' => 55],
            ['kode' => 'PT-002', 'nama' => 'Dunhill Filter 16',     'kategori' => 'Putihan', 'harga' => 40000, 'stok' => 18],
            ['kode' => 'PT-003', 'nama' => 'Camel Filter 20',       'kategori' => 'Putihan', 'harga' => 43000, 'stok' => 9],
        ];
    }

    /** Ambil produk dari session; isi dengan data awal jika belum ada. */
    private function ambilMenu(): array
    {
        if (! session()->has('produk_rokok')) {
            session(['produk_rokok' => $this->dataAwal()]);
        }
        return session('produk_rokok');
    }

    /** Tambahkan label status berdasarkan jumlah stok. */
    private function denganStatus(array $menu): array
    {
        return array_map(function ($item) {
            if ($item['stok'] === 0) {
                $item['status'] = 'Habis';
                $item['warna']  = 'danger';
            } elseif ($item['stok'] <= 10) {
                $item['status'] = 'Menipis';
                $item['warna']  = 'warning';
            } else {
                $item['status'] = 'Tersedia';
                $item['warna']  = 'success';
            }
            return $item;
        }, $menu);
    }

    /** Buat kode otomatis, mis. KP-004, berdasarkan kategori. */
    private function buatKode(array $menu, string $kategori): string
    {
        $prefix = self::KATEGORI[$kategori];
        $max = 0;
        foreach ($menu as $m) {
            if (str_starts_with($m['kode'], $prefix . '-')) {
                $max = max($max, (int) substr($m['kode'], 3));
            }
        }
        return sprintf('%s-%03d', $prefix, $max + 1);
    }

    private function aturanForm(): array
    {
        return [
            'nama'     => ['required', 'string', 'min:3', 'max:50'],
            'kategori' => ['required', Rule::in(array_keys(self::KATEGORI))],
            'harga'    => ['required', 'integer', 'min:0', 'max:1000000'],
            'stok'     => ['required', 'integer', 'min:0', 'max:9999'],
        ];
    }

    private function pesanForm(): array
    {
        return [
            'nama.required'     => 'Nama produk wajib diisi.',
            'nama.min'          => 'Nama produk minimal 3 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in'       => 'Kategori tidak valid.',
            'harga.required'    => 'Harga wajib diisi.',
            'harga.integer'     => 'Harga harus berupa angka bulat.',
            'harga.min'         => 'Harga tidak boleh negatif.',
            'stok.required'     => 'Stok wajib diisi.',
            'stok.integer'      => 'Stok harus berupa angka bulat.',
            'stok.min'          => 'Stok tidak boleh negatif.',
        ];
    }

    /** Menampilkan form login. */
    public function login()
    {
        return view('login');
    }

    /** Simulasi login: validasi input, lalu kirim username lewat request parameter. */
    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min'      => 'Username minimal 3 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        return redirect()->route('dashboard', ['username' => $data['username']]);
    }

    /** Dashboard: menerima ?username= dan menampilkan ringkasan dari data produk terkini. */
    public function dashboard(Request $request)
    {
        $username = $request->query('username');
        if (! $username) {
            return redirect()->route('login');
        }

        $menu = $this->denganStatus($this->ambilMenu());

        $stats = [
            ['label' => 'Total Produk',      'nilai' => count($menu)],
            ['label' => 'Total Stok (Bungkus)',      'nilai' => array_sum(array_column($menu, 'stok'))],
            ['label' => 'Stok Menipis',    'nilai' => count(array_filter($menu, fn ($m) => $m['warna'] !== 'success'))],
            ['label' => 'Nilai Inventori', 'nilai' => 'Rp ' . number_format(array_sum(array_map(fn ($m) => $m['harga'] * $m['stok'], $menu)), 0, ',', '.')],
        ];

        $aktivitas = [
            ['waktu' => '08.00', 'teks' => 'Toko dibuka, stok pagi dicek'],
            ['waktu' => '10.30', 'teks' => 'Pelanggan membeli 10 bungkus Sampoerna A Mild 16'],
            ['waktu' => '13.15', 'teks' => 'Stok Class Mild 16 habis'],
            ['waktu' => '15.45', 'teks' => 'Restock Gudang Garam Surya 12 dari distributor'],
        ];

        // Produk yang stoknya menipis atau habis
        $perhatian = array_values(array_filter($menu, fn ($m) => $m['warna'] !== 'success'));

        return view('dashboard', compact('username', 'stats', 'aktivitas', 'perhatian'));
    }

    /** Pengelolaan produk: daftar (@foreach), pencarian, dan mode edit lewat ?edit=KODE. */
    public function pengelolaan(Request $request)
    {
        $username = $request->query('username');
        if (! $username) {
            return redirect()->route('login');
        }

        $semua   = $this->ambilMenu();
        $keyword = trim((string) $request->query('q', ''));
        $menu    = $this->denganStatus($semua);

        if ($keyword !== '') {
            $menu = array_values(array_filter($menu, fn ($m) =>
                stripos($m['nama'], $keyword) !== false || stripos($m['kategori'], $keyword) !== false
            ));
        }

        $editItem = null;
        if ($kode = $request->query('edit')) {
            foreach ($semua as $m) {
                if ($m['kode'] === $kode) {
                    $editItem = $m;
                    break;
                }
            }
        }

        $kategoriList = array_keys(self::KATEGORI);

        return view('pengelolaan', compact('username', 'menu', 'keyword', 'editItem', 'kategoriList'));
    }

    /** CREATE: tambah produk baru. */
    public function store(Request $request)
    {
        $username = $request->input('username');
        $data = $request->validate($this->aturanForm(), $this->pesanForm());

        $menu = $this->ambilMenu();
        $menu[] = [
            'kode'     => $this->buatKode($menu, $data['kategori']),
            'nama'     => $data['nama'],
            'kategori' => $data['kategori'],
            'harga'    => (int) $data['harga'],
            'stok'     => (int) $data['stok'],
        ];
        session(['produk_rokok' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Produk \"{$data['nama']}\" berhasil ditambahkan.");
    }

    /** UPDATE: ubah produk berdasarkan kode. */
    public function update(Request $request, string $kode)
    {
        $username = $request->input('username');
        $data = $request->validate($this->aturanForm(), $this->pesanForm());

        $menu = $this->ambilMenu();
        foreach ($menu as $i => $m) {
            if ($m['kode'] === $kode) {
                $menu[$i] = [
                    'kode'     => $m['kode'],
                    'nama'     => $data['nama'],
                    'kategori' => $data['kategori'],
                    'harga'    => (int) $data['harga'],
                    'stok'     => (int) $data['stok'],
                ];
                break;
            }
        }
        session(['produk_rokok' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Produk \"{$data['nama']}\" berhasil diperbarui.");
    }

    /** DELETE: hapus produk berdasarkan kode. */
    public function destroy(Request $request, string $kode)
    {
        $username = $request->input('username');

        $menu = $this->ambilMenu();
        $nama = collect($menu)->firstWhere('kode', $kode)['nama'] ?? $kode;
        $menu = array_values(array_filter($menu, fn ($m) => $m['kode'] !== $kode));
        session(['produk_rokok' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Produk \"{$nama}\" berhasil dihapus.");
    }

    /** Profil pengguna: username dari request, data lain dari controller. */
    public function profile(Request $request)
    {
        $username = $request->query('username');
        if (! $username) {
            return redirect()->route('login');
        }

        $profil = [
            'Nama Pengguna' => $username,
            'Email'         => strtolower($username) . '@cigarray.id',
            'Peran'         => 'Admin Toko',
            'Cabang'        => 'CIGARRAY Jember',
            'Bergabung'     => '3 November 2026',
        ];

        $keahlian = ['Manajemen Stok', 'Kasir', 'Pelayanan Pelanggan', 'Laporan Harian'];

        return view('profile', compact('username', 'profil', 'keahlian'));
    }
}
