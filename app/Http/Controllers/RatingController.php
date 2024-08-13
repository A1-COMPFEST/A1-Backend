<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    // GET COURSE RATINGS
    public function getRatingsForCourse($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => "Course with id = $courseId not found",
            ], 404);
        }

        $ratings = Rating::with('user')
            ->where('course_id', $courseId)
            ->get()
            ->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'rating' => $rating->rating,
                    'comments' => $rating->comments,
                    'user_name' => $rating->user->name,
                    'created_at' => $rating->created_at,
                    'updated_at' => $rating->updated_at,
                ];
            });

        return response()->json([
            'message' => "Successfully retrieved ratings for course id = $courseId",
            'ratings' => $ratings,
        ], 200);
    }

    // ADD NEW RATINGS
    public function addRatings(Request $request, $course_id, $user_id)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'comments' => 'required',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $rating = Rating::create([
            'course_id' => $course_id,
            'user_id' => $user_id,
            'rating' => $request->rating,
            'comments' => $request->comments
        ]);

        return response()->json([
            'message' => "Successfully created new rating for course with id = $course_id",
            'rating' => $rating
        ], 201);
    }
}