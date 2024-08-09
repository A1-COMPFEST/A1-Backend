<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    // ADD NEW RATINGS
    public function addRatings(Request $request, $course_id, $user_id) {
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
