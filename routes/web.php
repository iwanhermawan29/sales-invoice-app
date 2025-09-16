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

Route::get('/', function () {
    return view('welcome');
});

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
    });

    Route::middleware([
        'auth',
        RoleMiddleware::class . ':admin',
    ])->group(function () {
        // Hanya Admin:

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
    });
});
