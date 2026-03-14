<?php

use App\Modules\FeatureFlags\Presentation\Http\Controllers\Web\FeatureFlagAdminPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/feature-flags', [FeatureFlagAdminPageController::class, 'index'])->name('feature-flags.index');
    Route::get('/feature-flags/create', [FeatureFlagAdminPageController::class, 'create'])->name('feature-flags.create');
    Route::post('/feature-flags', [FeatureFlagAdminPageController::class, 'store'])->name('feature-flags.store');
    Route::get('/feature-flags/{id}/edit', [FeatureFlagAdminPageController::class, 'edit'])->name('feature-flags.edit');
    Route::put('/feature-flags/{id}', [FeatureFlagAdminPageController::class, 'update'])->name('feature-flags.update');
    Route::delete('/feature-flags/{id}', [FeatureFlagAdminPageController::class, 'destroy'])->name('feature-flags.destroy');
});
