<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

//Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    //Dashboard general
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //CRUD Proyectos
    Route::resource('projects', ProjectController::class);

    //CRUD Tareas
    Route::resource('tasks', TaskController::class);
});

