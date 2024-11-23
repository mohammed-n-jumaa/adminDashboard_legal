<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    LawyerController,
    ConsultationController,
    CommentController,
    CategoryController,
    FeedbackController,
    AppointmentController,
    MessageController,
    NotificationController,
    LegalLibraryController,
    SubscriptionController
};
use App\Http\Controllers\ProfileController;

// Redirect root URL to the login page
Route::get('/', fn() => redirect('/login'));

// Authentication routes (included from `auth.php`)
require __DIR__ . '/auth.php';

// Routes for authenticated users
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard route for regular users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit'); // Profile edit
        Route::patch('/', [ProfileController::class, 'update'])->name('update'); // Profile update
        Route::delete('/delete/{id}', [ProfileController::class, 'destroy'])->name('destroy'); // Delete profile
    });

    // Admin Panel Routes - Restricted to Admins and Super Admins Only
    Route::middleware(['role.check'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard route for Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

        // Lawyer Management
        Route::resource('lawyers', LawyerController::class);
        Route::put('lawyers/{id}', [LawyerController::class, 'update'])->name('lawyers.update');
        Route::post('lawyers/{id}/restore', [LawyerController::class, 'restore'])->name('lawyers.restore');
        Route::delete('lawyers/{id}/force-delete', [LawyerController::class, 'forceDelete'])->name('lawyers.forceDelete');
        Route::get('lawyers/show/{id}', [LawyerController::class, 'show'])->name('lawyers.show');

        // Consultation Management
        Route::resource('consultations', ConsultationController::class);
        Route::post('consultations/{id}/restore', [ConsultationController::class, 'restore'])->name('consultations.restore');
        Route::delete('consultations/{id}/force-delete', [ConsultationController::class, 'forceDelete'])->name('consultations.forceDelete');

        // Comment Management
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('index');
            Route::post('/{id}/delete', [CommentController::class, 'softDelete'])->name('softDelete');
            Route::post('/{id}/restore', [CommentController::class, 'restore'])->name('restore');
            Route::post('/{id}/force-delete', [CommentController::class, 'forceDelete'])->name('forceDelete');
        });

        // Category Management
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

        // Feedback Management
        Route::resource('feedback', FeedbackController::class);

        // Appointment Management
        Route::resource('appointments', AppointmentController::class);

        // Message Management
        Route::resource('messages', MessageController::class);

        // Notification Management
        Route::resource('notifications', NotificationController::class);

        // Legal Library Management
        Route::resource('legal-library', LegalLibraryController::class);

        // Subscription Management
        Route::resource('subscriptions', SubscriptionController::class);
    });
});
