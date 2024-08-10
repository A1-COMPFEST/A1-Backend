<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // GET ALL USERS ENROLLED IN THE COURSE
    public function getUsersByCourseId($course_id) {
        $enrollments = Enrollment::where('course_id', $course_id)->with('user')->get();

        // Mengambil daftar pengguna dari enrollments
        $users = $enrollments->map(function ($enrollment) {
            return $enrollment->user;
        });

        return response()->json([
            'message' => "Successfully get all users enrolled for course with id = $course_id",
            'users' => $users
        ], 201);
    }
    
    // PURCHASE COURSES
    public function store($course_id, $user_id) {
        // Find course by id
        $course = Course::find($course_id);

        // Find user by id
        $user = User::find($user_id);
        
        // If course is not found
        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
        
        // If user is not found
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $price = $course->price;
        $balance = $user->balance;

        if ($balance >= $price) {
            $enrollment = Enrollment::create([
                'user_id' => $user_id,
                'course_id' => $course_id
            ]);

            $user->update([
                'balance' => $balance - $price
            ]);

            return response()->json([
                'message' => "Successfully created new enrollment for course with id = $course_id",
                'enrollment' => $enrollment
            ], 201);
        } else {
            return response()->json([
                'message' => "Insufficient balance"
            ], 422);
        }
    }
}
