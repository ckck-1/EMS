<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/past-events', [HomeController::class, 'pastEvents'])->name('events.past');

// Event routes (public view)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Authentication Routes
Auth::routes();

// Protected routes
Route::middleware('auth')->group(function () {
    // Event RSVP routes
    Route::post('/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
    Route::delete('/events/{event}/rsvp', [EventController::class, 'cancelRsvp'])->name('events.rsvp.cancel');

    // Organizer routes
    Route::middleware('can:viewOrganizer')->group(function () {
        Route::resource('events', EventController::class)->except(['show']);
        Route::get('/events/{event}/attendees', [EventController::class, 'attendees'])->name('events.attendees');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('can:viewAdmin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->except(['create', 'store']);
    });
});