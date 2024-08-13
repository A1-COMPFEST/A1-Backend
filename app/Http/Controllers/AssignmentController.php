<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    // GET ASSIGNMENT BY COURSE_ID
    public function getAssignmentByCourseId($courseId) {
        $assignments = Assignment::where('course_id', $courseId)->get();

         // Check if assignments are found
        if ($assignments->isEmpty()) {
            return response()->json([
                'message' => 'No assignments found for this course.'
            ], 404);
        }

        // Return the assignments
        return response()->json([
            'message' => 'Assignments retrieved successfully.',
            'assignments' => $assignments
        ]);
    }

    // ADD CONTENT ASSIGNMENT DATA
    public function store(Request $request, $course_id) {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'due_date' => 'required',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // create new assignment
        $assignment = Assignment::create([
            'course_id' => $course_id,
            'task' => $request->task,
            'due_date' => $request->due_date
        ]);

        return response()->json([
            'message' => "Successfully created new assignment for course with id = $course_id",
            'assignment' => $assignment
        ], 201);
    }
}
