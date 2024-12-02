<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProjectController;
use App\Http\Middleware\RequestNumMiddleware;

Route::prefix('users')->group(function () {
	Route::post('/', [UserController::class, 'create']);
	Route::get('/{id}', [UserController::class, 'read']);
	Route::put('/{id}', [UserController::class, 'update']);
	Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('projects')->group(function () {
    Route::post('/', [ProjectsController::class, 'create']);
    Route::get('/', [ProjectsController::class, 'read']);
    Route::put('/', [ProjectsController::class, 'update']);
    Route::delete('/', [ProjectsController::class, 'destroy']);
});

Route::prefix('/user-project')->group(function () {
    Route::post('/create', [UserProjectController::class, 'create']);
    Route::get('/all', [UserProjectController::class, 'read']);
	Route::get('/{id}', [UserProjectController::class, 'readById']);
    Route::put('/update/{id}', [UserProjectController::class, 'update']);
    Route::delete('/delete/{id}', [UserProjectController::class, 'destroy']);
})->middleware([RequestNumMiddleware::class]);
