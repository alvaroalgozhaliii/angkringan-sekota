<?php

namespace Tests\Feature;

use App\Models\PengaturanTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Support\OrderStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlurPesananTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_pilihan_qris_tampil_di_halaman_menu(): void
    {
        PengaturanTransaksi::current()->update([
            'tunai_aktif' => true,
            'qris_aktif' => true,
            'qris_gambar' => 'pengaturan/qris.png',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Cash / Tunai (bayar di kasir)')
            ->assertSee('QRIS (unggah bukti bayar)')
            ->assertSee('Pilih Foto Bukti')
            ->assertSee('Klik untuk memperbesar QRIS')
            ->assertSee('storage/pengaturan/qris.png')
            ->assertSee('id="orderSuccessPanel"', false)
            ->assertSee('id="btnCekStatusPesanan"', false)
            ->assertSee('Cek Status Pesanan');
    }

    public function test_pesanan_qris_diverifikasi_dan_stok_dipotong_sekali(): void
    {
        $pengaturan = PengaturanTransaksi::current();
        $pengaturan->update([
            'tunai_aktif' => true,
            'qris_aktif' => true,
            'qris_nama_merchant' => 'ANGKRINGAN SEKOTA',
            'qris_gambar' => 'pengaturan/qris.png',
            'pengiriman_aktif' => false,
            'ambil_di_toko_aktif' => true,
            'batas_bayar_menit' => 30,
        ]);

        $produk = Produk::create([
            'nama' => 'Nasi Pecel Test',
            'kategori' => 'Makanan',
            'harga' => 12000,
            'stok' => 10,
            'status' => 'tayang',
        ]);

        $response = $this->post('/pesanan', [
            'nama_pengirim' => 'Pelanggan Test',
            'nomor_wa' => '0812-3456-7890',
            'items' => json_encode([['id' => $produk->id, 'qty' => 2]]),
            'metode_pembayaran' => 'qris',
            'metode_pengambilan' => 'ambil_di_toko',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 128, 'image/jpeg'),
        ], ['Accept' => 'application/json']);

        $response->assertOk()->assertJsonPath('success', true);
        $nomor = $response->json('nomor_transaksi');
        $transaksi = Transaksi::where('nomor_transaksi', $nomor)->firstOrFail();

        $this->assertSame(OrderStatus::MENUNGGU_VERIFIKASI, $transaksi->status);
        $this->assertSame('website', $transaksi->sumber);
        $this->assertNotNull($transaksi->batas_bayar_at);
        $this->assertNull($transaksi->disetujui_oleh);
        $this->assertSame(2, (int) $transaksi->details()->sum('jumlah'));
        $this->assertSame(10, $produk->fresh()->stok);
        Storage::disk('public')->assertExists($transaksi->bukti_pembayaran);

        $this->get('/cek-pesanan?nomor='.$nomor)
            ->assertOk()
            ->assertSee('Lacak pesanan Anda')
            ->assertSee('class="stepper"', false)
            ->assertSee('Bukti bayar terkirim')
            ->assertSee('imageLightbox', false)
            ->assertDontSee('Kirim Bukti Bayar');

        $pegawai = User::create([
            'name' => 'Pegawai Test',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'pegawai',
        ]);

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/verifikasi-qris', ['keputusan' => 'valid'])
            ->assertRedirect();

        $transaksi->refresh();
        $this->assertSame(OrderStatus::DIPROSES, $transaksi->status);
        $this->assertNotNull($transaksi->disetujui_oleh);
        $this->assertTrue($transaksi->stok_dikurangi);
        $this->assertSame(8, $produk->fresh()->stok);

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/tolak', ['alasan' => 'Menu habis, silakan pesan menu lain.'])
            ->assertRedirect();

        $transaksi->refresh();
        $this->assertSame(OrderStatus::DIBATALKAN, $transaksi->status);
        $this->assertFalse($transaksi->stok_dikurangi);
        $this->assertSame(10, $produk->fresh()->stok);
    }

    public function test_pegawai_validasi_langsung_memproses_dan_menyerahkan(): void
    {
        PengaturanTransaksi::current()->update([
            'qris_aktif' => true,
            'qris_gambar' => 'pengaturan/qris.png',
            'pengiriman_aktif' => false,
            'ambil_di_toko_aktif' => true,
        ]);

        $produk = Produk::create([
            'nama' => 'Nasi Uduk Test',
            'kategori' => 'Makanan',
            'harga' => 15000,
            'stok' => 5,
            'status' => 'tayang',
        ]);

        $response = $this->post('/pesanan', [
            'nama_pengirim' => 'Pelanggan Tahap',
            'nomor_wa' => '0812-1111-2222',
            'items' => json_encode([['id' => $produk->id, 'qty' => 2]]),
            'metode_pembayaran' => 'qris',
            'metode_pengambilan' => 'ambil_di_toko',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 128, 'image/jpeg'),
        ], ['Accept' => 'application/json']);

        $transaksi = Transaksi::where('nomor_transaksi', $response->json('nomor_transaksi'))->firstOrFail();
        $pegawai = User::create([
            'name' => 'Pegawai Tahap',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'pegawai',
        ]);

        $this->actingAs($pegawai)
            ->get('/pegawai/pesanan/'.$transaksi->id)
            ->assertOk()
            ->assertSee('Pemrosesan pesanan')
            ->assertSee('Penyerahan ke pengunjung')
            ->assertDontSee('Penerimaan pesanan')
            ->assertDontSee('Terima pesanan')
            ->assertDontSee('Proses pesanan');

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/verifikasi-qris', ['keputusan' => 'valid'])
            ->assertRedirect();

        $transaksi->refresh();
        $this->assertSame(OrderStatus::DIPROSES, $transaksi->status);
        $this->assertSame($pegawai->id, (int) $transaksi->disetujui_oleh);
        $this->assertSame(3, $produk->fresh()->stok);

        $this->get('/cek-pesanan?nomor='.$transaksi->nomor_transaksi)
            ->assertOk()
            ->assertSee('Pemrosesan pesanan')
            ->assertSee('Penyerahan ke pengunjung')
            ->assertDontSee('Penerimaan pesanan');

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/serahkan')
            ->assertRedirect();

        $transaksi->refresh();
        $this->assertSame(OrderStatus::SELESAI, $transaksi->status);

        $this->get('/cek-pesanan?nomor='.$transaksi->nomor_transaksi)
            ->assertOk()
            ->assertSee('Pesanan selesai diserahkan');
    }

    public function test_pesanan_tunai_kurangi_stok_setelah_kasir_konfirmasi(): void
    {
        $pengaturan = PengaturanTransaksi::current();
        $pengaturan->update([
            'tunai_aktif' => true,
            'qris_aktif' => false,
            'pengiriman_aktif' => false,
            'ambil_di_toko_aktif' => true,
        ]);

        $produk = Produk::create([
            'nama' => 'Es Teh Test',
            'kategori' => 'Minuman',
            'harga' => 5000,
            'stok' => 4,
            'status' => 'tayang',
        ]);

        $response = $this->postJson('/pesanan', [
            'nama_pengirim' => 'Pelanggan Tunai',
            'nomor_wa' => '081298765432',
            'items' => json_encode([['id' => $produk->id, 'qty' => 1]]),
            'metode_pembayaran' => 'tunai',
            'metode_pengambilan' => 'ambil_di_toko',
        ]);

        $response->assertOk();
        $transaksi = Transaksi::where('nomor_transaksi', $response->json('nomor_transaksi'))->firstOrFail();
        $this->assertSame(OrderStatus::MENUNGGU_PEMBAYARAN, $transaksi->status);
        $this->assertNull($transaksi->disetujui_oleh);
        $this->assertSame(4, $produk->fresh()->stok);

        $pegawai = User::create([
            'name' => 'Kasir Test',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'pegawai',
        ]);

        $this->get('/cek-pesanan?nomor='.$transaksi->nomor_transaksi)
            ->assertOk()
            ->assertSee('Pembayaran Tunai')
            ->assertSee('Riwayat Status');

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/konfirmasi-tunai', ['uang_diterima' => 5000])
            ->assertRedirect();

        $transaksi->refresh();
        $this->assertSame(3, $produk->fresh()->stok);
        $this->assertSame(OrderStatus::DIPROSES, $transaksi->status);
        $this->assertSame($pegawai->id, (int) $transaksi->disetujui_oleh);

        $this->actingAs($pegawai)
            ->post('/pegawai/pesanan/'.$transaksi->id.'/serahkan')
            ->assertRedirect();

        $this->assertSame(OrderStatus::SELESAI, $transaksi->fresh()->status);
    }

    public function test_kasir_mencatat_pesanan_langsung_dan_memotong_stok(): void
    {
        $produk = Produk::create([
            'nama' => 'Kopi Test',
            'kategori' => 'Minuman',
            'harga' => 8000,
            'stok' => 6,
            'status' => 'tayang',
        ]);

        $pegawai = User::create([
            'name' => 'Kasir Langsung',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'pegawai',
        ]);

        $this->actingAs($pegawai)
            ->post('/pegawai/kasir', [
                'nama_pelanggan' => 'Pelanggan Tunai',
                'items' => json_encode([['id' => $produk->id, 'qty' => 2]]),
                'metode_pembayaran' => 'tunai',
                'uang_diterima' => 20000,
            ])->assertRedirect();

        $transaksi = Transaksi::latest('id')->firstOrFail();
        $this->assertSame('kasir', $transaksi->sumber);
        $this->assertSame(OrderStatus::DIPROSES, $transaksi->status);
        $this->assertNotNull($transaksi->disetujui_oleh);
        $this->assertTrue($transaksi->stok_dikurangi);
        $this->assertSame(4, $produk->fresh()->stok);
        $this->assertEquals(4000, $transaksi->kembalian);
    }

    public function test_owner_dapat_menginput_qris_lewat_pengaturan_transaksi(): void
    {
        $owner = User::create([
            'name' => 'Owner QRIS',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $this->actingAs($owner)
            ->put('/admin/pengaturan/transaksi', [
                'tunai_aktif' => 1,
                'qris_aktif' => 1,
                'qris_nama_merchant' => 'ANGKRINGAN TEST',
                'qris_gambar' => UploadedFile::fake()->image('qris.png', 120, 120),
                'batas_bayar_menit' => 20,
            ])->assertRedirect();

        $pengaturan = PengaturanTransaksi::current();
        $this->assertSame('ANGKRINGAN TEST', $pengaturan->qris_nama_merchant);
        $this->assertSame(20, (int) $pengaturan->batas_bayar_menit);
        Storage::disk('public')->assertExists($pengaturan->qris_gambar);
    }
}
