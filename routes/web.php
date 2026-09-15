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
use App\Http\Controllers\DurabilityController;
use App\Http\Controllers\QcFacilityController;
use App\Http\Controllers\WorkProgramKpiController;
use App\Http\Controllers\WorkIndicatorController;
use App\Http\Controllers\WorkAchievementController;
use App\Http\Controllers\QcFacilityCategoryController;
use App\Http\Controllers\QcProductElectricalDailyCheckController;
use App\Http\Controllers\QcProductElectricalDashboardController;
use App\Http\Controllers\QcFinalElectricalDailyCheckController;
use App\Http\Controllers\QcFinalElectricalDashboardController;
use App\Http\Controllers\QcFinalElectricalNcrController;
use App\Http\Controllers\QcProductFinalMechanicalDailyCheckController;
use App\Http\Controllers\QcProductFinalMechanicalNcrController;
use App\Http\Controllers\QcProductFinalMechanicalDashboardController;
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
            Route::get('/terlambat', [NCRController::class, 'terlambat'])->name('terlambat');

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
            Route::get('/project', [FeedbackPelangganController::class, 'project'])->name('project');
            Route::get('/barang', [FeedbackPelangganController::class, 'barang'])->name('barang');
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

        //embed visual check
        Route::get('/visiq', function () {
            return view('visual-check.index');
        })->name('visual-check.index');

        // Durability Menu
        Route::prefix('durability')
            ->name('durability.')
            ->group(function () {

                Route::get('/', [DurabilityController::class, 'index'])
                    ->name('index');

                Route::get('/penggantian-komponen', [DurabilityController::class, 'penggantianKomponen'])
                    ->name('penggantian-komponen');

                Route::get('/durability-komponen', [DurabilityController::class, 'durabilityKomponen'])
                    ->name('durability-komponen');

                Route::get('/lokasi', [DurabilityController::class, 'lokasi'])
                    ->name('lokasi');

                Route::get('/tabel-detail', [DurabilityController::class, 'index'])
                    ->name('tabel-detail');

                Route::get('/import', [DurabilityController::class, 'importForm'])
                    ->name('import.form');

                Route::post('/import', [DurabilityController::class, 'import'])
                    ->name('import');

                Route::get('/create', [DurabilityController::class, 'create'])
                    ->name('create');

                Route::post('/', [DurabilityController::class, 'store'])
                    ->name('store');

                // Detail
                Route::get('/{durability}', [DurabilityController::class, 'show'])
                    ->name('show');

                // Edit
                Route::get('/{durability}/edit', [DurabilityController::class, 'edit'])
                    ->name('edit');

                // Update
                Route::put('/{durability}', [DurabilityController::class, 'update'])
                    ->name('update');

                // Delete
                Route::delete('/{durability}', [DurabilityController::class, 'destroy'])
                    ->name('destroy');
            });

    // Program Kerja dan KPI
    Route::prefix('program-kerja-kpi')
        ->name('work-program-kpi.')
        ->group(function () {
            // Semua pengguna yang sudah login
            Route::get('/', [WorkProgramKpiController::class, 'index'])
                ->name('index');

            // Hanya admin dan superadmin
            Route::middleware('isAdmin')->group(function () {
                Route::get(
                    '/indikator',
                    [WorkIndicatorController::class, 'index']
                )->name('indicators.index');

                Route::get(
                    '/indikator/create',
                    [WorkIndicatorController::class, 'create']
                )->name('indicators.create');

                Route::post(
                    '/indikator',
                    [WorkIndicatorController::class, 'store']
                )->name('indicators.store');

                Route::get(
                    '/indikator/{workIndicator}/edit',
                    [WorkIndicatorController::class, 'edit']
                )->name('indicators.edit');

                Route::put(
                    '/indikator/{workIndicator}',
                    [WorkIndicatorController::class, 'update']
                )->name('indicators.update');

                Route::get(
                    '/capaian',
                    [WorkAchievementController::class, 'edit']
                )->name('achievements.edit');

                Route::put(
                    '/capaian',
                    [WorkAchievementController::class, 'update']
                )->name('achievements.update');
            });
        });

    // Kategori Fasilitas Quality Control
    Route::prefix('fasilitas-qc/kategori')
        ->name('qc-facility-categories.')
        ->middleware('isAdmin')
        ->group(function () {
            Route::get('/', [QcFacilityCategoryController::class, 'index'])
                ->name('index');

            Route::post('/', [QcFacilityCategoryController::class, 'store'])
                ->name('store');

            Route::put(
                '/{qcFacilityCategory}',
                [QcFacilityCategoryController::class, 'update']
            )->name('update');

            Route::delete(
                '/{qcFacilityCategory}',
                [QcFacilityCategoryController::class, 'destroy']
            )->name('destroy');
        });

        // Fasilitas Quality Control
    Route::prefix('fasilitas-qc')
        ->name('qc-facilities.')
        ->group(function () {
            // Semua pengguna yang sudah login
            Route::get('/', [QcFacilityController::class, 'index'])
                ->name('index');

            // Hanya admin dan superadmin
            Route::middleware('isAdmin')->group(function () {
                Route::get('/create', [QcFacilityController::class, 'create'])
                    ->name('create');

                Route::post('/', [QcFacilityController::class, 'store'])
                    ->name('store');

                Route::get('/{qcFacility}/edit', [QcFacilityController::class, 'edit'])
                    ->name('edit');

                Route::put('/{qcFacility}', [QcFacilityController::class, 'update'])
                    ->name('update');

                Route::delete('/{qcFacility}', [QcFacilityController::class, 'destroy'])
                    ->name('destroy');
            });

            // Route dinamis diletakkan paling bawah
            Route::get('/{qcFacility}', [QcFacilityController::class, 'show'])
                ->name('show');
        });

        /*
        |--------------------------------------------------------------------------
        | Monitoring QC - QC Product Elektrik
        |--------------------------------------------------------------------------
        */

    Route::prefix('monitoring-qc/product-elektrik')
        ->name('monitoring-qc.product-electrical.')
        ->group(function () {

            Route::get(
                '/dashboard',
                [QcProductElectricalDashboardController::class, 'index']
            )->name('dashboard');

            Route::prefix('daily-check')
                ->name('daily-check.')
                ->controller(QcProductElectricalDailyCheckController::class)
                ->group(function () {
                    Route::get('/', 'index')
                        ->name('index');

                    Route::get('/create', 'create')
                        ->name('create');

                    Route::post('/', 'store')
                        ->name('store');

                    /*
                    * Route dinamis ditempatkan setelah /create.
                    */
                    Route::get('/{dailyCheck}', 'show')
                        ->name('show');

                    Route::get('/{dailyCheck}/edit', 'edit')
                        ->name('edit');

                    Route::put('/{dailyCheck}', 'update')
                        ->name('update');

                    Route::delete('/{dailyCheck}', 'destroy')
                        ->name('destroy');
                });
        });

    Route::prefix('monitoring-qc/final-elektrik')
        ->name('monitoring-qc.final-electrical.')
        ->group(function () {

            Route::get(
                '/dashboard',
                [QcFinalElectricalDashboardController::class, 'index']
            )->name('dashboard');

            Route::prefix('detail-ncr')
                ->name('ncr.')
                ->controller(
                    QcFinalElectricalNcrController::class
                )
                ->group(function () {
                    Route::get(
                        '/',
                        'index'
                    )->name('index');

                    Route::get(
                        '/create',
                        'create'
                    )->name('create');

                    Route::post(
                        '/',
                        'store'
                    )->name('store');

                    Route::get(
                        '/{ncrDetail}',
                        'show'
                    )->name('show');

                    Route::get(
                        '/{ncrDetail}/edit',
                        'edit'
                    )->name('edit');

                    Route::put(
                        '/{ncrDetail}',
                        'update'
                    )->name('update');

                    Route::delete(
                        '/{ncrDetail}',
                        'destroy'
                    )->name('destroy');
                });

            Route::prefix('daily-check')
                ->name('daily-check.')
                ->controller(
                    QcFinalElectricalDailyCheckController::class
                )
                ->group(function () {
                    Route::get(
                        '/',
                        'index'
                    )->name('index');

                    Route::get(
                        '/create',
                        'create'
                    )->name('create');

                    Route::post(
                        '/',
                        'store'
                    )->name('store');

                    Route::get(
                        '/{dailyCheck}',
                        'show'
                    )->name('show');

                    Route::get(
                        '/{dailyCheck}/edit',
                        'edit'
                    )->name('edit');

                    Route::put(
                        '/{dailyCheck}',
                        'update'
                    )->name('update');

                    Route::delete(
                        '/{dailyCheck}',
                        'destroy'
                    )->name('destroy');
                });
        });

    Route::prefix('monitoring-qc/product-final-mekanik')
            ->name('monitoring-qc.product-final-mechanical.')
            ->group(function () {
                Route::get(
                    '/',
                    [
                        QcProductFinalMechanicalDashboardController::class,
                        'index',
                    ]
                )->name('dashboard');
                Route::prefix('daily-check')

                    ->name('daily-check.')
                    ->controller(
                        QcProductFinalMechanicalDailyCheckController::class
                    )
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/create', 'create')
                            ->name('create');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::get('/{dailyCheck}', 'show')
                            ->name('show');

                        Route::get('/{dailyCheck}/edit', 'edit')
                            ->name('edit');

                        Route::put('/{dailyCheck}', 'update')
                            ->name('update');

                        Route::delete('/{dailyCheck}', 'destroy')
                            ->name('destroy');
                    });

                Route::prefix('ncr')
                    ->name('ncr.')
                    ->controller(
                        QcProductFinalMechanicalNcrController::class
                    )
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/create', 'create')
                            ->name('create');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::get('/{ncr}', 'show')
                            ->name('show');

                        Route::get('/{ncr}/edit', 'edit')
                            ->name('edit');

                        Route::put('/{ncr}', 'update')
                            ->name('update');

                        Route::delete('/{ncr}', 'destroy')
                            ->name('destroy');
                    });

            });

    Route::get('/bantuan', [App\Http\Controllers\BantuanController::class, 'index'])->name('bantuan.index');

});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('unit-kerja', UnitKerjaController::class);

    Route::resource('temuan', TemuanController::class)->parameters(['temuan' => 'lokasi']);

    Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project']);

    Route::resource('changelog', App\Http\Controllers\ChangelogController::class)->except(['show']);
});


require __DIR__ . '/auth.php';
