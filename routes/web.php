<?php

use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════
// PUBLIC ROUTES
// ══════════════════════════════════════════════
Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/search', [\App\Http\Controllers\PublicController::class, 'search'])->name('search');
Route::get('/artikel/{slug}', [\App\Http\Controllers\PublicController::class, 'show'])->name('article.show');
Route::get('/kategori/{slug}', [\App\Http\Controllers\PublicController::class, 'category'])->name('category.show');
Route::get('/jaringan/{slug}', [\App\Http\Controllers\PublicController::class, 'network'])->name('jaringan.show');
Route::get('/halaman/{slug}', [\App\Http\Controllers\PublicController::class, 'page'])->name('page.show');


// ══════════════════════════════════════════════
// ADMIN ROUTES
// ══════════════════════════════════════════════
Route::prefix('seputaradmin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // ── Advertisement Management ─────────────────
        Route::post('advertisements/bulk-delete',
            [\App\Http\Controllers\Admin\AdvertisementController::class, 'bulkDelete'])
            ->name('advertisements.bulk-delete');

        Route::resource('advertisements', \App\Http\Controllers\Admin\AdvertisementController::class)
            ->except(['show']);

        // ── User Management ──────────────────────────
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
            ->except(['show']);
        Route::patch('users/{user}/toggle-status',
            [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // ── Category Management ──────────────────────
        Route::post('categories/bulk-delete',
            [\App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])
            ->name('categories.bulk-delete');

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)
            ->except(['show']);

        // ── Article Management ───────────────────────
        // ⚠️ PENTING: Route fetch-url WAJIB didefinisikan SEBELUM resource route.
        // Jika diletakkan sesudah, Laravel akan mencocokkan "fetch-url" sebagai
        // parameter {article} di route articles/{article} → 404 / model not found.
        Route::post('articles/fetch-url',
            [\App\Http\Controllers\Admin\ArticleController::class, 'fetchFromUrl'])
            ->name('articles.fetch');

        Route::post('articles/import-xml',
            [\App\Http\Controllers\Admin\ArticleController::class, 'importXml'])
            ->name('articles.import-xml');

        Route::post('articles/bulk-action',
            [\App\Http\Controllers\Admin\ArticleController::class, 'bulkAction'])
            ->name('articles.bulk-action');

        Route::post('articles/{id}/restore',
            [\App\Http\Controllers\Admin\ArticleController::class, 'restore'])
            ->name('articles.restore');
        Route::delete('articles/{id}/force-delete',
            [\App\Http\Controllers\Admin\ArticleController::class, 'forceDelete'])
            ->name('articles.force-delete');

        Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class)
            ->except(['show']);

        // ── Profile Management ───────────────────────
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('admin.profile.update');
        Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('admin.profile.destroy');
    });


require __DIR__.'/auth.php';