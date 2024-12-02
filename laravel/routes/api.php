<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/{id}', [UserController::class, 'read']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

// Project Routes
Route::prefix('projects')->group(function () {
    Route::post('/', [ProjectsController::class, 'create']);
    Route::get('/', [ProjectsController::class, 'read']);
    Route::put('/', [ProjectsController::class, 'update']);
    Route::delete('/', [ProjectsController::class, 'destroy']);
});
