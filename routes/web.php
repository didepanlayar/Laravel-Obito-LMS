<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [FrontController::class, 'index'])->name('front.index');

// Pricing
Route::get('/pricing', [FrontController::class, 'pricing'])->name('front.pricing');

// Midtrans
Route::match(['get', 'post'], '/booking/payment/midtrans/notification', [FrontController::class, 'paymentMidtransNotification'])->name('front.payment.midtrans.notification');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student
    Route::middleware('role:student')->group(function () {
        // Subscription
        Route::get('/dashboard/subscription', [DashboardController::class, 'subscription'])->name('dashboard.subscription');
        Route::get('/dashboard/subscription/{transaction}', [DashboardController::class, 'subscriptionDetail'])->name('dashboard.subscription.detail');

        // Course
        Route::get('/dashboard/courses', [CourseController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/courses/{course:slug}', [CourseController::class, 'detail'])->name('dashboard.courses.detail');
        Route::get('/dashboard/courses/search', [CourseController::class, 'searchCourse'])->name('dashboard.courses.search');

        Route::middleware('check.subscription')->group(function () {
            Route::get('/dashboard/courses/join/{course:slug}', [CourseController::class, 'join'])->name('dashboard.course.join');
            Route::get('/dashboard/courses/learning/{course:slug}/{courseSection}/{sectionContent}', [CourseController::class, 'learning'])->name('dashboard.course.learning');
            Route::get('/dashboard/courses/learning/{course:slug}/finished', [CourseController::class, 'learningFinished'])->name('dashboard.course.learning.finished');
        });

        // Checkout
        Route::get('/checkout/success', [FrontController::class, 'checkoutSuccess'])->name('front.checkout.success');
        Route::get('/checkout/{pricing}', [FrontController::class, 'checkout'])->name('front.checkout');
        Route::get('/booking/payment/midtrans', [FrontController::class, 'paymentStoreMidtrans'])->name('front.payment.store.midtrans');
    });
});

require __DIR__.'/auth.php';
