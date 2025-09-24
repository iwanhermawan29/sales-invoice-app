<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesTargetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EventTargetController;
use App\Http\Controllers\SalesClosingController;
use App\Http\Controllers\Head\SalesTargetReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleExportController;
use App\Http\Controllers\Admin\AgentVerificationController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestMediaController;
use App\Http\Controllers\HeadReportController;
use App\Http\Controllers\TargetPenjualanController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GalleryPublicController;
use App\Http\Controllers\Admin\CollaborationController;
use App\Http\Controllers\PublicCollaborationController;




Route::get('/', function () {
    return view('welcome');
});
// ABOUT (halaman publik)
Route::view('/about', 'public.about')->name('about');
Route::view('/demography', 'public.demography')->name('demography');
Route::get('/gallery', [GalleryPublicController::class, 'index'])->name('gallery.public');
Route::get('/collaboration', [PublicCollaborationController::class, 'index'])
    ->name('collaboration');


require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    // Dashboard tunggal, yang akan memilih view HRD atau Direktur
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('sales-targets', [SalesTargetController::class, 'index'])
        ->name('sales-targets.index');
    Route::resource('event-targets',   EventTargetController::class);


    Route::middleware(['auth', RoleMiddleware::class . ':head',])->group(function () {
        Route::get('reports/sales-targets', [SalesTargetReportController::class, 'index'])
            ->name('reports.sales-targets.index');
        Route::get('/head/reports',        [HeadReportController::class, 'index'])->name('head.reports.index');
        Route::get('/head/reports/excel',  [HeadReportController::class, 'exportExcel'])->name('head.reports.excel');
        Route::get('/head/reports/print',  [HeadReportController::class, 'exportPdf'])->name('head.reports.print');
    });


    Route::middleware([
        'auth',
        RoleMiddleware::class . ':agent',
    ])->group(function () {
        // Hanya Agent:

        Route::get('sales-targets/create', [SalesTargetController::class, 'create'])
            ->name('sales-targets.create');
        Route::post('sales-targets', [SalesTargetController::class, 'store'])
            ->name('sales-targets.store');

        Route::get(
            'sales-targets/{sales_target}/closings/create',
            [SalesClosingController::class, 'create']
        )
            ->name('sales-targets.closings.create');
        Route::post(
            'sales-targets/{sales_target}/closings',
            [SalesClosingController::class, 'store']
        )
            ->name('sales-targets.closings.store');
        Route::resource('sales', SaleController::class);
        Route::get('sales/export/excel', [SaleExportController::class, 'excel'])->name('sales.export.excel');
        Route::get('sales/export/pdf',   [SaleExportController::class, 'pdf'])->name('sales.export.pdf');
        Route::get('/agent/contests', [ContestController::class, 'agentIndex'])
            ->name('agent.contests.index');
        Route::get('/agent/targets', [TargetPenjualanController::class, 'agentIndex'])
            ->name('agent.targets.index');
    });

    Route::middleware([
        'auth',
        RoleMiddleware::class . ':admin',
    ])->group(function () {
        // Hanya Admin:

        // verifikasi
        Route::patch('sales/{sale}/approve', [SaleController::class, 'approve'])->name('sales.approve');
        Route::patch('sales/{sale}/reject', [SaleController::class, 'reject'])->name('sales.reject');

        // index khusus admin (verifikasi)
        Route::get('admin/sales', [SaleController::class, 'adminIndex'])->name('admin.sales.index');


        Route::get('admin/agents', [AgentVerificationController::class, 'index'])->name('admin.agents.index');
        Route::patch('admin/agents/{user}/approve', [AgentVerificationController::class, 'approve'])->name('admin.agents.approve');
        Route::patch('admin/agents/{user}/reject',  [AgentVerificationController::class, 'reject'])->name('admin.agents.reject');

        Route::get('sales-targets/{sales_target}/edit', [SalesTargetController::class, 'edit'])
            ->name('sales-targets.edit');
        Route::patch('sales-targets/{sales_target}', [SalesTargetController::class, 'update'])
            ->name('sales-targets.update');
        Route::delete('sales-targets/{sales_target}', [SalesTargetController::class, 'destroy'])
            ->name('sales-targets.destroy');

        Route::get(
            'sales-targets/{sales_target}/closings',
            [SalesClosingController::class, 'index']
        )
            ->name('sales-targets.closings.index');

        Route::resource('users',           UserController::class);
        Route::resource('products',        ProductController::class);
        Route::resource('contests', ContestController::class);
        Route::get('contests/{contest}/media',        [ContestMediaController::class, 'index'])->name('contests.media.index');
        Route::post('contests/{contest}/media',       [ContestMediaController::class, 'store'])->name('contests.media.store');
        Route::put('contests/{contest}/media/{media}', [ContestMediaController::class, 'update'])->name('contests.media.update');
        Route::delete('contests/{contest}/media/{media}', [ContestMediaController::class, 'destroy'])->name('contests.media.destroy');
        Route::resource('targets-penjualan', TargetPenjualanController::class)
            ->parameters(['targets-penjualan' => 'targets_penjualan']) // agar binding variabelnya $targets_penjualan
            ->names('targets-penjualan');
        Route::get('/galleries',              [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('/galleries/create',       [GalleryController::class, 'create'])->name('galleries.create');
        Route::post('/galleries',             [GalleryController::class, 'store'])->name('galleries.store');
        Route::get('/galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
        Route::put('/galleries/{gallery}',    [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::resource('collaborations', CollaborationController::class)
            ->names('collaborations');
    });
});
