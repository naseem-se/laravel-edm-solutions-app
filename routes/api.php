
<?php

use App\Http\Controllers\Facility\HomeController as FacilityHomeController;
use App\Http\Controllers\Facility\ShiftController;
use App\Http\Controllers\User\Api\AuthController;
use App\Http\Controllers\User\Api\HomeController;
use App\Http\Controllers\User\Api\PaymentController;
use App\Http\Controllers\User\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum']);


Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('verify-email', 'verifyEmail');
        Route::post('resend-otp', 'resendOtp');
        Route::post('forget-password', 'forgetPassword');
        Route::post('login', 'login');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['worker.mode'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('upload/document', 'uploadDocument');
            Route::post('reset-password', 'resetPassword');
        });
        Route::controller(HomeController::class)->group(function () {
            Route::get('/get/shifts', 'shifts');
            Route::get('/get/shifts/{id}', 'shiftDetails');
            Route::post('/claim-shift', 'claimShift');
            Route::get('/get/claimed-shift', 'getClaimedShift');
            Route::post('/shift-check-in', 'checkInShift');
            Route::post('/confirm-verification', 'confirmVerification');
            Route::post('/checkout-shift', 'checkOutShift');
            Route::post('/cancelled-shift', 'cancelledShift');
            Route::get('/location-services', 'locationServices');
        });
        Route::controller(ProfileController::class)->group(function () {
            Route::post('/profile/update', 'updateProfile');
            Route::post('/profile/change/password', 'changePassword');
            Route::get('/get/bank-account', 'getBankAccount');
            Route::post('/add/bank-account', 'addBankAccount');
            Route::get('/get/weekly-summary', 'getWeeklySummary');
            Route::post('/upload/compliance-document', 'uploadComplianceDocument');
            Route::post('/update/compliance-document', 'uploadComplianceDocument');
        }); 

        Route::get('/payment/weekly-summary', [PaymentController::class, 'getWeeklySummary']);
        Route::post('/payment/create-for-worker', [PaymentController::class, 'createPaymentForWorker']);
        Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment']);
        Route::get('/payment/history', [PaymentController::class, 'getPaymentHistory']);

        // Stripe Connect onboarding
        Route::post('/payment/onboard', [PaymentController::class, 'onboardRecipient']);
        Route::get('/payment/onboard-status', [PaymentController::class, 'checkOnboardingStatus']);
    });

    // facility mode routes
    Route::middleware(['facility.mode'])->group(function () {
        Route::controller(ShiftController::class)->group(function () {
            Route::get('/facility/get/shifts', 'getShift');
            Route::post('/create/shift', 'createShift');
            Route::post('/create/update/{id}', 'updateShift');
            Route::get('/delete/shift/{id}', 'deleteShift');
        });

        Route::controller(FacilityHomeController::class)->group(function () {
            Route::get('get/shifts-group', 'getShifts');
            Route::get('accept-pending-shift/{id}', 'acceptPendingShift');
            Route::get('filled-shift-details', 'filledShiftDetails');
            Route::get('get/complete-shift-summary/{id}', 'getCompleteShiftSummary');
            Route::get('get/staff-attendance-details', 'StaffAttendanceDetails');
            Route::get('get/payment-history', 'getPaymentHistory');
        });
    });

     Route::post('logout', [AuthController::class, 'logout']);
});
