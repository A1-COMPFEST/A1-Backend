<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/courses/popular', [CourseController::class, 'popular']); // GET POPULAR COURSES WITH LIMIT 10
Route::get('/courses/enrolled/{user_id}', [CourseController::class, 'purchased']); // GET PURCHASED COURSES
Route::get('/courses/{id}', [CourseController::class, 'detail']); // GET DETAIL COURSE BY ID
Route::post('/courses', [CourseController::class, 'store']); // ADD NEW COURSE
Route::put('/courses/{id}', [CourseController::class, 'update']); // UPDATE COURSE BY ID
Route::delete('/courses/{id}', [CourseController::class, 'delete']); // DELETE COURSE BY ID

Route::get('/courses/{course_id}/contents', [ContentController::class, 'allContents']); // GET ALL CONTENTS DATA
Route::get('/courses/{course_id}/contents/{id}', [ContentController::class, 'detail']); // GET CONTENT DETAIL BY ID
Route::post('/courses/{course_id}/contents', [ContentController::class, 'store']); // ADD NEW CONTENT
Route::put('/courses/{course_id}/contents/{id}', [ContentController::class, 'update']); // UPDATE CONTENT
Route::delete('/courses/{course_id}/contents/{id}', [ContentController::class, 'delete']); // DELETE CONTENT

Route::put('/api/topup/{user_id}', [UserController::class, 'topup']); // TOPUP BALANCE

Route::post('/login', [AuthController::class, 'login']); // LOGIN USER
Route::post('/register/{role}', [AuthController::class, 'register']); // REGISTER USER & INSTRUCTOR
