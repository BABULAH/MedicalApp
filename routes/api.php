<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Doctor\{
    AppointmentController,
    };

use App\Http\Controllers\Api\Patient\{
    DoctorController,
    DoctorAvailabilityController,
    TimeSlotController,
};

/*
|--------------------------------------------------------------------------
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth:api')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

/*
|--------------------------------------------------------------------------
| routes protégées avec vérification de rôle (Spatie)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);




Route::middleware(['auth:api', 'role:patient'])
    ->prefix('patient')
    ->group(function () {
        Route::get('doctors', [DoctorController::class, 'index']);
        Route::get('doctors/{doctor}', [DoctorController::class, 'show']);

        Route::get('/doctor/{doctor_id}/availabilities', [DoctorAvailabilityController::class, 'index']);

        // Lister tous les créneaux d?un médecin
        Route::get('/doctors/{doctor_id}/time-slots', [TimeSlotController::class, 'index']);
        // Lister les créneaux d 'une disponibilité spécifique
        Route::get('/availabilities/{availability_id}/time-slots', [TimeSlotController::class, 'listByAvailability']);
        
    });






Route::middleware(['auth:api', 'role:doctor'])
    ->prefix('doctor')
    ->group(function () {
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::post('appointments/{appointment}/accept', [AppointmentController::class, 'accept']);
        Route::post('appointments/{appointment}/reject', [AppointmentController::class, 'reject']);
    });


Route::middleware(['auth:sanctum'])->group(function () {


    // Accessible par doctor
    Route::middleware('role:doctor')->prefix('doctor')->group(function () {
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::post('appointments/{appointment}/accept', [AppointmentController::class, 'accept']);
        Route::post('appointments/{appointment}/reject', [AppointmentController::class, 'reject']);
        // Route::apiResource('appointments', AppointmentController::class);
    });

    // Accessible par patient
    Route::middleware('role:patient')->prefix('patient')->group(function () {
        // Route::get('appointments', ...);
    });
});
