<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\EcourtAccount;
use App\Models\Identity;
use App\Service\HashId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class PermohonanSearchApiTest extends TestCase
{
    use RefreshDatabase;

    protected HashId $hashId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hashId = app(HashId::class);
    }

    protected function createSampleIdentity(array $overrides = [], bool $withBankAccount = true): Identity
    {
        $nik = '3201010101010001';
        $phone = '081234567890';

        $identity = Identity::create(array_merge([
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'tempat_lahir' => 'Jakarta',
            'nomor_kependudukan' => Crypt::encryptString($nik),
            'nomor_telepon' => Crypt::encryptString($phone),
            'email' => 'budi@example.com',
            'pekerjaan' => 'Wiraswasta',
            'pendidikan' => 'S1',
            'status_perkawinan' => 'Kawin',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10 Jakarta',
            'kewarganegaraan' => 'WNI',
            'status' => 'pending',
        ], $overrides));

        $identity->sensitive_identity_key()->create([
            'hash_nik' => hash_hmac('sha256', $nik, config('app.key')),
            'hash_nomor_telepon' => hash_hmac('sha256', $phone, config('app.key')),
        ]);

        if ($withBankAccount) {
            BankAccount::create([
                'identity_id' => $identity->id,
                'nama_bank' => 'BCA',
                'nomor_rekening' => Crypt::encryptString('1234567890'),
                'nama_akun' => 'Budi Santoso',
                'file_ktp' => 'ktp/sample.pdf',
            ]);
        }

        return $identity;
    }

    public function test_search_by_phone_via_query_param_when_verified(): void
    {
        $identity = $this->createSampleIdentity(['status' => 'verified']);
        EcourtAccount::create([
            'identity_id' => $identity->id,
            'username' => 'budi_ecourt',
            'password' => 'ecourt_secret_123',
        ]);

        $response = $this->getJson('/api/permohonan/search?nomor_telepon=081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Akun E-Court sudah dibuat oleh admin.',
                'data' => [
                    'identity_id' => $this->hashId->encode($identity->id),
                    'nama_lengkap' => 'Budi Santoso',
                    'status' => 'verified',
                    'username' => 'budi_ecourt',
                    'password' => 'ecourt_secret_123',
                ],
            ]);
    }

    public function test_search_by_phone_via_post_json_when_verified(): void
    {
        $identity = $this->createSampleIdentity(['status' => 'verified']);
        EcourtAccount::create([
            'identity_id' => $identity->id,
            'username' => 'budi_ecourt_post',
            'password' => 'ecourt_secret_post',
        ]);

        $response = $this->postJson('/api/permohonan/search', [
            'nomor_telepon' => '081234567890',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Akun E-Court sudah dibuat oleh admin.',
                'data' => [
                    'identity_id' => $this->hashId->encode($identity->id),
                    'nama_lengkap' => 'Budi Santoso',
                    'status' => 'verified',
                    'username' => 'budi_ecourt_post',
                    'password' => 'ecourt_secret_post',
                ],
            ]);
    }

    public function test_search_by_phone_via_route_parameter(): void
    {
        $identity = $this->createSampleIdentity(['status' => 'verified']);
        EcourtAccount::create([
            'identity_id' => $identity->id,
            'username' => 'budi_param',
            'password' => 'secret_param',
        ]);

        $response = $this->getJson('/api/permohonan/search/081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'username' => 'budi_param',
                ],
            ]);
    }

    public function test_search_by_phone_via_root_search_endpoint(): void
    {
        $identity = $this->createSampleIdentity(['status' => 'verified']);
        EcourtAccount::create([
            'identity_id' => $identity->id,
            'username' => 'budi_root',
            'password' => 'secret_root',
        ]);

        $response = $this->getJson('/api/search?nomor_telepon=081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'username' => 'budi_root',
                ],
            ]);
    }

    public function test_search_by_phone_when_pending(): void
    {
        $identity = $this->createSampleIdentity(['status' => 'pending']);

        $response = $this->getJson('/api/permohonan/search?nomor_telepon=081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'pending',
                'message' => 'Menunggu verifikasi admin.',
                'data' => [
                    'identity_id' => $this->hashId->encode($identity->id),
                    'nama_lengkap' => 'Budi Santoso',
                    'status' => 'pending',
                ],
            ]);
    }

    public function test_search_by_phone_when_rejected(): void
    {
        $identity = $this->createSampleIdentity([
            'status' => 'rejected',
            'alasan_penolakan' => 'Foto KTP tidak jelas.',
            'rejected_at' => now(),
        ]);

        $response = $this->getJson('/api/permohonan/search?nomor_telepon=081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'rejected',
                'message' => 'Permohonan ditolak oleh admin.',
                'data' => [
                    'identity_id' => $this->hashId->encode($identity->id),
                    'nama_lengkap' => 'Budi Santoso',
                    'status' => 'rejected',
                    'alasan_penolakan' => 'Foto KTP tidak jelas.',
                ],
            ]);
    }

    public function test_search_by_phone_when_incomplete_step_2(): void
    {
        $identity = $this->createSampleIdentity([], withBankAccount: false);

        $response = $this->getJson('/api/permohonan/search?nomor_telepon=081234567890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'incomplete',
                'message' => 'Permohonan belum lengkap. Silakan lengkapi data rekening bank dan KTP (Step 2).',
                'data' => [
                    'identity_id' => $this->hashId->encode($identity->id),
                    'nama_lengkap' => 'Budi Santoso',
                    'status' => 'incomplete',
                ],
            ]);
    }

    public function test_search_by_phone_normalizes_formatted_number(): void
    {
        $this->createSampleIdentity(['status' => 'pending']);

        // Search with international prefix and formatting
        $response = $this->getJson('/api/permohonan/search?nomor_telepon=+62 812-3456-7890');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'pending',
                'data' => [
                    'nama_lengkap' => 'Budi Santoso',
                ],
            ]);

        // Search with 62 prefix
        $response2 = $this->getJson('/api/permohonan/search?nomor_telepon=6281234567890');
        $response2->assertStatus(200);
    }

    public function test_search_by_phone_not_found(): void
    {
        $response = $this->getJson('/api/permohonan/search?nomor_telepon=089999999999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Akun dengan nomor telepon tersebut tidak ditemukan.',
            ]);
    }

    public function test_search_by_phone_empty_validation_error(): void
    {
        $response = $this->getJson('/api/permohonan/search');

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Nomor telepon tidak boleh kosong.',
            ]);
    }

    public function test_search_by_phone_excludes_soft_deleted_identity(): void
    {
        $identity = $this->createSampleIdentity();
        $identity->delete();

        $response = $this->getJson('/api/permohonan/search?nomor_telepon=081234567890');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Akun dengan nomor telepon tersebut tidak ditemukan.',
            ]);
    }
}
