<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PegawaiAksesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pegawai_bisa_membuka_panel_pegawai(): void
    {
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->get('/pegawai')->assertOk();
        $this->actingAs($pegawai)->get('/pegawai/kasir')->assertOk();
        $this->actingAs($pegawai)->get('/pegawai/kategori')->assertNotFound();
        $this->actingAs($pegawai)->get('/admin/pesan')->assertOk();
    }

    public function test_owner_tidak_bisa_membuka_panel_pegawai(): void
    {
        $owner = $this->buatUser('owner');

        $this->actingAs($owner)->get('/pegawai')->assertForbidden();
    }

    public function test_pegawai_tidak_bisa_mengubah_profil_usaha(): void
    {
        $pegawai = $this->buatUser('pegawai');

        $this->actingAs($pegawai)->get('/admin/profil')->assertForbidden();
        $this->actingAs($pegawai)->put('/admin/profil/update', [])->assertForbidden();
    }

    public function test_super_admin_bisa_membuka_panel_pegawai_dan_profil_usaha(): void
    {
        $superAdmin = $this->buatUser('super_admin');

        $this->actingAs($superAdmin)->get('/pegawai')->assertOk();
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
