<?php

namespace Tests\Feature;

use App\Events\IdentityRejectedEvent;
use App\Livewire\BankAccountStepWizard;
use App\Livewire\FormECourtAccount;
use App\Livewire\IdentityDetailPage;
use App\Livewire\IdentityTotalCard;
use App\Livewire\TimeLineWizard;
use App\Models\BankAccount;
use App\Models\Identity;
use App\Models\User;
use App\Service\HashId;
use App\Service\HashRouteId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class IdentityRejectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected HashId $hashId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->hashId = app(HashId::class);
        Storage::fake('public');
    }

    protected function createSampleIdentity(array $overrides = []): Identity
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

        BankAccount::create([
            'identity_id' => $identity->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => Crypt::encryptString('1234567890'),
            'nama_akun' => 'Budi Santoso',
            'file_ktp' => 'ktp/sample.pdf',
        ]);

        return $identity;
    }

    public function test_admin_can_reject_identity_with_reason(): void
    {
        Event::fake([IdentityRejectedEvent::class]);

        $identity = $this->createSampleIdentity();
        $hashRouteId = new HashRouteId($this->hashId, $this->hashId->encode($identity->id));

        Livewire::actingAs($this->admin)
            ->test(IdentityDetailPage::class, ['hash_id' => $hashRouteId])
            ->set('alasan_penolakan', 'Foto KTP buram dan tidak terbaca.')
            ->call('rejectApplication')
            ->assertHasNoErrors()
            ->assertDispatched('close-reject-modal');

        $identity->refresh();

        $this->assertEquals('rejected', $identity->status);
        $this->assertEquals('Foto KTP buram dan tidak terbaca.', $identity->alasan_penolakan);
        $this->assertNotNull($identity->rejected_at);
        $this->assertTrue($identity->isRejected());

        Event::assertDispatched(IdentityRejectedEvent::class, function ($event) use ($identity) {
            return $event->identity->id === $identity->id &&
                $event->alasan_penolakan === 'Foto KTP buram dan tidak terbaca.';
        });
    }

    public function test_admin_cannot_reject_with_empty_reason(): void
    {
        $identity = $this->createSampleIdentity();
        $hashRouteId = new HashRouteId($this->hashId, $this->hashId->encode($identity->id));

        Livewire::actingAs($this->admin)
            ->test(IdentityDetailPage::class, ['hash_id' => $hashRouteId])
            ->set('alasan_penolakan', '')
            ->call('rejectApplication')
            ->assertHasErrors(['alasan_penolakan' => 'required']);

        $identity->refresh();
        $this->assertEquals('pending', $identity->status);
    }

    public function test_admin_can_cancel_rejection(): void
    {
        $identity = $this->createSampleIdentity([
            'status' => 'rejected',
            'alasan_penolakan' => 'Alamat tidak lengkap.',
            'rejected_at' => now(),
        ]);
        $hashRouteId = new HashRouteId($this->hashId, $this->hashId->encode($identity->id));

        Livewire::actingAs($this->admin)
            ->test(IdentityDetailPage::class, ['hash_id' => $hashRouteId])
            ->call('cancelRejection')
            ->assertHasNoErrors();

        $identity->refresh();

        $this->assertEquals('pending', $identity->status);
        $this->assertNull($identity->alasan_penolakan);
        $this->assertNull($identity->rejected_at);
        $this->assertTrue($identity->isPending());
    }

    public function test_timeline_displays_rejection_status_and_reason(): void
    {
        $identity = $this->createSampleIdentity([
            'status' => 'rejected',
            'alasan_penolakan' => 'Foto KTP tidak terbaca dengan jelas.',
            'rejected_at' => now(),
        ]);
        $hashRouteId = new HashRouteId($this->hashId, $this->hashId->encode($identity->id));

        Livewire::test(TimeLineWizard::class, ['hash_id' => $hashRouteId])
            ->assertSee('Permohonan Ditolak')
            ->assertSee('Foto KTP tidak terbaca dengan jelas.');
    }

    public function test_api_status_returns_rejected_with_reason(): void
    {
        $identity = $this->createSampleIdentity([
            'status' => 'rejected',
            'alasan_penolakan' => 'Nomor rekening tidak sesuai dengan nama KTP.',
            'rejected_at' => now(),
        ]);

        $response = $this->getJson('/api/permohonan/status/'.$this->hashId->encode($identity->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'rejected',
                'message' => 'Permohonan ditolak oleh admin.',
                'data' => [
                    'alasan_penolakan' => 'Nomor rekening tidak sesuai dengan nama KTP.',
                ],
            ]);
    }

    public function test_applicant_resubmission_resets_rejection_to_pending(): void
    {
        $identity = $this->createSampleIdentity([
            'status' => 'rejected',
            'alasan_penolakan' => 'File KTP terpotong.',
            'rejected_at' => now(),
        ]);

        $encodedId = $this->hashId->encode($identity->id);
        $hashRouteId = new HashRouteId($this->hashId, $encodedId);

        // Test editing in Step 2 (upload new file / resave)
        $newFile = UploadedFile::fake()->create('ktp_baru.pdf', 500, 'application/pdf');

        Livewire::test(BankAccountStepWizard::class, ['hash_id' => $hashRouteId])
            ->set('nama_bank', 'BRI')
            ->set('nomor_rekening', '9876543210')
            ->set('nama_akun', 'Budi Santoso')
            ->set('file_ktp', $newFile)
            ->call('save', $this->hashId)
            ->assertHasNoErrors()
            ->assertRedirect("/timeline/{$encodedId}");

        $identity->refresh();

        $this->assertEquals('pending', $identity->status);
        $this->assertNull($identity->alasan_penolakan);
        $this->assertNull($identity->rejected_at);
        $this->assertTrue($identity->isPending());
    }

    public function test_issuing_ecourt_account_sets_status_to_verified(): void
    {
        $identity = $this->createSampleIdentity();
        $encodedId = $this->hashId->encode($identity->id);

        Livewire::actingAs($this->admin)
            ->test(FormECourtAccount::class, ['identity_id' => $encodedId, 'hash' => $this->hashId])
            ->set('ecourt_account.username', 'budiecourtx')
            ->set('ecourt_account.password', 'rahasia123')
            ->call('save')
            ->assertHasNoErrors();

        $identity->refresh();

        $this->assertEquals('verified', $identity->status);
        $this->assertTrue($identity->isVerified());
        $this->assertNotNull($identity->ecourt_account);
    }

    public function test_identity_total_card_includes_rejected_count(): void
    {
        $this->createSampleIdentity(['status' => 'pending']);
        $this->createSampleIdentity(['status' => 'rejected', 'email' => 'rejected@example.com']);

        $verified = $this->createSampleIdentity(['status' => 'verified', 'email' => 'verified@example.com']);
        $verified->ecourt_account()->create([
            'username' => 'verifieduser',
            'password' => 'secret123',
        ]);

        Livewire::actingAs($this->admin)
            ->test(IdentityTotalCard::class)
            ->assertSet('total', 3)
            ->assertSet('belum_verifikasi', 1)
            ->assertSet('ditolak', 1)
            ->assertSet('sudah_verifikasi', 1);
    }
}
