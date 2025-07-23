<?php

use App\Livewire\SettingAdminEcourt;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/admin_ecourt', SettingAdminEcourt::class)->name('settings.admin_ecourt');
});

Route::middleware(['auth'])->group(function () {
    Route::get('permohonan-akun', App\Livewire\ManageIdentityPage::class)->name('permohonan-akun');

    Route::get('identity/{hash_id}', App\Livewire\IdentityDetailPage::class);
});

Route::get("step-1/{hash_id}", \App\Livewire\IdentityStepWizard::class)->name("step-1");
Route::get("step-1", \App\Livewire\IdentityStepWizard::class);
Route::get("step-2/{hash_id}", \App\Livewire\BankAccountStepWizard::class)->name("step-2");
Route::get("timeline/{hash_id}", \App\Livewire\TimeLineWizard::class)->name("timeline");
Route::get("search", \App\Livewire\SearchIdentityWizard::class)->name("search");

require __DIR__ . '/auth.php';
