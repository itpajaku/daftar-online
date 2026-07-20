<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PermohonanController;

Route::prefix('permohonan')->group(function () {
    Route::post('/step-1', [PermohonanController::class, 'step1']);
    Route::post('/step-2/{identity_id}', [PermohonanController::class, 'step2']);
});
