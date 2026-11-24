<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\ProfilUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_form_admin_banner_hanya_memuat_gambar_urutan_dan_status(): void
    {
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->get('/pegawai/konten')->assertOk()
            ->assertSee('name="gambar"', false)
            ->assertSee('name="urutan"', false)
            ->assertSee('name="is_aktif"', false)
            ->assertDontSee('name="judul"', false)
            ->assertDontSee('name="deskripsi"', false)
            ->assertDontSee('name="link"', false);
    }

    public function test_pegawai_bisa_menambah_banner_dengan_gambar_saja(): void
    {
        Storage::fake('public');
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->post('/pegawai/banner', [
            'gambar' => UploadedFile::fake()->image('promo.jpg', 1200, 458),
            'urutan' => 3,
            'is_aktif' => 1,
        ])->assertRedirect();

        $banner = Banner::latest('id')->firstOrFail();

        $this->assertStringStartsWith('banner/', $banner->gambar);
        $this->assertSame(3, $banner->urutan);
        $this->assertTrue($banner->is_aktif);
        Storage::disk('public')->assertExists($banner->gambar);
        $this->assertDatabaseHas('activity_logs', [
            'modul' => 'Tampilan Web',
            'aksi' => 'Tambah banner',
            'entitas' => 'banner',
            'entitas_id' => $banner->id,
        ]);
        $this->assertSame(
            $banner->gambar,
            ActivityLog::where('aksi', 'Tambah banner')->latest('id')->firstOrFail()->data_sesudah['gambar']
        );
    }

    public function test_update_banner_tanpa_ganti_gambar_menyimpan_gambar_lama(): void
    {
        $pegawai = $this->buatUser('pegawai');
        $banner = Banner::create([
            'gambar' => 'banner/promo-lama.jpg',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $this->actingAs($pegawai)->put('/pegawai/banner/'.$banner->id, [
            'urutan' => 5,
            'is_aktif' => 1,
        ])->assertRedirect();

        $banner->refresh();

        $this->assertSame('banner/promo-lama.jpg', $banner->gambar);
        $this->assertSame(5, $banner->urutan);
        $this->assertTrue($banner->is_aktif);
    }

    public function test_beranda_menampilkan_gambar_banner_tanpa_overlay_teks(): void
    {
        ProfilUsaha::getSingleton()->update(['tampilkan_banner' => true]);
        Banner::create([
            'gambar' => 'banner/promo.jpg',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $this->get('/')->assertOk()
            ->assertSee('<section class="promo-banner-section"', false)
            ->assertSee('id="promoBannerWrap"', false)
            ->assertSee('storage/banner/promo.jpg', false)
            ->assertSee('Banner promo Angkringan Sekota 1')
            ->assertDontSee('promo-banner-overlay', false)
            ->assertDontSee('promo-banner-title', false)
            ->assertDontSee('Lihat Detail')
            ->assertDontSee('Buka tiap hari 17.00 - 23.00 WIB', false);
    }

    public function test_bagian_banner_hilang_saat_tidak_ada_banner_aktif(): void
    {
        ProfilUsaha::getSingleton()->update(['tampilkan_banner' => true]);
        Banner::create([
            'gambar' => 'banner/nonaktif.jpg',
            'urutan' => 1,
            'is_aktif' => false,
        ]);

        $this->get('/')->assertOk()
            ->assertDontSee('<section class="promo-banner-section"', false)
            ->assertDontSee('Buka tiap hari 17.00 - 23.00 WIB', false);
    }

    public function test_bagian_banner_hilang_saat_toggle_dimatikan(): void
    {
        ProfilUsaha::getSingleton()->update(['tampilkan_banner' => false]);
        Banner::create([
            'gambar' => 'banner/promo.jpg',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $this->get('/')->assertOk()
            ->assertDontSee('<section class="promo-banner-section"', false);
    }

    public function test_kontrol_carousel_hanya_tampil_untuk_lebih_dari_satu_banner(): void
    {
        ProfilUsaha::getSingleton()->update(['tampilkan_banner' => true]);

        Banner::create([
            'gambar' => 'banner/promo-1.jpg',
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        $this->get('/')->assertOk()
            ->assertDontSee('id="bannerPrevBtn"', false)
            ->assertDontSee('id="bannerPauseBtn"', false);

        Banner::create([
            'gambar' => 'banner/promo-2.jpg',
            'urutan' => 2,
            'is_aktif' => true,
        ]);

        $this->get('/')->assertOk()
            ->assertSee('id="bannerPrevBtn"', false)
            ->assertSee('id="bannerNextBtn"', false)
            ->assertSee('id="bannerPauseBtn"', false)
            ->assertSee('aria-label="Jeda banner otomatis"', false)
            ->assertSee('data-autoplay-delay="5000"', false)
            ->assertSee('aria-label="Tampilkan banner 1"', false)
            ->assertSee('aria-label="Tampilkan banner 2"', false);
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
