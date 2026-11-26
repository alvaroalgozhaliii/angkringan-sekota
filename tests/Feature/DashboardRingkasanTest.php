<?php

namespace Tests\Feature;

use App\Models\Transaksi;
use App\Models\User;
use App\Support\DashboardRingkasan;
use App\Support\OrderStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardRingkasanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_panel_ringkasan_menampilkan_angka_dari_transaksi(): void
    {
        $baseline = DashboardRingkasan::hitung();
        $owner = $this->buatUser('owner');
        $pegawai = $this->buatUser('pegawai');

        $this->buatTransaksi('RINGKASAN-001', 50000, OrderStatus::DIPROSES, $owner->id);
        $this->buatTransaksi('RINGKASAN-002', 25000, OrderStatus::SELESAI);
        $this->buatTransaksi('RINGKASAN-003', 10000, OrderStatus::MENUNGGU_VERIFIKASI);
        $this->buatTransaksi('RINGKASAN-004', 99999, OrderStatus::DIBATALKAN, $owner->id);
        $kemarin = $this->buatTransaksi('RINGKASAN-005', 30000, OrderStatus::SELESAI, $owner->id);
        Transaksi::whereKey($kemarin->id)->update(['created_at' => now()->subDay()]);

        $aktual = DashboardRingkasan::hitung();
        $this->assertSame((int) $baseline['uangMasukHariIni'] + 85000, (int) $aktual['uangMasukHariIni']);
        $this->assertSame($baseline['pengunjungHariIni'] + 2, $aktual['pengunjungHariIni']);
        $this->assertSame($baseline['pengunjungTotal'] + 3, $aktual['pengunjungTotal']);
        $this->assertSame((int) $baseline['pemasukanTotal'] + 115000, (int) $aktual['pemasukanTotal']);
        $this->assertSame($baseline['antrian']['menunggu_konfirmasi'] + 1, $aktual['antrian']['menunggu_konfirmasi']);
        $this->assertSame($baseline['antrian']['dalam_proses'] + 1, $aktual['antrian']['dalam_proses']);

        $rupiahHariIni = 'Rp'.number_format($aktual['uangMasukHariIni'], 0, ',', '.');
        $rupiahTotal = 'Rp'.number_format($aktual['pemasukanTotal'], 0, ',', '.');

        $this->actingAs($owner)->get('/owner/dashboard')->assertOk()
            ->assertSee('Uang masuk hari ini')
            ->assertSee('Pengunjung hari ini')
            ->assertSee('Antrian saat ini')
            ->assertSee('Pengunjung total')
            ->assertSee('Pemasukan total')
            ->assertSee($rupiahHariIni)
            ->assertSee($rupiahTotal)
            ->assertSee('grup=dalam_proses', false);

        $this->actingAs($pegawai)->get('/pegawai/dashboard')->assertOk()
            ->assertSee('Uang masuk hari ini')
            ->assertSee('Antrian saat ini')
            ->assertSee($rupiahHariIni)
            ->assertSee('grup=menunggu_konfirmasi', false);
    }

    public function test_tautan_antrian_membuka_daftar_pesanan_tersaring(): void
    {
        $owner = $this->buatUser('owner');
        $pegawai = $this->buatUser('pegawai');

        $diproses = $this->buatTransaksi('RINGKASAN-FILTER-001', 50000, OrderStatus::DIPROSES, $owner->id);
        $menunggu = $this->buatTransaksi('RINGKASAN-FILTER-002', 10000, OrderStatus::MENUNGGU_VERIFIKASI);

        $this->actingAs($owner)->get('/admin/pesanan?grup=dalam_proses')->assertOk()
            ->assertSee('Menampilkan antrian')
            ->assertSee($diproses->nomor_transaksi)
            ->assertDontSee($menunggu->nomor_transaksi);

        $this->actingAs($pegawai)->get('/pegawai/pesanan?grup=menunggu_konfirmasi')->assertOk()
            ->assertSee($menunggu->nomor_transaksi)
            ->assertDontSee($diproses->nomor_transaksi);
    }

    public function test_tautan_data_pelanggan_memiliki_halaman(): void
    {
        $owner = $this->buatUser('owner');

        $this->actingAs($owner)->get('/admin/pelanggan')->assertOk()
            ->assertSee('Data pelanggan')
            ->assertSee('Jumlah pesanan');
    }

    private function buatTransaksi(string $nomor, int $total, string $status, ?int $verifiedBy = null): Transaksi
    {
        return Transaksi::create([
            'nomor_transaksi' => $nomor,
            'nama_kasir' => 'Kasir Uji',
            'sumber' => 'kasir',
            'total_harga' => $total,
            'status' => $status,
            'verified_by' => $verifiedBy,
            'verified_at' => $verifiedBy ? now() : null,
        ]);
    }

    private function buatUser(string $role): User
    {
        return User::create([
            'name' => 'User '.$role.' Ringkasan',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => $role,
        ]);
    }
}
