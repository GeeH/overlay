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

    Route::get('/pane/create', [\App\Http\Controllers\AddEditPaneController::class, 'create'])
        ->name('create-pane');
    Route::post('/pane', [\App\Http\Controllers\AddEditPaneController::class, 'store'])
        ->name('store-pane');

    Route::get('/pane/{paneId}/edit', [\App\Http\Controllers\AddEditPaneController::class, 'show'])
        ->name('add-edit-pane');
    Route::patch('/pane/{paneId}/edit', [\App\Http\Controllers\AddEditPaneController::class, 'update'])
        ->name('update-pane');

    Route::controller(\App\Http\Controllers\ChecklistController::class)->group(function () {
        Route::get('/checklist/create', 'create')->name('checklists.create');
        Route::post('/checklist', 'store')->name('checklists.store');
        Route::get('/checklist/{checklistId}', 'show')->name('checklists.show');
        Route::patch('/checklist/{checklistId}', 'update')->name('checklists.update');
        Route::post('/checklist/{checklistId}/reset', 'reset')->name('checklists.reset');
        Route::post('/checklist/{checklistId}/visibility', 'toggleVisibility')->name('checklists.visibility');
    });

    Route::controller(\App\Http\Controllers\ChecklistItemController::class)->group(function () {
        Route::post('/checklist/{checklistId}/items', 'store')->name('checklists.items.store');
        Route::patch('/checklist/{checklistId}/items/{itemId}/toggle', 'toggle')->name('checklists.items.toggle');
        Route::patch('/checklist/{checklistId}/items/{itemId}/move', 'move')->name('checklists.items.move');
        Route::delete('/checklist/{checklistId}/items/{itemId}', 'destroy')->name('checklists.items.destroy');
    });
});

Route::get('/{username}/overlay', \App\Http\Controllers\OverlayController::class)
    ->name('overlay');
Route::get('/{username}/overlay/checklist/{checklistId}', [\App\Http\Controllers\ChecklistController::class, 'overlay'])
    ->name('overlay.checklist');

Route::prefix('/api')
    ->group(function () {
        Route::get('/trigger', \App\Http\Controllers\Api\TriggerController::class)
            ->name('trigger');
    })
    ->name('api');

require __DIR__ . '/auth.php';
