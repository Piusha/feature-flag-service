<?php

use App\Modules\CarDamageReports\Presentation\Http\Controllers\Api\CarDamageReportController;
use App\Modules\FeatureFlags\Presentation\Http\Controllers\Api\AdminFeatureFlagController;
use App\Modules\FeatureFlags\Presentation\Http\Controllers\Api\FeatureFlagEvaluationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (): void {
    Route::get('/feature-flags', [AdminFeatureFlagController::class, 'index']);
    Route::post('/feature-flags', [AdminFeatureFlagController::class, 'store']);
    Route::get('/feature-flags/{id}', [AdminFeatureFlagController::class, 'show']);
    Route::put('/feature-flags/{id}', [AdminFeatureFlagController::class, 'update']);
    Route::delete('/feature-flags/{id}', [AdminFeatureFlagController::class, 'destroy']);
});

Route::get('/feature-flags/evaluate', FeatureFlagEvaluationController::class);

Route::get('/reports', [CarDamageReportController::class, 'index']);
Route::post('/reports', [CarDamageReportController::class, 'store']);
Route::get('/reports/{id}', [CarDamageReportController::class, 'show']);
Route::put('/reports/{id}', [CarDamageReportController::class, 'update'])
    ->middleware('feature.enabled:allow_report_editing');
Route::post('/reports/{id}/photos', [CarDamageReportController::class, 'uploadPhoto'])
    ->middleware('feature.enabled:allow_photo_upload');
Route::get('/reports/{id}/history', [CarDamageReportController::class, 'history']);
