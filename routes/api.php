<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Facility\HomeController as FacilityHomeController;
use App\Http\Controllers\Facility\ProfileController as FacilityProfileController;
use App\Http\Controllers\Facility\ReviewController;
use App\Http\Controllers\Facility\ShiftController;
use App\Http\Controllers\User\Api\AuthController;
use App\Http\Controllers\User\Api\HomeController;
use App\Http\Controllers\User\Api\PaymentController;
use App\Http\Controllers\User\Api\PrivacySettingsController;
use App\Http\Controllers\User\Api\ProfileController;
use App\Http\Controllers\User\Api\ShiftInvitationController;
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
        Route::post('login', 'login')->name('login');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['worker.mode'])->group(function () {

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
            Route::post('/filter/shifts', 'filterShifts');
            Route::get('/weekly/timesheet', 'getWeeklyTimesheet');
            Route::get('/timesheet/week/{startDate}', 'getTimesheetByWeek');
            Route::get('/timesheet/monthly/{month?}/{year?}', 'getMonthlyTimesheet');

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
        Route::get('/payment/history', [PaymentController::class, 'getPaymentHistory']);
        // Stripe Connect onboarding
        Route::post('/payment/onboard', [PaymentController::class, 'onboardRecipient']);

        // Worker gets all their reviews
        Route::get('/get/reviews', [ReviewController::class, 'getMyReviews']);

        // Worker gets detailed view of all reviews
        Route::get('/get/reviews/detailed', [ReviewController::class, 'getMyReviewsDetailed']);

        // Worker gets rating summary
        Route::get('/get/rating-summary', [ReviewController::class, 'getMyRatingSummary']);

        // Worker gets reviews from specific facility
        Route::get('/get/reviews/facility/{facilityId}', [ReviewController::class, 'getReviewsByFacility']);

    });

    // facility mode routes
    Route::middleware(['facility.mode'])->group(function () {
        Route::controller(ShiftController::class)->group(function () {
            Route::get('/facility/get/shifts', 'getShift');
            Route::post('/create/shift', 'createShift');
            Route::post('/create/bulk/shift', 'createBulkShift');
            Route::post('/create/update/{id}', 'updateShift');
            Route::get('/delete/shift/{id}', 'deleteShift');
            Route::post('/reject/shift/{id}', 'rejectShift');
        });

        Route::controller(FacilityHomeController::class)->group(function () {
            Route::get('get/shifts-group', 'getShifts');
            Route::get('accept-pending-shift/{id}', 'acceptPendingShift');
            Route::get('filled-shift-details', 'filledShiftDetails');
            Route::get('get/complete-shift-summary/{id}', 'getCompleteShiftSummary');
            Route::get('get/staff-attendance-details', 'StaffAttendanceDetails');
            Route::get('get/payment-history', 'getPaymentHistory');
            Route::get('get/workers-list', 'getWorkersList');
            Route::get('get/worker-details/{id}', 'getWorkerDetails');
            Route::get('/get/report/stats','getStatsReport');
            Route::get('/get/report/payments','getPaymentsReport');
        });

        Route::controller(FacilityProfileController::class)->group(function () {
            Route::get('/facility/profile', 'getProfile');
            Route::post('/facility/profile/update', 'updateProfile');
            Route::get('facility/detail', 'getFacilityDetail');
            Route::post('/facility/detail/update', 'updateFacilityDetail');
        });

        Route::post('/payment/create-for-worker', [PaymentController::class, 'createPaymentForWorker']);
        Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment']);

        // Facility submits review for worker
        Route::post('/submit/reviews', [ReviewController::class, 'submitReview']);

        // Facility updates their own review
        Route::put('/update/reviews/{reviewId}', [ReviewController::class, 'updateReview']);

        // Facility deletes their own review
        Route::delete('/delete/reviews/{reviewId}', [ReviewController::class, 'deleteReview']);

        // Facility gets reviews they submitted
        Route::get('/get/reviews', [ReviewController::class, 'getFacilityReviews']);

    });

    Route::post('/delete/account', [AuthController::class, 'deleteAccount']);
    Route::post('/password/change', [AuthController::class, 'changePassword']);
    Route::get('/payment/onboard-status', [PaymentController::class, 'checkOnboardingStatus']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::controller(AuthController::class)->group(function () {
        Route::post('upload/document', 'uploadDocument');
        Route::post('reset-password', 'resetPassword');
    });
});

Route::middleware('auth:sanctum')->group(function () {  
    // Get all privacy settings
    Route::get('/privacy-settings', [PrivacySettingsController::class, 'getSettings']);
    // Profile Visibility
    Route::put('/privacy-settings/profile-visibility', [PrivacySettingsController::class, 'updateProfileVisibility']);
    // Two-Factor Authentication
    Route::post('/privacy-settings/two-factor/enable', [PrivacySettingsController::class, 'enableTwoFactor']);
    Route::post('/privacy-settings/two-factor/verify', [PrivacySettingsController::class, 'verifyTwoFactorCode']);
    Route::post('/privacy-settings/two-factor/disable', [PrivacySettingsController::class, 'disableTwoFactor']);   
    // Biometric Lock
    Route::put('/privacy-settings/biometric-lock', [PrivacySettingsController::class, 'updateBiometricLock']);
});

Route::middleware('auth:sanctum')->group(function () {
    
    // FACILITY - Send invitation
    Route::post('/send/shift-invitations', [ShiftInvitationController::class, 'sendInvitation'])->middleware('facility.mode');

    // WORKER - Get pending invitations
    Route::get('/shift-invitations/pending', [ShiftInvitationController::class, 'getPendingInvitations'])->middleware('worker.mode');

    // WORKER - Accept invitation
    Route::post('/shift-invitations/{invitationId}/accept', [ShiftInvitationController::class, 'acceptInvitation'])->middleware('worker.mode');

    // WORKER - Reject invitation
    Route::post('/shift-invitations/{invitationId}/reject', [ShiftInvitationController::class, 'rejectInvitation'])->middleware('worker.mode');

    // WORKER - Get invitation history
    Route::get('/shift-invitations/history', [ShiftInvitationController::class, 'getInvitationHistory'])->middleware('worker.mode');
});


Route::get('/contents', [SettingController::class, 'getContent']);
Route::post('/contents', [SettingController::class, 'updateContent']);

Route::get('payment/onboard-return', [PaymentController::class, 'handleOnboardReturn']);
Route::get('payment/onboard-refresh', [PaymentController::class, 'handleOnboardRefresh']);

// Get any worker's public rating
Route::get('/workers/{workerId}/rating', [ReviewController::class, 'getWorkerRating']);

// Get any worker's reviews count
Route::get('/workers/{workerId}/reviews-count', [ReviewController::class, 'getWorkerReviewsCount']);