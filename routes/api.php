<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\AgentApiController;
use App\Http\Controllers\CustomerApiController;

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

// admin routes
Route::post('/login', [AdminApiController::class, 'loginAndCreateToken']);
Route::post('/authenticate', [AdminApiController::class, 'checkAuth']);
Route::delete('/logout', [AdminApiController::class, 'logout']);

//agent routes
Route::get('/agent/dashboard', [AgentApiController::class, 'index']);
Route::get('/agent/profile', [AgentApiController::class, 'profile']);
Route::get('/agent/applications', [AgentApiController::class, 'applications']);
Route::get('/agent/apply', [AgentApiController::class, 'applyService']);
Route::post('/agent/apply', [AgentApiController::class, 'applyServiceSubmit']);

//customer routes
Route::get('/customer/get_all_applications', [CustomerApiController::class, 'get_all_applications']);
Route::get('/customer/get_all_services', [CustomerApiController::class, 'get_all_services']);
Route::get('/customer/get_form_data/{service_id}', [CustomerApiController::class, 'get_form_data']);
Route::post('/customer/submit_application/{service_id}', [CustomerApiController::class, 'submit_application']);
Route::post('/customer/update-password', [CustomerApiController::class, 'update_password']);
