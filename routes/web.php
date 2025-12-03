<?php

use App\Http\Controllers\LandingController;
use App\Livewire\Dashboard;
use App\Livewire\ProjectsDashboard;
use Illuminate\Support\Facades\Route;

//Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    //Vista general una vez registrado
    Route::get('/dashboard', ProjectsDashboard::class)
        ->name('dashboard');

    // Vista al pinchar en un proyecto
    Route::get('/boards/{board}', Dashboard::class)
        ->name('boards.show');
});
