<?php

use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthCheckController;
use App\Http\Controllers\Api\V1\HealthRecordController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\VitalSignController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthCheckController::class);

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [MeController::class, 'show']);

        Route::middleware(['audit', 'throttle:api'])->group(function (): void {
            Route::apiResource('patients', PatientController::class);
            Route::get('patients/{patient}/health-records', [HealthRecordController::class, 'index']);
            Route::post('patients/{patient}/health-records', [HealthRecordController::class, 'store']);
            Route::get('patients/{patient}/vital-signs', [VitalSignController::class, 'index']);
            Route::post('patients/{patient}/vital-signs', [VitalSignController::class, 'store']);
            Route::get('audit-logs', [AuditLogController::class, 'index'])->middleware('role:admin');
        });
    });
});
