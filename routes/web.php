<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// Debug route for testing debugbar

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Main login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Customer-specific login routes
    Route::get('/customer/login', [AuthController::class, 'showCustomerLoginForm'])->name('customer.login');
    Route::post('/customer/login', [AuthController::class, 'customerLogin']);
    
    // Registration routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password reset routes
    Route::get('/forgot-password', [AuthController::class, 'showPasswordResetForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showPasswordResetFormWithToken'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout route (accessible to authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected routes for different user types
Route::middleware(['auth'])->group(function () {
    // Customer routes
    Route::middleware(['auth.customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('dashboard');
        
        Route::get('/parcels', function () {
            return view('customer.parcels');
        })->name('parcels');
        
        Route::get('/parcels/add', [App\Http\Controllers\ParcelController::class, 'create'])->name('parcels.add');
        
        Route::post('/parcels/add', [App\Http\Controllers\ParcelController::class, 'store'])->name('parcels.store');
        
        Route::get('/profile', function () {
            return view('customer.profile');
        })->name('profile');
    });
    
    // Staff routes
    Route::middleware(['auth.staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');
    });
    
    // Admin routes
    Route::middleware(['auth.admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
    
    // Runner routes
    Route::middleware(['auth.runner'])->prefix('runner')->name('runner.')->group(function () {
        Route::get('/dashboard', function () {
            return view('runner.dashboard');
        })->name('dashboard');
    });
});

// Default route - redirect to appropriate dashboard
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        return match($user->type) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager', 'staff' => redirect()->route('staff.dashboard'),
            'runner' => redirect()->route('runner.dashboard'),
            'user' => redirect()->route('customer.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }
    
    return redirect()->route('login');
})->name('home');

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});
