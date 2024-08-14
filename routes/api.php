<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/login', [AuthController::class, 'login']); // LOGIN USER
Route::post('/register/{role}', [AuthController::class, 'register']); // REGISTER USER & INSTRUCTOR
Route::post('/logout', [AuthController::class, 'logout'])->middleware('user');

// User Middleware
Route::middleware('user')->group(function() {
    // Courses
    Route::get('/courses/popular', [CourseController::class, 'popular']); // GET POPULAR COURSES WITH LIMIT 10
    Route::get('/courses/category/{category_id}', [CourseController::class, 'getCoursesByCategoryId']); // GET COURSES BY CATEGORY ID
    Route::get('/courses/level/{level}', [CourseController::class, 'getCoursesByLevel']); // GET COURSES BY DIFFICULTY LEVEL
    Route::get('/courses/ratings', [CourseController::class, 'getCoursesByRatingRange']); // GET COURSES BY RATINGS RANGE
    Route::get('/courses/enrolled/{user_id}', [CourseController::class, 'purchased']); // GET PURCHASED COURSES
    Route::get('/courses/instructor/{instructor_id}', [CourseController::class, 'getInstructorCourse']); // GET COURSES BY INSTRUCTOR ID
    Route::get('/courses/filter', [CourseController::class, 'filterCourses']);
    Route::get('/courses/{id}', [CourseController::class, 'detail']); // GET DETAIL COURSE BY ID
    Route::post('/courses', [CourseController::class, 'store']); // ADD NEW COURSE
    Route::patch('/courses/{id}', [CourseController::class, 'update']); // UPDATE COURSE BY ID
    Route::delete('/courses/{id}', [CourseController::class, 'delete']); // DELETE COURSE BY ID
    
    // Contents
    Route::get('/courses/{course_id}/contents', [ContentController::class, 'allContents']); // GET ALL CONTENTS DATA
    Route::get('/courses/{course_id}/contents/{id}', [ContentController::class, 'detail']); // GET CONTENT DETAIL BY ID
    Route::post('/courses/{course_id}/contents', [ContentController::class, 'store']); // ADD NEW CONTENT
    Route::patch('/courses/{course_id}/contents/{id}', [ContentController::class, 'update']); // UPDATE CONTENT
    Route::delete('/courses/contents/{id}', [ContentController::class, 'delete']); // DELETE CONTENT
    
    // Balance
    Route::post('/topup/{user_id}', [UserController::class, 'topup']); // TOPUP BALANCE
    Route::get('/balance/{user_id}', [UserController::class, 'getBalance']); // GET BALANCE
    
    // Category
    Route::post('/category', [CategoryController::class, 'store']); // ADD NEW CATEGORY
    Route::get('/category', [CategoryController::class, 'getUniqueCategories']); // GET UNIQUE CATEGORIES
    
    // Ratings
    Route::get('/courses/{course_id}/ratings', [RatingController::class, 'getRatingsForCourse']); // GET RATINGS BY COURSE ID
    Route::post('/courses/{course_id}/ratings/{user_id}', [RatingController::class, 'addRatings']); // ADD NEW RATINGS
    
    // Assignments
    Route::get('/courses/{course_id}/assignments', [AssignmentController::class, 'getAssignmentBycourse_id']); // GET ASSIGNMENT BY COURSE ID
    Route::get('/assignments/{instructor_id}', [AssignmentController::class, 'getAssignmentByInstructorId']); // GET ASSIGNMENT BY INSTRUCTOR ID
    Route::get('assignments/deadline/{assignment_id}/{user_id}', [AnswerController::class, 'getAssignmentDeadline']); // GET ASSIGNMENT DEADLINE
    Route::post('/courses/{course_id}/assignments', [AssignmentController::class, 'store']);
    Route::get('assignments/{id}', [AssignmentController::class, 'show']);

    // Answer
    Route::get('/assignments/{assignment_id}/answers/{user_id}', [AnswerController::class, 'getAnswer']); // GET ANSWER BY ASSIGNMENT ID AND USER ID
    Route::get('/assignments/{assignment_id}/answers', [AnswerController::class, 'getAllAnswers']); // GET ALL ANSWER BY ASSIGNMENT ID
    Route::post('/assignments/{assignment_id}/answers', [AnswerController::class, 'store']); // ADD NEW ANSWER
    Route::patch('/assignments/{assignment_id/answers/{answer_id}', [AnswerController::class, 'update']);

    // Enrollment
    Route::get('enrollment/{course_id}', [EnrollmentController::class, 'getUsersByCourseId']); // GET ALL USERS ENROLLED IN THE COURSE
    Route::post('/enrollment/{course_id}/{user_id}', [EnrollmentController::class, 'store']); // ADD NEW ENROLLMENT
});