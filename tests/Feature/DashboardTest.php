<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\Identity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dashboard_page_can_be_rendered_by_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard E-Court');
        $response->assertSee('Statistik Permohonan Harian');
        $response->assertSee('Distribusi Status');
        $response->assertSee('Antrean Permohonan Terbaru');
    }

    public function test_dashboard_guest_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_component_computes_stats_accurately(): void
    {
        Identity::create([
            'nama_lengkap' => 'Pemohon 1',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'tempat_lahir' => 'Jakarta',
            'nomor_kependudukan' => Crypt::encryptString('1234567890123456'),
            'nomor_telepon' => Crypt::encryptString('08123456789'),
            'email' => 'p1@example.com',
            'pekerjaan' => 'Swasta',
            'pendidikan' => 'S1',
            'status_perkawinan' => 'Belum Menikah',
            'agama' => 'Islam',
            'alamat' => 'Jl. Test No. 1',
            'status' => 'pending',
        ]);

        Identity::create([
            'nama_lengkap' => 'Pemohon 2',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1992-02-02',
            'tempat_lahir' => 'Bandung',
            'nomor_kependudukan' => Crypt::encryptString('6543210987654321'),
            'nomor_telepon' => Crypt::encryptString('08987654321'),
            'email' => 'p2@example.com',
            'pekerjaan' => 'PNS',
            'pendidikan' => 'S1',
            'status_perkawinan' => 'Menikah',
            'agama' => 'Islam',
            'alamat' => 'Jl. Test No. 2',
            'status' => 'rejected',
            'alasan_penolakan' => 'KTP buram',
            'rejected_at' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(Dashboard::class)
            ->assertSee('Pemohon 1')
            ->assertSee('Menunggu Verifikasi')
            ->assertSee('Permohonan Ditolak');
    }

    public function test_dashboard_handles_to_json_method_call_safely(): void
    {
        Livewire::actingAs($this->user)
            ->test(Dashboard::class)
            ->call('toJSON')
            ->assertSuccessful();

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\IdentityTotalCard::class)
            ->call('toJSON')
            ->assertSuccessful();
    }
}
