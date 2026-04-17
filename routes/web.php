<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NCRController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NcrPdfController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignatureController;

//testing only
// use App\Models\Ncr;
// use App\Models\User;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/signature/{token}', [SignatureController::class, 'show'])->name('signature.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    // Profile
    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
            Route::patch('/foto', [ProfileController::class, 'updateFoto'])->name('foto');
        });

    // Notifications
    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/{id}/read', [NotificationController::class, 'read'])->name('read');
        });

    // NCR
    Route::prefix('ncr')
        ->name('ncr.')
        ->group(function () {
            Route::get('/', [NCRController::class, 'index'])->name('index');
            Route::get('/verifikasi', [NCRController::class, 'verifikasincr'])->name('verifikasi.index');

            Route::get('/create', [NCRController::class, 'create'])->name('create');
            Route::post('/', [NCRController::class, 'simpan'])->name('store');

            Route::get('/{nomor_ncr}', [NCRController::class, 'show'])->name('show');
            Route::get('/{nomor_ncr}/edit', [NCRController::class, 'edit'])->name('edit');
            Route::put('/{nomor_ncr}', [NCRController::class, 'update'])->name('update');

            Route::get('/{nomor_ncr}/tanggapi', [NCRController::class, 'tanggapi'])->name('tanggapi');
            Route::post('/{nomor_ncr}/tanggapi', [NCRController::class, 'simpantanggapi'])->name('tanggapi.store');

            Route::get('/{nomor_ncr}/verifikasi', [NCRController::class, 'verifikasi'])->name('verifikasi.form');
            Route::post('/{nomor_ncr}/verifikasi', [NCRController::class, 'simpanverifikasi'])->name('verifikasi.store');

            Route::post('/{nomor_ncr}/open', [NCRController::class, 'openncr'])->name('open');
            Route::delete('/{nomor_ncr}', [NCRController::class, 'destroy'])->name('destroy');

            Route::get('/ncr/export-report', [NcrController::class, 'exportReport'])->name('export-report');
            Route::get('/{nomor_ncr}/export-pdf', [NcrPdfController::class, 'export'])->name('export.pdf');
        });
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('unit-kerja', UnitKerjaController::class);

    Route::resource('temuan', TemuanController::class)->parameters(['temuan' => 'lokasi']);

    Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project']);
});


// Testing debug view email

// Route::get('/debug/email/perlu-ditanggapi', function () {
//     $notifiable = User::first() ?? (object) ['name' => 'User Debug'];
//     $ncr = Ncr::first() ?? (object) ['nomor_ncr' => '202604010077'];

//     return view('emails.perlu-ditanggapi', [
//         'notifiable' => $notifiable,
//         'ncr' => $ncr,
//         'url' => route('ncr.show', $ncr->nomor_ncr ?? '202604010077'),
//     ]);
// });

// Route::get('/debug/email/perlu-diverifikasi', function () {
//     $notifiable = User::first() ?? (object) ['name' => 'User Debug'];
//     $ncr = Ncr::first() ?? (object) ['nomor_ncr' => '202604010077'];

//     return view('emails.perlu-diverifikasi', [
//         'notifiable' => $notifiable,
//         'ncr' => $ncr,
//         'url' => route('ncr.show', $ncr->nomor_ncr ?? '202604010077'),
//     ]);
// });

// Route::get('/debug/email/terlambat', function () {
//     $notifiable = User::first() ?? (object) ['name' => 'User Debug'];
//     $ncr = Ncr::first() ?? (object) [
//         'nomor_ncr' => '202604010077',
//         'tgl_target' => now(),
//     ];

//     return view('emails.terlambat', [
//         'notifiable' => $notifiable,
//         'ncr' => $ncr,
//         'url' => route('ncr.show', $ncr->nomor_ncr ?? '202604010077'),
//         'tanggal_target' => optional($ncr->tgl_target)->format('Y-m-d') ?? '-',
//         'hari_terlambat' => 5,
//     ]);
// });

require __DIR__ . '/auth.php';
