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
Route::get('/agents/{id}/{category}', [AdminLoginController::class, 'agentView'])->name('agent.show');
Route::get('/admin/filter', [AdminLoginController::class, 'filter'])->name('admin.filter');
Route::get('/admin/recharge-history', [AdminLoginController::class, 'rechargeHistory'])->name('admin.recharge-history');
Route::get('/admin/appointment-history', [AdminLoginController::class, 'appointments'])->name('admin.appointment-history');
Route::get('/admin/visited-appointments', [AdminLoginController::class, 'visitedAppointments'])->name('admin.appointment-visited');
Route::get('/admin/rejected-appointment', [AdminLoginController::class, 'rejectedAppointments'])->name('admin.appointment-rejected');
Route::post('/admin/delete-data', [AdminLoginController::class, 'deleteData'])->name('admin.delete-data');
Route::get('/admin/delete-data', [AdminLoginController::class, 'showDeleteForm'])->name('admin.delete-form');
Route::get('admin/registered-staff', [AdminLoginController::class, 'showStaffDetails'])->name('admin.registered-staff');
Route::get('admin/applications/{category}', [AdminLoginController::class, 'applications'])->name('admin.applications');
Route::get('admin/troubleshooter', [AdminLoginController::class, 'troubleshoot'])->name('admin.troubleshoot');
Route::get('admin/bill', [AdminLoginController::class, 'showbill'])->name('admin.bill');
Route::post('admin/bill', [AdminLoginController::class, 'submitBill'])->name('admin.bill-submit');
Route::get('/admin/bill-filter', [AdminLoginController::class, 'billFilter'])->name('admin.bill-filter');
Route::get('/fetch-items/{billId}', [AdminLoginController::class, 'fetchItems'])->name('admin.bill-item-fetch');
Route::get('/deploy', [AdminLoginController::class, 'deployTest']);
// routes/web.php

Route::get('/admin/customer-data', [AdminLoginController::class, 'customerData'])
    ->withoutMiddleware(\App\Http\Middleware\EnableCors::class);



//service groups routes
Route::get('/service-groups', [ServiceGroupController::class, 'index'])->name('service-groups.index');
Route::post('/service-groups', [ServiceGroupController::class, 'store'])->name('service-groups.store');
Route::get('/service-groups/{id}/edit', [ServiceGroupController::class, 'edit'])->name('service-groups.edit');
Route::put('/service-groups/{id}/update', [ServiceGroupController::class, 'update'])->name('service-groups.update');
Route::post('/service-groups/{groupId}/update-visibility', [ServiceGroupController::class, 'updateVisibility'])->name('service-groups.update-visibility');
Route::post('/service-groups/{groupId}/update-availability', [ServiceGroupController::class, 'updateAvailability'])->name('service-groups.update-availability');

//service routes

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('services/{id}/delete', [ServiceController::class, 'destroy'])->name('services.delete');
Route::post('/update-visibility/{serviceId}', [ServiceController::class, 'updateVisibility'])->name('update-visibility');
Route::post('/update-availability/{serviceId}', [ServiceController::class, 'updateAvailability'])->name('update-availability');


// location routes

use App\Http\Controllers\LocationController;

Route::resource('locations', LocationController::class);

//price routes

use App\Http\Controllers\PriceController;
Route::get('/prices/{serviceId}', [PriceController::class, 'index'])->name('prices.index');
Route::get('/plan-based-prices/{serviceId}/{locationId}', [PriceController::class, 'planBasedPrices'])->name('prices.plan-based-prices');
Route::post('/prices/{serviceId}/{locationId}/store', [PriceController::class, 'store'])->name('prices.store');
Route::post('/prices/update', [PriceController::class, 'update'])->name('prices.update');
Route::put('/services/{serviceId}/update-appointment-price', [PriceController::class, 'updateAppointmentPrice'])
    ->name('services.update-appointment-price');


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
Route::get('/agent/profile', [AgentController::class, 'profile'])->name('agent.profile');
Route::get('/agent/applications/{category}', [AgentController::class, 'applications'])->name('agent.applications');
Route::get('/agent/logout', [AgentController::class, 'logout'])->name('agent.logout');
Route::get('/servicegroup/view/{serviceGroupId}', [AgentController::class, 'view'])->name('service-group.view');

//Appy Services
use App\Http\Controllers\ApplyServiceController; 

Route::get('/service/apply/direct/{id}', [ApplyServiceController::class, 'direct'])->name('service.direct-apply');
Route::post('/submit-form/{id}', [ApplyServiceController::class, 'submitForm'])->name('submitForm');

//application routes
use App\Http\Controllers\ApplicationController;
Route::post('/update-application', [ApplicationController::class, 'update'])->name('application.update');


//appointment routes

use App\Http\Controllers\AppointmentController;
Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
Route::post('/appointments/visited', [AppointmentController::class, 'markVisited'])->name('appointments.visited');
Route::post('/appointments/reject', [AppointmentController::class, 'rejectAppointment'])->name('appointments.reject');

//staff routes
use App\Http\Controllers\StaffController;
Route::get('/register-staff',[StaffController::class,'create'])->name("staffs.create");
Route::post('/register/staff', [StaffController::class, 'register'])->name('staff.register');
Route::get('/staff/logout', [StaffController::class, 'logout'])->name('staff.logout');
Route::get('/staff/login', [StaffController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffController::class, 'login'])->name('staff.login.submit');
Route::get('/staff/dashboard/{category}', [StaffController::class, 'index'])->name('staff.dashboard');

//customer routes
use App\Http\Controllers\CustomerController;
Route::get('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');
Route::get('/customer/login', [CustomerController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.submit');
Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
Route::post('/customer/reset-password', [CustomerController::class, 'resetPassword'])->name('customer.reset-password');

//statuses routes
use App\Http\Controllers\StatusController;

Route::get('statuses/{service_id}', [StatusController::class, 'index'])->name('statuses.index');
Route::post('statuses/{id}', [StatusController::class, 'update'])->name('statuses.update');
Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store');

//Home page register Agent
use App\Http\Controllers\HomeController;

Route::get('join-as-agent', [HomeController::class, 'registerAgent'])->name('home.register-agent');




