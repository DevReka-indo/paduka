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
use App\Http\Controllers\FeedbackPelangganController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\FeedbackProjectController;
use App\Http\Controllers\FeedbackProjectItemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//testing only
use App\Models\Ncr;
use App\Models\User;
use App\Models\NcrChangeLog;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

//link detail qr code signature
Route::get('/signature/{token}', [SignatureController::class, 'show'])->name('signature.show');

//link publik form kepuasan pelanggan
Route::get('/survey-kepuasan', [FeedbackPelangganController::class, 'form'])->name('feedback.form');
Route::post('/survey-kepuasan', [FeedbackPelangganController::class, 'store'])->name('feedback.store');
Route::get('/survey-kepuasan/submited', [FeedbackPelangganController::class, 'success'])->name('feedback.success');

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

            Route::get('/{nomor_ncr}/revision/{rev}', [NCRController::class, 'showRevision'])->name('revision.show');
        });

    // Feedback Kepuasan Pelanggan
    Route::prefix('feedback-pelanggan')
        ->name('feedback.')
        ->group(function () {
            Route::get('/', [FeedbackPelangganController::class, 'index'])->name('index');
            Route::get('/{id}', [FeedbackPelangganController::class, 'show'])->name('show');
            Route::get('/{id}/pdf', [FeedbackPelangganController::class, 'pdf'])->name('pdf');
            Route::delete('/{id}', [FeedbackPelangganController::class, 'destroy'])->name('destroy');
        });

        Route::resource('feedback-projects', FeedbackProjectController::class);
        Route::resource('feedback-project-items', FeedbackProjectItemController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);



        Route::get('/ncr/file/{path}', function (string $path) {
            $decoded = base64_decode($path);

            // Cegah path traversal
            if (str_contains($decoded, '..')) {
                abort(403);
            }

            $fullPath = storage_path('app/' . $decoded);

            if (!file_exists($fullPath)) {
                abort(404, 'File tidak ditemukan.');
            }

            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'])) {
                abort(403, 'Tipe file tidak diizinkan.');
            }

            $mimeMap = [
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'gif'  => 'image/gif',
                'webp' => 'image/webp',
                'svg'  => 'image/svg+xml',
                'bmp'  => 'image/bmp',
            ];

            return response()->file($fullPath, [
                'Content-Type' => $mimeMap[$ext] ?? 'application/octet-stream',
            ]);
        })->middleware('auth')->name('ncr.file.preview');

        Route::get('/bantuan', [App\Http\Controllers\BantuanController::class, 'index'])->name('bantuan.index');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('unit-kerja', UnitKerjaController::class);

    Route::resource('temuan', TemuanController::class)->parameters(['temuan' => 'lokasi']);

    Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project']);

    Route::resource('changelog', App\Http\Controllers\ChangelogController::class)->except(['show']);
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

// Route::get('/debug/email/direvisi', function () {
//     $notifiable = User::first() ?? (object) ['name' => 'User Debug'];

//     $nomorNcr = '202604200084';

//     $ncr = Ncr::where('nomor_ncr', $nomorNcr)->first()
//         ?? (object) ['nomor_ncr' => $nomorNcr];

//     $changeLog = NcrChangeLog::with('user')
//         ->where('nomor_ncr', $nomorNcr)
//         ->orderByDesc('revision_index')
//         ->first();

//     if (!$changeLog) {
//         $changeLog = (object) [
//             'nomor_ncr' => $nomorNcr,
//             'revision' => 'Rev-07',
//             'revision_index' => 7,
//             'action' => 'update',
//             'user' => (object) ['name' => 'Editor Debug'],
//         ];
//     }

//     return view('emails.direvisi', [
//         'notifiable' => $notifiable,
//         'ncr' => $ncr,
//         'changeLog' => $changeLog,
//         'url' => route('ncr.revision.show', [
//             'nomor_ncr' => $ncr->nomor_ncr,
//             'rev' => $changeLog->revision_index,
//         ]),
//     ]);
// });

require __DIR__ . '/auth.php';
