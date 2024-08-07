<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/courses/popular', [CourseController::class, 'popular']);
Route::get('/api/courses/enrolled/{user_id}', [CourseController::class, 'purchased']);
Route::get('/courses/{id}', [CourseController::class, 'detail']);
Route::post('/register/{role}', [AuthController::class, 'register']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/delete', [AuthController::class, 'logout']);

    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'delete']);

    Route::post('/courses/{course_id}/content', [ContentController::class, 'store']);
    Route::put('/courses/{course_id}/content/{id}', [ContentController::class, 'update']);
    Route::delete('/courses/{course_id}/content/{id}', [ContentController::class, 'delete']);

   
    Route::put('/api/topup/{user_id}', [UserController::class, 'topup']);
});

