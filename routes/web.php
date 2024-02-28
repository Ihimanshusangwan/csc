<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ServiceGroupController;
use App\Http\Controllers\ServiceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//admin routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AdminLoginController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::get('/agents/{id}', [AdminLoginController::class, 'agentView'])->name('agent.show');
Route::get('/admin/filter', [AdminLoginController::class, 'filter'])->name('admin.filter');
Route::get('/admin/recharge-history', [AdminLoginController::class, 'rechargeHistory'])->name('admin.recharge-history');


//service groups routes
Route::get('/service-groups', [ServiceGroupController::class, 'index'])->name('service-groups.index');
Route::post('/service-groups', [ServiceGroupController::class, 'store'])->name('service-groups.store');
Route::get('/service-groups/{id}/edit', [ServiceGroupController::class, 'edit'])->name('service-groups.edit');
Route::put('/service-groups/{id}/update', [ServiceGroupController::class, 'update'])->name('service-groups.update');

//service routes

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('services/{id}/delete', [ServiceController::class, 'destroy'])->name('services.delete');

// location routes

use App\Http\Controllers\LocationController;

Route::resource('locations', LocationController::class);

//price routes

use App\Http\Controllers\PriceController;
Route::get('/prices/{serviceId}', [PriceController::class, 'index'])->name('prices.index');
Route::post('/prices/{serviceId}/store', [PriceController::class, 'store'])->name('prices.store');
Route::post('/prices/update', [PriceController::class, 'update'])->name('prices.update');

//plans routes

use App\Http\Controllers\PlanController;

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
Route::delete('/plans/{id}', [PlanController::class, 'destroy'])->name('plans.destroy');

//agent routes

Route::post('/agent/register', [AgentController::class, 'store'])->name('agents.store');
Route::post('/agent/approve', [AgentController::class, 'approve'])->name('agents.approve');
Route::post('/agent/reject', [AgentController::class, 'reject'])->name('agents.reject');
Route::post('/agent/hold', [AgentController::class, 'hold'])->name('agents.hold');
Route::post('/agent/unhold', [AgentController::class, 'unhold'])->name('agents.unhold');
Route::get('admin/registered-agents', [AgentController::class, 'showAgentDetails'])->name('admin.registered-agents');
Route::get('admin/requested-agents', [AgentController::class, 'showRequestedAgentDetails'])->name('admin.requested-agents');
Route::post('/recharge/{id}', [AgentController::class, 'recharge'])->name('agent.recharge');
Route::get('/agent/recharge-history', [AgentController::class, 'rechargeHistory'])->name('agent.recharge-history');


Route::get('/agent/login', [AgentController::class, 'showLoginForm'])->name('agent.login');
Route::post('/agent/login', [AgentController::class, 'login'])->name('agent.login.submit');
Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
Route::get('/agent/applications', [AgentController::class, 'applications'])->name('agent.applications');
Route::get('/agent/logout', [AgentController::class, 'logout'])->name('agent.logout');
Route::get('/servicegroup/view/{serviceGroupId}', [AgentController::class, 'view'])->name('service-group.view');

//Appy Services
use App\Http\Controllers\ApplyServiceController; 

Route::get('/service/apply/direct/{id}', [ApplyServiceController::class, 'direct'])->name('service.direct-apply');
Route::post('/submit-form/{id}', [ApplyServiceController::class, 'submitForm'])->name('submitForm');

//application routes
use App\Http\Controllers\ApplicationController;
Route::post('/update-application', [ApplicationController::class, 'update'])->name('application.update');




