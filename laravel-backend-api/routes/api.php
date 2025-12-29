<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rutas para Categories (categorías)
Route::apiResource('categories', CategoryController::class);

// Rutas para Posts (posts del blog)
Route::apiResource('posts', PostController::class);

// Rutas para Users (usuarios)
Route::apiResource('users', UserController::class);

