<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Models\Rental;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Login and Registration
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Motorcycle Listing
Route::get('/motorcycles', [MotorcycleController::class, 'index'])
    ->name('motorcycles.index');

// Admin
Route::middleware(['auth', 'admin'])->group(function () {

    // Motorcycle Management
    Route::get('/motorcycles/create', [MotorcycleController::class, 'create'])
        ->name('motorcycles.create');

    Route::post('/motorcycles', [MotorcycleController::class, 'store'])
        ->name('motorcycles.store');

    Route::get('/motorcycles/{id}/edit', [MotorcycleController::class, 'edit'])
        ->name('motorcycles.edit');

    Route::put('/motorcycles/{id}', [MotorcycleController::class, 'update'])
        ->name('motorcycles.update');

    Route::delete('/motorcycles/{id}', [MotorcycleController::class, 'destroy'])
        ->name('motorcycles.destroy');

    // Driver's License Review
    Route::patch('/users/{id}/license/verify', [AdminController::class, 'verifyLicense'])
        ->name('licenses.verify');

    Route::patch('/users/{id}/license/reject', [AdminController::class, 'rejectLicense'])
        ->name('licenses.reject');

    // Rental Approval and Rejection
    Route::patch('/rentals/{id}/approve', [AdminController::class, 'approve'])
        ->name('rentals.approve');

    Route::patch('/rentals/{id}/reject', [AdminController::class, 'reject'])
        ->name('rentals.reject');

    Route::patch('/rentals/{id}/returned', [AdminController::class, 'markReturned'])
        ->name('rentals.returned');

    // Update Rental Payment Records
    Route::patch('/rentals/{id}/payments', [AdminController::class, 'updatePayments'])
        ->name('rentals.payments');
});

// Submit Driver's License Information
Route::post('/license/submit', [MotorcycleController::class, 'submitLicense'])
    ->middleware('auth')
    ->name('license.submit');

// Rent a Motorcycle
Route::post('/motorcycles/{id}/rent', [MotorcycleController::class, 'rent'])
    ->middleware('auth')
    ->name('motorcycles.rent');

// Motorcycle Details
Route::get('/motorcycles/{id}', [MotorcycleController::class, 'show'])
    ->name('motorcycles.show');

// Admin Dashboard
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

// User Dashboard
Route::get('/user', function () {
    $rentals = Rental::with('motorcycle')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('dashboards.user', compact('rentals'));
})->middleware('auth')->name('user.dashboard');