<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Team\TaskController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
    return Inertia::render('Auth/Login');
});

Route::middleware(['auth', 'verified', 'role:Admin|Project Manager'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('projects', ProjectController::class);

    Route::get('/generate-report', [ReportController::class, 'generateReport'])->name('generate.report');

    Route::get('/projects/{id}/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('project.tasks');
    Route::post('/projects/{project}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('project.tasks.store');
    Route::put('/projects/{project}/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('project.tasks.update');

    Route::resource('users', UsersController::class);
});

Route::middleware(['auth', 'verified', 'role:Team Member'])->group(function () {
    Route::get('/user/tasks', [TaskController::class, 'index'])->name('team.member.home');
    Route::get('/user/tasks/{id}', [TaskController::class, 'show'])->name('task.show');
    Route::patch('/user/tasks/{id}', [TaskController::class, 'update'])->name('task.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
