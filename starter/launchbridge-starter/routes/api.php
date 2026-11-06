<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ApplicationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/projects/{project}/apply', [ApplicationController::class, 'store']);
    Route::get('/projects/{project}/applications', [ApplicationController::class, 'index']);
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
});
