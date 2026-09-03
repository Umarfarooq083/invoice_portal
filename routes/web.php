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
    // ─── Profile Routes ──────//
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Forms Routes ───────//
    Route::get('/forms/booking-data',           [FormController::class, 'fetchBookingData'])->name('forms.booking-data');
    Route::resource('forms', FormController::class);
    Route::patch('/forms/{form}/mark-live',     [FormController::class, 'markLive'])->name('forms.mark-live');
    Route::patch('/forms/{form}/mark-transfer', [FormController::class, 'markTransfer'])->name('forms.mark-transfer');

    // ─── Dealers Routes ───────//
    Route::resource('dealers', \App\Http\Controllers\DealerController::class);

    // ─── Blocks Routes ────────//
    Route::resource('blocks', \App\Http\Controllers\BlockController::class);
    Route::get('block-roles/{block_role}/assign-users', [\App\Http\Controllers\BlockRoleController::class, 'assignUsers'])->name('block-roles.assign-users');
    Route::post('block-roles/{block_role}/assign-users', [\App\Http\Controllers\BlockRoleController::class, 'syncUsers'])->name('block-roles.sync-users');
    Route::resource('block-roles', \App\Http\Controllers\BlockRoleController::class);

    // ─── Invoices Routes ───────//
    Route::get('/invoices/fetch-file-data', [\App\Http\Controllers\InvoiceController::class, 'fetchFileData'])->name('invoices.fetch-file-data');
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class)->only(['index', 'create', 'store']);
    // ─── Mergers Routes ───────//
    Route::get('/mergers/fetch-main-app-data', [\App\Http\Controllers\MergerController::class, 'fetchMainAppData'])->name('mergers.fetch-main-app-data');
    Route::get('/mergers/fetch-merge-to-data', [\App\Http\Controllers\MergerController::class, 'fetchMergeToData'])->name('mergers.fetch-merge-to-data');
    Route::resource('mergers', \App\Http\Controllers\MergerController::class);

    // ─── APR Routes ───────//
    Route::get('/aprs/fetch-main-app-data', [\App\Http\Controllers\AprController::class, 'fetchMainAppData'])->name('aprs.fetch-main-app-data');
    Route::get('/aprs/fetch-merge-to-data', [\App\Http\Controllers\AprController::class, 'fetchMergeToData'])->name('aprs.fetch-merge-to-data');
    Route::resource('aprs', \App\Http\Controllers\AprController::class);

    // ─── Invoice Merger Types Routes ───────//
    Route::resource('invoice-merger-types', \App\Http\Controllers\InvoiceMergerTypeController::class);
});

require __DIR__ . '/auth.php';
