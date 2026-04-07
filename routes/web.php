<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['can:admin'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });

    Route::get('/work-log', [WorkLogController::class, 'index'])->name('work-log.index');
    Route::post('/work-log/punch', [WorkLogController::class, 'punch'])->name('work-log.punch');
    Route::get('/work-log/history', [WorkLogController::class, 'history'])->name('work-log.history');

});

require __DIR__.'/auth.php';