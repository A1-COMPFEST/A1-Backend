<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    // GET ALL LIST OF STUDENTS SUBMITTED ASSIGNMENT
    

    // ADD CONTENT ASSIGNMENT DATA
    public function store(Request $request) {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'content_id' => 'required',
            'user_id' => 'required',
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
            'course_id' => $request->course_id,
            'content_id' => $request->content_id,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'message' => "Successfully created new assignment for course content with id = $request->content_id",
            'course' => $assignment
        ], 201);
    }
}
