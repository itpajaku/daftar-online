<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Identity;
use App\Models\SensitiveIdentityKey;
use App\Service\HashId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PermohonanController extends Controller
{
    public function step1(Request $request, HashId $hashId)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|max:255',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|max:255',
            'nomor_kependudukan' => 'required|numeric|digits_between:1,20',
            'nomor_telepon' => 'required|numeric|digits_between:1,20',
            'email' => 'required|email|max:255',
            'pekerjaan' => 'required|max:255',
            'pendidikan' => 'required|max:255',
            'status_perkawinan' => 'required|max:255',
            'agama' => 'required|max:255',
            'alamat' => 'required|max:512',
        ]);

        $hashed_nik = hash_hmac('sha256', $validated['nomor_kependudukan'], config('app.key'));
        $hashed_nomor_telepon = hash_hmac('sha256', $validated['nomor_telepon'], config('app.key'));

        try {
            $exists = SensitiveIdentityKey::where(function ($q) use ($hashed_nik, $hashed_nomor_telepon) {
                $q->where('hash_nik', $hashed_nik)
                    ->orWhere('hash_nomor_telepon', $hashed_nomor_telepon);
            })->whereHas('identity', function ($q) {
                $q->whereNull('deleted_at');
            })->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor Kependudukan Atau Nomor Telepon Sudah Terdaftar',
                ], 422);
            }

            DB::beginTransaction();

            $data = $validated;
            $data['nomor_kependudukan'] = Crypt::encryptString($validated['nomor_kependudukan']);
            $data['nomor_telepon'] = Crypt::encryptString($validated['nomor_telepon']);

            $identity = Identity::create($data);
            $identity->sensitive_identity_key()->create([
                'hash_nik' => $hashed_nik,
                'hash_nomor_telepon' => $hashed_nomor_telepon,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data identitas berhasil disimpan.',
                'data' => [
                    'identity_id' => $hashId->encode($identity->id),
                ],
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function step2(Request $request, $identity_id, HashId $hashId)
    {
        $decoded_id = $hashId->decodeFirst($identity_id);
        if (! $decoded_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Identitas tidak valid.',
            ], 404);
        }

        $identity = Identity::find($decoded_id);
        if (! $identity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identitas tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
            'nama_akun' => 'required',
            'file_ktp' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $file_ktp = $request->file('file_ktp')->storeAs(
                'ktp',
                'ktp-'.$identity_id.'.pdf',
                'public'
            );

            $data = [
                'nama_bank' => $validated['nama_bank'],
                'nomor_rekening' => Crypt::encryptString($validated['nomor_rekening']),
                'nama_akun' => $validated['nama_akun'],
                'file_ktp' => $file_ktp,
            ];

            if ($identity->bank_account) {
                $identity->bank_account()->update($data);
                $bank_account = $identity->bank_account;
            } else {
                $bank_account = $identity->bank_account()->create($data);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data rekening bank dan KTP berhasil disimpan.',
                'data' => [
                    'bank_account_id' => $hashId->encode($bank_account->id),
                ],
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data rekening.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function status($identity_id, HashId $hashId)
    {
        $decoded_id = $hashId->decodeFirst($identity_id);
        if (! $decoded_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Identitas tidak valid.',
            ], 404);
        }

        $identity = Identity::with('ecourt_account')->find($decoded_id);
        if (! $identity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identitas tidak ditemukan.',
            ], 404);
        }

        if ($identity->ecourt_account) {
            return response()->json([
                'status' => 'success',
                'message' => 'Akun E-Court sudah dibuat oleh admin.',
                'data' => [
                    'username' => $identity->ecourt_account->username,
                    'password' => $identity->ecourt_account->password,
                ],
            ]);
        }

        if ($identity->isRejected()) {
            return response()->json([
                'status' => 'rejected',
                'message' => 'Permohonan ditolak oleh admin.',
                'data' => [
                    'alasan_penolakan' => $identity->alasan_penolakan,
                    'rejected_at' => $identity->rejected_at ? $identity->rejected_at->toISOString() : null,
                ],
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Menunggu verifikasi admin.',
            'data' => null,
        ]);
    }

    public function show($identity_id, HashId $hashId)
    {
        $decoded_id = $hashId->decodeFirst($identity_id);
        if (! $decoded_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Identitas tidak valid.',
            ], 404);
        }

        $identity = Identity::with('bank_account')->find($decoded_id);
        if (! $identity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identitas tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data identitas berhasil diambil.',
            'data' => [
                'id' => $identity_id,
                'nama_lengkap' => $identity->nama_lengkap,
                'jenis_kelamin' => $identity->jenis_kelamin,
                'tanggal_lahir' => $identity->tanggal_lahir ? $identity->tanggal_lahir->format('Y-m-d') : null,
                'tempat_lahir' => $identity->tempat_lahir,
                'nomor_kependudukan' => $identity->nomor_kependudukan_original,
                'nomor_telepon' => $identity->nomor_telepon_original,
                'email' => $identity->email,
                'pekerjaan' => $identity->pekerjaan,
                'pendidikan' => $identity->pendidikan,
                'status_perkawinan' => $identity->status_perkawinan,
                'agama' => $identity->agama,
                'alamat' => $identity->alamat,
                'bank_account' => $identity->bank_account ? [
                    'id' => $hashId->encode($identity->bank_account->id),
                    'nama_bank' => $identity->bank_account->nama_bank,
                    'nomor_rekening' => $identity->bank_account->nomor_rekening,
                    'nama_akun' => $identity->bank_account->nama_akun,
                    'file_ktp_url' => $identity->bank_account->file_ktp ? asset('storage/'.$identity->bank_account->file_ktp) : null,
                ] : null,
            ],
        ]);
    }

    public function search(Request $request, HashId $hashId, $nomor_telepon = null)
    {
        $phoneInput = $nomor_telepon
            ?? $request->input('nomor_telepon')
            ?? $request->query('nomor_telepon')
            ?? $request->input('phone')
            ?? $request->query('phone')
            ?? $request->input('no_hp')
            ?? $request->query('no_hp');

        if (! $phoneInput) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor telepon tidak boleh kosong.',
            ], 422);
        }

        $phoneStr = trim((string) $phoneInput);
        $cleaned = preg_replace('/[^0-9]/', '', $phoneStr);

        $candidates = array_filter(array_unique([
            $phoneStr,
            $cleaned,
        ]));

        if (str_starts_with($cleaned, '62')) {
            $candidates[] = '0'.substr($cleaned, 2);
            $candidates[] = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '0')) {
            $candidates[] = '62'.substr($cleaned, 1);
            $candidates[] = substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $candidates[] = '0'.$cleaned;
            $candidates[] = '62'.$cleaned;
        }

        $hashes = array_unique(array_map(function ($phone) {
            return hash_hmac('sha256', $phone, config('app.key'));
        }, array_filter($candidates)));

        $sensitiveKey = SensitiveIdentityKey::whereIn('hash_nomor_telepon', $hashes)
            ->whereHas('identity', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->with(['identity.bank_account', 'identity.ecourt_account'])
            ->latest('id')
            ->first();

        if (! $sensitiveKey || ! $sensitiveKey->identity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun dengan nomor telepon tersebut tidak ditemukan.',
            ], 404);
        }

        $identity = $sensitiveKey->identity;
        $hashedId = $hashId->encode($identity->id);

        if (! $identity->bank_account) {
            return response()->json([
                'status' => 'incomplete',
                'message' => 'Permohonan belum lengkap. Silakan lengkapi data rekening bank dan KTP (Step 2).',
                'data' => [
                    'identity_id' => $hashedId,
                    'nama_lengkap' => $identity->nama_lengkap,
                    'status' => 'incomplete',
                    'step_2_url' => url('/step-2/'.$hashedId),
                ],
            ]);
        }

        if ($identity->ecourt_account) {
            return response()->json([
                'status' => 'success',
                'message' => 'Akun E-Court sudah dibuat oleh admin.',
                'data' => [
                    'identity_id' => $hashedId,
                    'nama_lengkap' => $identity->nama_lengkap,
                    'status' => 'verified',
                    'username' => $identity->ecourt_account->username,
                    'password' => $identity->ecourt_account->password,
                    'timeline_url' => url('/timeline/'.$hashedId),
                ],
            ]);
        }

        if ($identity->isRejected()) {
            return response()->json([
                'status' => 'rejected',
                'message' => 'Permohonan ditolak oleh admin.',
                'data' => [
                    'identity_id' => $hashedId,
                    'nama_lengkap' => $identity->nama_lengkap,
                    'status' => 'rejected',
                    'alasan_penolakan' => $identity->alasan_penolakan,
                    'rejected_at' => $identity->rejected_at ? $identity->rejected_at->toISOString() : null,
                    'timeline_url' => url('/timeline/'.$hashedId),
                ],
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Menunggu verifikasi admin.',
            'data' => [
                'identity_id' => $hashedId,
                'nama_lengkap' => $identity->nama_lengkap,
                'status' => 'pending',
                'timeline_url' => url('/timeline/'.$hashedId),
            ],
        ]);
    }
}
