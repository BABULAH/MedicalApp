<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH – PATIENT / USER (SANCTUM)
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
        Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
            Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | AUTH – DOCTOR (JWT)
    |--------------------------------------------------------------------------
    */
    // Route::prefix('v1/doctor')->group(function () {() {
    //         Route::post('/login', [\App\Http\Controllers\Api\Doctor\AuthController::class, 'login'])
    //             ->name('doctor.login');
    //     });

    /*
    |--------------------------------------------------------------------------
    | PUBLIC DATA
    |--------------------------------------------------------------------------
    */
    Route::get('/specialities', [\App\Http\Controllers\Api\SpecialityController::class, 'index']);
    Route::get('/localities', [\App\Http\Controllers\Api\LocalityController::class, 'index']);
    Route::get('/establishments', [\App\Http\Controllers\Api\EstablishmentController::class, 'index']);

    Route::get('/doctors', [\App\Http\Controllers\Api\DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [\App\Http\Controllers\Api\DoctorController::class, 'show']);
    Route::get('/doctors/{doctor}/availabilities', [\App\Http\Controllers\Api\AvailabilityController::class, 'byDoctor']);

    /*
    |--------------------------------------------------------------------------
    | PATIENT – SANCTUM + ROLE
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'role:patient'])->group(function () {

        Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
        Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);

        Route::apiResource('appointments', \App\Http\Controllers\Api\AppointmentController::class)
            ->only(['index', 'store', 'show', 'destroy']);

        Route::post('/appointments/{appointment}/cancel',
            [\App\Http\Controllers\Api\AppointmentController::class, 'cancel']);

        Route::post('/doctors/{doctor}/reviews',
            [\App\Http\Controllers\Api\ReviewController::class, 'store']);

        Route::get('/notifications',
            [\App\Http\Controllers\Api\NotificationController::class, 'index']);

        Route::post('/notifications/{notification}/read',
            [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN – SANCTUM + ROLE
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'role:admin'])
        ->prefix('admin')
        ->group(function () {

            Route::get('/profile', [\App\Http\Controllers\Api\Admin\ProfileController::class, 'show']);
            Route::put('/profile', [\App\Http\Controllers\Api\Admin\ProfileController::class, 'update']);

            Route::apiResource('doctors',
                \App\Http\Controllers\Api\Admin\DoctorController::class)
                ->except(['create', 'edit']);
        });

    /*
    |--------------------------------------------------------------------------
    | DOCTOR – JWT + MULTI-TENANT
    |--------------------------------------------------------------------------
    */
    Route::middleware([
            'auth:api_doctor',
            'ensure.doctor.tenant'
        ])
        ->prefix('doctor')
        ->group(function () {

            Route::get('/profile',
                [\App\Http\Controllers\Api\Doctor\ProfileController::class, 'show']);

            Route::put('/profile',
                [\App\Http\Controllers\Api\Doctor\ProfileController::class, 'update']);

            Route::apiResource('availabilities',
                \App\Http\Controllers\Api\Doctor\AvailabilityController::class)
                ->except(['create', 'edit', 'show']);

            Route::get('/appointments',
                [\App\Http\Controllers\Api\Doctor\AppointmentController::class, 'index']);

            Route::patch('/appointments/{appointment}',
                [\App\Http\Controllers\Api\Doctor\AppointmentController::class, 'update']);
        });
});
