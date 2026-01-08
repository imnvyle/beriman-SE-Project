<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

// Redirect the very first page to the dashboard
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        // Kalau dah login, terus ke dashboard
        return redirect()->route('dashboard');
    }
    // Kalau guest, tunjuk landing page
    return view('welcome'); // atau view('guest') kalau awak buat guest.blade.php
});


Route::middleware(['auth', 'verified'])->group(function () {
    // Page 1: Homepage
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // Page 2: Favourites
    Route::get('/favourites', [EventController::class, 'favourites'])->name('events.favourites');

    // Page 3: Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])
     ->name('calendar.index');

     Route::middleware(['auth', 'verified'])->group(function () { Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index'); Route::get('/calendar/export', [CalendarController::class, 'export'])->name('calendar.export'); });


    // Page 4 & 5: Post Events
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    // Page 6: Manage your events
    Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.myEvents');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Page 7 & Detail View
    Route::get('/all-events', [EventController::class, 'all'])->name('events.all');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/favourite', [EventController::class, 'toggleFavourite'])->name('events.toggle');
    Route::post('/events/{event}/report', [EventController::class, 'report'])->name('events.report');

    // Profile routes (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';