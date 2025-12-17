<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ComplianceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\TimesheetController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\Api\ShiftInvitationController;
use App\Http\Controllers\User\Api\StripeWebhookController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'pages.auth.welcome')->name('pages.welcome');
Route::middleware('guest:admin')->group(function () {
    // Route::view('/register', 'pages.auth.register')->name('pages.register');
    Route::view('/login', 'pages.auth.login')->name('pages.login');
    // Route::view('/forget-password', 'pages.auth.forget-password')->name('pages.forget-password');

});

Route::post('/login',[AdminAuthController::class,'login'])->name('admin.login.post');
Route::match(['get', 'post'], 'logout', [AdminAuthController::class, 'logout'])->middleware('admin')->name('logout');

Route::middleware('admin')->controller(ShiftController::class)->group(function () {
    Route::get('shifts', 'index')->name('pages.shifts');
    Route::get('approved/shifts', 'approvedShifts')->name('admin.approved.shifts');
    Route::patch('shifts/approved/{id}', 'approved')->name('pages.shifts.approve');
    Route::patch('shifts/cancel/{id}', 'cancelled')->name('pages.shifts.cancel');
    Route::patch('shifts/delete/{id}', 'delete')->name('pages.shifts.delete');
    Route::get('/calender-view', 'calenderView')->name('admin.shifts.calender-view');
    Route::get('/admin/shifts/{id}', 'show')->name('admin.shifts.show');
    Route::delete('/admin/shifts/{id}', 'destroy')->name('admin.shifts.destroy');
    Route::get('/open/shifts', 'openShifts')->name('admin.open.shifts');
});

Route::middleware('admin')->controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('admin.users.index');
    Route::delete('/user/delete/{id}', 'destroy')->name('admin.users.destroy');
});

Route::middleware('admin')->controller(FacilityController::class)->group(function () {
    Route::get('/facilities', 'index')->name('admin.facilities.index');
    Route::get('/pending/facilities', 'pendingFacilities')->name('admin.facilities.pending');
    Route::post('/aprove/facility/{id}','approveFacilty')->name('admin.facilities.approve');
    Route::post('/reject/facility/{id}','rejectFacilty')->name('admin.facilities.reject');
    Route::delete('/facilities/delete/{id}', 'destroy')->name('admin.facilities.destroy');
});

Route::middleware('admin')->controller(ComplianceController::class)->group(function () {
    Route::get('/compliances', 'index')->name('admin.compliances.index');
    Route::get('/pending/compliances', 'pendingCompliances')->name('admin.compliances.pending');
    Route::post('/aprove/compliances/{id}','approveCompliance')->name('admin.compliances.approve');
    Route::post('/reject/compliances/{id}','rejectCompliance')->name('admin.compliances.reject');
    // Route::delete('/facilities/delete/{id}', 'destroy')->name('admin.facilities.destroy');
    Route::get('/expiring/soon', 'expiringSoon')->name('admin.compliances.expiring.soon');
    Route::get('/expiried', 'expired')->name('admin.compliances.expired');
});

Route::middleware('admin')->controller(TimesheetController::class)->group(function () {
    Route::get('/time/sheet', 'index')->name('admin.timesheet.index');

});
Route::middleware('admin')->controller(ReportController::class)->group(function () {
    Route::get('/reports', 'index')->name('admin.reports.index');

});

Route::middleware('admin')->controller(SettingController::class)->group(function () {
    Route::get('/setting', 'index')->name('admin.setting.index');
    Route::get('/roles/permission', 'roles')->name('admin.setting.roles.index');
    Route::get('/platform/configuration', 'platformConfigs')->name('admin.setting.platform.config');
    Route::get('/content/management', 'contentManagement')->name('admin.setting.content.management');
    Route::post('/profile/update', 'updateProfile')->name('admin.profile.update');
    Route::post('/password/update', 'updatePassword')->name('admin.password.update');
    Route::post('/platform/configs/update', 'updatePlatformConfig')->name('admin.platform.config.update');
    
});

Route::middleware('admin')->controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('admin.dashboard.index');
    // Schedule endpoints
    Route::get('/schedule/weekly',  'getWeeklySchedule')->name('admin.schedule.weekly');
    Route::get('/schedule/monthly',  'getMonthlySchedule')->name('admin.schedule.monthly');    
});

Route::middleware('admin')->controller(PaymentController::class)->group(function () {
    Route::get('/payments', 'index')->name('admin.payments.index');
    Route::get('/payout/payments', 'payouts')->name('admin.payments.worker.invoices');
    Route::get('commission/tracking', 'commissionTracking')->name('admin.payments.commission.tracking');
});


Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);

Route::get('/audit/logs', [AuditLogController::class, 'index'])
    ->middleware('admin')
    ->name('admin.audit.logs');

Route::get('/invitations/{token}', [ShiftInvitationController::class, 'show'])->name('invitation.show');



// Route::view('/payments', 'pages.admin.payments')->name('pages.payments');
// Route::view('/payout-payments', 'pages.admin.payout-payments')->name('pages.payout-payments');
// Route::view('/compliance', 'pages.admin.compliance')->name('pages.compliance');
// Route::view('/credential', 'pages.admin.credential')->name('pages.credential');
// Route::view('/shifts', 'pages.admin.shifts')->name('pages.shifts');
// Route::view('/reports', 'pages.admin.reports')->name('pages.reports');
// Route::view('/document', 'pages.admin.document')->name('pages.document');
// Route::view('/support', 'pages.admin.support')->name('pages.support');
// Route::view('/notification', 'pages.admin.notification')->name('pages.notification');

// Route::view('/shift-orchestration', 'pages.admin.shift-orchestration')->name('pages.shift-orchestration');
// Route::view('/smart-match', 'pages.admin.smart-match')->name('pages.smart-match');
// Route::view('/waitlist', 'pages.admin.waitlist')->name('pages.waitlist');



