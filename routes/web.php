<?php
use App\Http\Controllers\UrlController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Default route
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// URL Shortener resource routes
Route::resource('Url', UrlController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {
    Route::get('/urls', [UrlController::class, 'index'])->name('Url.index');
    Route::post('/urls', [UrlController::class, 'store'])->name('Url.store');
    Route::post('/urls/{id}/increment-copy-count', [UrlController::class, 'incrementCopyCount']);
});

// Wildcard route for short URL redirection (MUST BE LAST)
Route::get('/{shortUrl}', [UrlController::class, 'redirect'])->name('Url.redirect');
