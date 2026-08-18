<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ─── Profile Routes ───────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Forms Routes ─────────────────────────────────────────────────────────
    Route::get('/forms/booking-data',           [FormController::class, 'fetchBookingData'])->name('forms.booking-data');
    Route::resource('forms', FormController::class);
    Route::patch('/forms/{form}/mark-live',     [FormController::class, 'markLive'])->name('forms.mark-live');
    Route::patch('/forms/{form}/mark-transfer', [FormController::class, 'markTransfer'])->name('forms.mark-transfer');

    // ─── Dealers Routes ───────────────────────────────────────────────────────
    Route::resource('dealers', \App\Http\Controllers\DealerController::class);
});

require __DIR__.'/auth.php';
