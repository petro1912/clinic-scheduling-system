<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ScheduleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:sanctum']], function () {
  Route::get('services', [ServiceController::class, 'getDoctorServices']);
  Route::post('services', [ServiceController::class, 'updateDoctorService']);
  Route::delete('services/{id}', [ServiceController::class, 'deleteDoctorService']);

  Route::get('availability', [AvailabilityController::class, 'getAvailability']);
  Route::post('availability', [AvailabilityController::class, 'updateAvailability']);

  Route::get('schedules', [ScheduleController::class, 'getAllSchedules']);
  Route::post('meeting', [ScheduleController::class, 'createMeeting']);
});

Route::group(['prefix' => 'patient', 'middleware' => ['auth:sanctum']], function () {
  Route::get('schedules', [ScheduleController::class, 'getMySchedules']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
  Route::get('accounts', [AccountController::class, 'getProfessionals']);
  Route::post('accounts', [AccountController::class, 'updateProfessional']);  
});

Route::get('services', [ServiceController::class, 'getAllServices']);
Route::get('services-schedules', [ServiceController::class, 'getAllServicesWithAvailability']);
Route::post('/schedule/register', [ScheduleController::class, 'register']);


//Route::get('password', [AuthController::class, 'genPassword']);