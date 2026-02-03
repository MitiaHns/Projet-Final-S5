<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client API (Firebase Auth)
|--------------------------------------------------------------------------
|
| These endpoints expect an Authorization: Bearer <Firebase ID Token>.
| Firestore is the main database for: users, cars, repairs, payments...
|
*/

Route::middleware(['firebase.auth'])->group(function () {
    Route::get('/repair-types', [\App\Http\Controllers\Api\RepairTypeController::class, 'index']);
    Route::get('/repair-types/{id}', [\App\Http\Controllers\Api\RepairTypeController::class, 'show']);

    Route::get('/cars', [\App\Http\Controllers\Api\CarController::class, 'index']);
    Route::post('/cars', [\App\Http\Controllers\Api\CarController::class, 'store']);

    Route::get('/repairs', [\App\Http\Controllers\Api\RepairController::class, 'index']);
    Route::post('/repairs', [\App\Http\Controllers\Api\RepairController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin API (Postgres + Sanctum)
|--------------------------------------------------------------------------
|
| Admin accounts live in Postgres (connection: pgsql_admin).
| Admin can also read/write operational collections in Firestore.
|
*/

Route::prefix('admin')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);

    Route::middleware(['auth:admin'])->group(function () {
        // Firestore management
        Route::post('/repair-types', [\App\Http\Controllers\Api\RepairTypeController::class, 'store']);
        Route::put('/repair-types/{id}', [\App\Http\Controllers\Api\RepairTypeController::class, 'update']);
        Route::delete('/repair-types/{id}', [\App\Http\Controllers\Api\RepairTypeController::class, 'destroy']);

        Route::get('/slots', [\App\Http\Controllers\Admin\SlotController::class, 'index']);
        Route::post('/slots', [\App\Http\Controllers\Admin\SlotController::class, 'store']);
        Route::put('/slots/{id}', [\App\Http\Controllers\Admin\SlotController::class, 'update']);

        Route::get('/repairs', [\App\Http\Controllers\Admin\RepairController::class, 'index']);
        Route::post('/repairs/{repairId}/status', [\App\Http\Controllers\Admin\RepairController::class, 'updateStatus']);
    });
});
