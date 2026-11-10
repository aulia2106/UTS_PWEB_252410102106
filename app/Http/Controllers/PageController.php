<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    private const KATEGORI = ['Kopi' => 'KP', 'Non-Kopi' => 'NK', 'Makanan' => 'MK'];


    private function dataAwal(): array
    {
        return [
            ['kode' => 'KP-001', 'nama' => 'Kopi Tubruk Jember',     'kategori' => 'Kopi',     'harga' => 12000, 'stok' => 48],
            ['kode' => 'KP-002', 'nama' => 'Es Kopi Susu Gula Aren', 'kategori' => 'Kopi',     'harga' => 18000, 'stok' => 6],
            ['kode' => 'KP-003', 'nama' => 'Cappuccino',             'kategori' => 'Kopi',     'harga' => 22000, 'stok' => 30],
            ['kode' => 'NK-001', 'nama' => 'Teh Tarik Panas',        'kategori' => 'Non-Kopi', 'harga' => 14000, 'stok' => 25],
            ['kode' => 'NK-002', 'nama' => 'Matcha Latte',           'kategori' => 'Non-Kopi', 'harga' => 24000, 'stok' => 4],
            ['kode' => 'NK-003', 'nama' => 'Coklat Dingin',          'kategori' => 'Non-Kopi', 'harga' => 20000, 'stok' => 0],
            ['kode' => 'MK-001', 'nama' => 'Pisang Goreng Keju',     'kategori' => 'Makanan',  'harga' => 15000, 'stok' => 20],
            ['kode' => 'MK-002', 'nama' => 'Roti Bakar Coklat',      'kategori' => 'Makanan',  'harga' => 16000, 'stok' => 9],
            ['kode' => 'MK-003', 'nama' => 'Nasi Goreng Kampung',    'kategori' => 'Makanan',  'harga' => 25000, 'stok' => 14],
        ];
    }

    private function ambilMenu(): array
    {
        if (! session()->has('menu')) {
            session(['menu' => $this->dataAwal()]);
        }
        return session('menu');
    }

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
            'nama.required'     => 'Nama menu wajib diisi.',
            'nama.min'          => 'Nama menu minimal 3 karakter.',
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

    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30'],
            'password' => ['required', 'string', 'min:4'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min'      => 'Username minimal 3 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
        ]);

        return redirect()->route('dashboard', ['username' => $data['username']]);
    }

    public function dashboard(Request $request)
    {
        $username = $request->query('username');
        if (! $username) {
            return redirect()->route('login');
        }

        $menu = $this->denganStatus($this->ambilMenu());

        $stats = [
            ['label' => 'Total Menu',      'nilai' => count($menu)],
            ['label' => 'Total Stok',      'nilai' => array_sum(array_column($menu, 'stok'))],
            ['label' => 'Stok Menipis',    'nilai' => count(array_filter($menu, fn ($m) => $m['warna'] !== 'success'))],
            ['label' => 'Nilai Inventori', 'nilai' => 'Rp ' . number_format(array_sum(array_map(fn ($m) => $m['harga'] * $m['stok'], $menu)), 0, ',', '.')],
        ];

        $aktivitas = [
            ['waktu' => '08.00', 'teks' => 'Kedai dibuka, stok pagi dicek'],
            ['waktu' => '10.30', 'teks' => 'Pesanan 12 gelas Es Kopi Susu Gula Aren'],
            ['waktu' => '13.15', 'teks' => 'Stok Coklat Dingin habis'],
            ['waktu' => '15.45', 'teks' => 'Restock biji kopi dari petani lokal'],
        ];

        return view('dashboard', compact('username', 'stats', 'aktivitas'));
    }

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
        session(['menu' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Menu \"{$data['nama']}\" berhasil ditambahkan.");
    }

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
        session(['menu' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Menu \"{$data['nama']}\" berhasil diperbarui.");
    }

    public function destroy(Request $request, string $kode)
    {
        $username = $request->input('username');

        $menu = $this->ambilMenu();
        $nama = collect($menu)->firstWhere('kode', $kode)['nama'] ?? $kode;
        $menu = array_values(array_filter($menu, fn ($m) => $m['kode'] !== $kode));
        session(['menu' => $menu]);

        return redirect()->route('pengelolaan', ['username' => $username])
            ->with('sukses', "Menu \"{$nama}\" berhasil dihapus.");
    }

    public function profile(Request $request)
    {
        $username = $request->query('username');
        if (! $username) {
            return redirect()->route('login');
        }

        $profil = [
            'Nama Pengguna' => $username,
            'Email'         => strtolower($username) . '@coffeemist.id',
            'Peran'         => 'Admin Kedai',
            'Cabang'        => 'Coffeemist Jember',
            'Bergabung'     => '3 November 2026',
        ];

        $keahlian = ['Manajemen Stok', 'Barista', 'Pelayanan Pelanggan', 'Laporan Harian'];

        return view('profile', compact('username', 'profil', 'keahlian'));
    }
}
