<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/login', [AuthController::class, 'login']); // LOGIN USER
Route::post('/register/{role}', [AuthController::class, 'register']); // REGISTER USER & INSTRUCTOR
Route::post('/logout', [AuthController::class, 'logout']);

// Courses
Route::get('/courses/popular', [CourseController::class, 'popular']); // GET POPULAR COURSES WITH LIMIT 10
Route::get('/courses/category/{category_id}', [CourseController::class, 'getCoursesByCategoryId']); // GET COURSES BY CATEGORY ID
Route::get('/courses/level/{level}', [CourseController::class, 'getCoursesByLevel']); // GET COURSES BY DIFFICULTY LEVEL
Route::get('/courses/ratings', [CourseController::class, 'getCoursesByRatingRange']); // GET COURSES BY RATINGS RANGE
Route::get('/courses/enrolled/{user_id}', [CourseController::class, 'purchased']); // GET PURCHASED COURSES
Route::get('/courses/{id}', [CourseController::class, 'detail']); // GET DETAIL COURSE BY ID
Route::post('/courses', [CourseController::class, 'store']); // ADD NEW COURSE
Route::put('/courses/{id}', [CourseController::class, 'update']); // UPDATE COURSE BY ID
Route::delete('/courses/{id}', [CourseController::class, 'delete']); // DELETE COURSE BY ID

// Contents
Route::get('/courses/{course_id}/contents', [ContentController::class, 'allContents']); // GET ALL CONTENTS DATA
Route::get('/courses/{course_id}/contents/{id}', [ContentController::class, 'detail']); // GET CONTENT DETAIL BY ID
Route::post('/courses/{course_id}/contents', [ContentController::class, 'store']); // ADD NEW CONTENT
Route::put('/courses/contents/{id}', [ContentController::class, 'update']); // UPDATE CONTENT
Route::delete('/courses/contents/{id}', [ContentController::class, 'delete']); // DELETE CONTENT

// Balance
Route::put('/topup/{user_id}', [UserController::class, 'topup']); // TOPUP BALANCE

// Category
Route::post('/category', [CategoryController::class, 'store']); // ADD NEW CATEGORY

// Ratings
Route::post('/courses/{courses_id}/ratings/{user_id}', [RatingController::class, 'addRatings']); // ADD NEW RATINGS