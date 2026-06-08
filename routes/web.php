<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/settings', \App\Http\Controllers\SettingsController::class)
        ->name('settings.update');

    Route::get('/pane/{paneId}/edit', [\App\Http\Controllers\AddEditPaneController::class, 'show'])
        ->name('add-edit-pane');
    Route::patch('/pane/{paneId}/edit', [\App\Http\Controllers\AddEditPaneController::class, 'update'])
        ->name('update-pane');
});

Route::get('/{username}/overlay', \App\Http\Controllers\OverlayController::class)
    ->name('overlay');

Route::prefix('/api')
    ->group(function () {
        Route::get('/trigger', \App\Http\Controllers\Api\TriggerController::class)
            ->name('trigger');
    })
    ->name('api');

require __DIR__ . '/auth.php';
