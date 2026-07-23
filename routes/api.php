<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PermohonanController;

Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toIso8601String()
    ]);
});

Route::prefix('permohonan')->group(function () {
    Route::post('/step-1', [PermohonanController::class, 'step1']);
    Route::post('/step-2/{identity_id}', [PermohonanController::class, 'step2']);
    Route::get('/status/{identity_id}', [PermohonanController::class, 'status']);
    Route::get('/identity/{identity_id}', [PermohonanController::class, 'show']);
});
