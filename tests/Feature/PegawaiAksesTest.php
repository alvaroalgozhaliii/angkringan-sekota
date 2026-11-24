<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PegawaiAksesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pegawai_bisa_membuka_panel_pegawai(): void
    {
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->get('/pegawai/kasir')->assertOk()
            ->assertDontSee('Dashboard Pegawai')
            ->assertDontSee('Pesan Masuk')
            ->assertDontSee('Profil Saya');
        $this->actingAs($pegawai)->get('/pegawai')->assertNotFound();
        $this->actingAs($pegawai)->get('/pegawai/profil')->assertNotFound();
        $this->actingAs($pegawai)->get('/admin/pesan')->assertNotFound();
        $this->actingAs($pegawai)->get('/owner/dashboard')->assertForbidden();
    }

    public function test_halaman_kasir_memakai_grid_kartu_dan_modal_checkout(): void
    {
        $pegawai = $this->buatUser('pegawai');

        Produk::create([
            'nama' => 'Nasi Kucing',
            'kategori' => 'Makanan',
            'harga' => 12000,
            'stok' => 8,
            'is_best_seller' => true,
            'status' => 'tayang',
            'urutan' => 1,
        ]);

        Produk::create([
            'nama' => 'Es Teh',
            'kategori' => 'Minuman',
            'harga' => 5000,
            'stok' => 0,
            'status' => 'tayang',
            'urutan' => 2,
        ]);

        $response = $this->actingAs($pegawai)->get('/pegawai/kasir')->assertOk()
            ->assertSee('id="kasir-grid"', false)
            ->assertSee('id="cari-menu"', false)
            ->assertSee('id="checkout-modal"', false)
            ->assertSee('id="buka-checkout"', false)
            ->assertSee('data-nama="nasi kucing"', false)
            ->assertSee('data-nama="es teh"', false)
            ->assertSee('Terlaris')
            ->assertSee('Stok habis')
            ->assertSee('Menampilkan');

        $isi = $response->getContent();
        $this->assertLessThan(
            strpos($isi, 'data-nama="es teh"'),
            strpos($isi, 'data-nama="nasi kucing"')
        );
        $this->assertMatchesRegularExpression(
            '/data-nama="es teh"[\s\S]{0,700}?disabled/',
            $isi
        );
    }

    public function test_owner_bisa_membuka_dashboard_owner(): void
    {
        $owner = $this->buatUser('owner');

        $this->actingAs($owner)->get('/owner/dashboard')->assertOk()
            ->assertSee('Ringkasan kondisi Angkringan Sekota hari ini.')
            ->assertSee('Pantau pesanan');
        $this->actingAs($owner)->get('/pegawai/kasir')->assertForbidden();
    }

    public function test_pegawai_tidak_bisa_mengubah_profil_usaha(): void
    {
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->get('/admin/profil')->assertForbidden();
        $this->actingAs($pegawai)->put('/admin/profil/update', [])->assertForbidden();
    }

    public function test_log_aktivitas_memakai_box_dan_pagination_sendiri(): void
    {
        $pegawai = $this->buatUser('pegawai');

        ActivityLog::create([
            'user_nama' => $pegawai->name,
            'role' => 'pegawai',
            'modul' => 'Pesanan',
            'aksi' => 'Menyelesaikan pesanan',
            'data_sebelum' => ['status' => 'diproses'],
            'data_sesudah' => ['status' => 'selesai'],
            'created_at' => now(),
        ]);

        for ($i = 0; $i < 20; $i++) {
            ActivityLog::create([
                'user_nama' => $pegawai->name,
                'role' => 'pegawai',
                'modul' => 'Produk',
                'aksi' => 'Memperbarui stok '.$i,
                'created_at' => now()->subMinutes($i + 1),
            ]);
        }

        $this->actingAs($pegawai)->get('/pegawai/log')->assertOk()
            ->assertSee('class="log-filters"', false)
            ->assertSee('Cari log')
            ->assertSee('Bulan')
            ->assertSee('Tahun')
            ->assertSee('class="log-role"', false)
            ->assertSee('(Pegawai)')
            ->assertSee('class="log-summary"', false)
            ->assertSee('status: diproses menjadi selesai')
            ->assertSee('Detail')
            ->assertSee('class="log-box"', false)
            ->assertSee('class="pagination-bar"', false)
            ->assertSee('Menampilkan')
            ->assertDontSee('« Previous')
            ->assertDontSee('Showing');
    }

    public function test_log_aktivitas_bisa_difilter_role_modul_bulan_tahun_dan_pencarian(): void
    {
        $pegawai = $this->buatUser('pegawai');
        $owner = $this->buatUser('owner');

        ActivityLog::create([
            'user_nama' => $owner->name,
            'role' => 'owner',
            'modul' => 'Pesanan',
            'aksi' => 'Menyetujui pesanan',
            'data_sebelum' => ['status' => 'menunggu_persetujuan'],
            'data_sesudah' => ['status' => 'menunggu_pembayaran'],
            'created_at' => now()->setDate(2026, 11, 5),
        ]);

        ActivityLog::create([
            'user_nama' => $pegawai->name,
            'role' => 'pegawai',
            'modul' => 'Produk & Stok',
            'aksi' => 'Memperbarui stok',
            'created_at' => now()->setDate(2026, 10, 5),
        ]);

        $this->actingAs($pegawai)->get('/pegawai/log?role=owner&modul=Pesanan&bulan=11&tahun=2026&q=Menyetujui')->assertOk()
            ->assertSee('Menyetujui pesanan')
            ->assertSee('(Owner)')
            ->assertDontSee('Memperbarui stok');
    }

    public function test_helper_log_memetakan_role_dan_memadatkan_ringkasan(): void
    {
        $this->assertSame('Admin', (new ActivityLog(['role' => 'super_admin']))->roleLabel());
        $this->assertSame('Pegawai', (new ActivityLog(['role' => 'pegawai']))->roleLabel());
        $this->assertSame('Sistem', (new ActivityLog(['role' => 'sistem']))->roleLabel());
        $this->assertSame('Owner', (new ActivityLog(['role' => 'owner']))->roleLabel());

        $log = new ActivityLog([
            'data_sebelum' => ['status' => 'diproses', 'stok' => 10, 'catatan' => 'lama'],
            'data_sesudah' => ['status' => 'selesai', 'stok' => 9, 'catatan' => 'baru'],
        ]);

        $this->assertSame('status: diproses menjadi selesai, stok: 10 menjadi 9, +1', $log->ringkasanPerubahan(2, 120));
    }

    public function test_super_admin_bisa_membuka_dashboard_owner_dan_panel_pegawai(): void
    {
        $superAdmin = $this->buatUser('super_admin');

        $this->actingAs($superAdmin)->get('/owner/dashboard')->assertOk();
        $this->actingAs($superAdmin)->get('/pegawai/kasir')->assertOk();
        $this->actingAs($superAdmin)->get('/admin/profil')->assertOk();
    }

    private function buatUser(string $role): User
    {
        return User::create([
            'name' => 'User '.$role,
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => $role,
        ]);
    }
}
