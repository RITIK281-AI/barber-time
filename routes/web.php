<?php

use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\BarberShopController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Frontend\PartnerController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Frontend\ShopController as FrontendShopController;
use App\Http\Controllers\Frontend\BarberController as FrontendBarberController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Shop\BarberController;
use App\Http\Controllers\Shop\BookingController as ShopBookingController;
use App\Http\Controllers\Shop\ServiceController;
use App\Http\Controllers\Admin\PartnerManagementController;
use App\Http\Controllers\Shop\ShopDashboardController;
use App\Http\Controllers\Shop\ShopPaymentController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Shop\ShopProfileController;
use App\Http\Controllers\Shop\ShopReviewController;
use App\Http\Controllers\Shop\ShopScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/how-it-works', [App\Http\Controllers\Frontend\HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/faq', fn() => redirect()->route('how-it-works') . '#faq')->name('faq');

// public barber profile — must be before shops group
Route::get('/shops/barbers/{id}', [FrontendBarberController::class, 'show'])->name('frontend.barbers.show');

Route::prefix('shops')->name('frontend.shops.')->group(function () {
    Route::get('/', [FrontendShopController::class, 'index'])->name('index');
    Route::get('/nearby', [FrontendShopController::class, 'nearby'])->name('nearby');

    // Become a Partner (public)
    Route::get('/become-a-partner', [PartnerController::class, 'create'])->name('partner.create');
    Route::post('/become-a-partner', [PartnerController::class, 'store'])->name('partner.store');

    Route::get('/{shop}', [FrontendShopController::class, 'show'])->name('show');
});

// Contact Us page (public)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// frontend booking flow (auth required)
Route::middleware(['auth'])->prefix('shops/{shop}')->name('frontend.bookings.')->group(function () {
    Route::get('/book', [FrontendBookingController::class, 'create'])->name('create');
    Route::get('/book/booked-slots', [FrontendBookingController::class, 'bookedSlots'])->name('booked-slots');
    Route::post('/book', [FrontendBookingController::class, 'store'])->name('store');
});

// customer authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

    Route::get('/my-bookings', function () {
        return redirect()->route('dashboard', ['tab' => 'bookings']);
    })->name('frontend.bookings.index');

    Route::post('/my-bookings/{booking}/cancel', [FrontendBookingController::class, 'cancel'])->name('frontend.bookings.cancel');
    Route::get('/my-bookings/{booking}', [FrontendBookingController::class, 'show'])->name('frontend.bookings.show');

    Route::get('/booking-success', [FrontendBookingController::class, 'success'])
        ->name('book.success');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{booking}/confirm', [PaymentController::class, 'showConfirmPage'])
            ->name('confirm');
    });

    Route::post('/user/bookings/{booking}/pay', [PaymentController::class, 'initiatePayment'])
        ->name('user.payment.initiate');
    Route::get('/user/bookings/{booking}/pay/verify', [PaymentController::class, 'verifyPayment'])
        ->name('user.payment.verify');

    Route::post('/user/bookings/{booking}/pay-cod', [PaymentController::class, 'storeCod'])
        ->name('user.payment.cod');

    Route::post('/user/bookings/{booking}/pay-fine', [PaymentController::class, 'initiateFinePayment'])
        ->name('user.fine.initiate');
    Route::get('/user/bookings/{booking}/pay-fine/verify', [PaymentController::class, 'verifyFinePayment'])
        ->name('user.fine.verify');

    Route::get('/reviews/create/{bookingId}', [ReviewController::class, 'create'])->name('frontend.reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('frontend.reviews.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('frontend.reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('frontend.reviews.update');

    Route::post('/favourites/{shop}/toggle', [App\Http\Controllers\Frontend\FavouriteController::class, 'toggle'])->name('favourites.toggle');
    Route::get('/favourites', [App\Http\Controllers\Frontend\FavouriteController::class, 'index'])->name('favourites.index');
});

// Barber Shop Owner Dashboard (role: barber_shop)
Route::middleware(['auth', 'role:barber_shop', 'shop.linked'])
    ->prefix('shop')
    ->name('shop.')
    ->group(function () {

    Route::get('/dashboard', [ShopDashboardController::class, 'index'])->name('dashboard');
    Route::get('/payments', [ShopPaymentController::class, 'index'])->name('payments.index');

    Route::resource('barbers', BarberController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    Route::resource('services', ServiceController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('bookings', ShopBookingController::class)
        ->only(['index', 'show', 'update']);
    Route::post('bookings/{booking}/mark-paid', [ShopBookingController::class, 'markPaid'])
        ->name('bookings.mark-paid');
    Route::patch('bookings/{id}/complete', [ShopBookingController::class, 'markComplete'])
        ->name('bookings.complete');
    Route::post('bookings/{id}/record-cash', [ShopBookingController::class, 'recordCash'])
        ->name('bookings.record-cash');

    Route::get('/profile', [ShopProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ShopProfileController::class, 'update'])->name('profile.update');

    Route::get('/reviews', [ShopReviewController::class, 'index'])->name('reviews.index');

    // shop schedule — weekly closed days and holiday dates
    Route::get('/schedule', [ShopScheduleController::class, 'index'])->name('schedule.index');
    Route::put('/schedule/closed-days', [ShopScheduleController::class, 'updateClosedDays'])->name('schedule.closed-days.update');
    Route::get('/schedule/holiday/confirm', [ShopScheduleController::class, 'confirmHoliday'])->name('schedule.holiday.confirm');
    Route::post('/schedule/holiday', [ShopScheduleController::class, 'storeHoliday'])->name('schedule.holiday.store');
    Route::delete('/schedule/holiday/{id}', [ShopScheduleController::class, 'destroyHoliday'])->name('schedule.holiday.destroy');
});

// Admin Panel (role: admin)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('barbershops', BarberShopController::class)
        ->except(['destroy']);

    Route::post('/barbershops/{barbershop}/suspend', [BarberShopController::class, 'suspend'])
        ->name('barbershops.suspend');

    Route::post('/barbershops/{barbershop}/restore', [BarberShopController::class, 'restore'])
        ->name('barbershops.restore');

    Route::get('/partners', [PartnerManagementController::class, 'index'])->name('partners.index');
    Route::get('/partners/{barberShop}', [PartnerManagementController::class, 'show'])->name('partners.show');
    Route::post('/partners/{barberShop}/approve', [PartnerManagementController::class, 'approve'])->name('partners.approve');
    Route::post('/partners/{barberShop}/reject', [PartnerManagementController::class, 'reject'])->name('partners.reject');

    Route::resource('barbers', AdminBarberController::class)
        ->only(['index', 'show']);

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

    Route::get('/category', [App\Http\Controllers\Admin\ServiceCategoryController::class, 'index'])->name('category.index');
    Route::post('/category/categories', [App\Http\Controllers\Admin\ServiceCategoryController::class, 'store'])->name('category.categories.store');
    Route::put('/category/categories/{serviceCategory}', [App\Http\Controllers\Admin\ServiceCategoryController::class, 'update'])->name('category.categories.update');
    Route::delete('/category/categories/{serviceCategory}', [App\Http\Controllers\Admin\ServiceCategoryController::class, 'destroy'])->name('category.categories.destroy');

    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');

    Route::get('/analytics', [App\Http\Controllers\Admin\DashboardController::class, 'analytics'])->name('analytics.index');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

    Route::get('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profile/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Contact Messages ──────────────────────────────────────────────────────
    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact.index');
    Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact.show');
    Route::patch('/contact-messages/{contactMessage}/resolve', [ContactMessageController::class, 'markResolved'])->name('contact.resolve');
    Route::patch('/contact-messages/{contactMessage}/unread', [ContactMessageController::class, 'markUnread'])->name('contact.unread');
    Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact.destroy');
    // ─────────────────────────────────────────────────────────────────────────
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
require __DIR__.'/auth.php';
