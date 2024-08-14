<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    // GET ASSIGNMENT BY COURSE_ID
    public function getAssignmentBycourse_id($course_id)
    {
        $assignments = Assignment::where('course_id', $course_id)->get();

        // Check if assignments are found
        if ($assignments->isEmpty()) {
            return response()->json([
                'message' => 'No assignments found for this course.'
            ], 404);
        }

        $assignmentData = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'course_id' => $assignment->course_id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'task' => "http://localhost:8000/assignments/{$assignment->course_id}/{$assignment->task}",
                'due_date' => $assignment->due_date
            ];
        });

        // Return the assignments
        return response()->json([
            'message' => "Successfully get assignments data for course id = $course_id",
            'assignments' => $assignmentData
        ]);
    }

    // GET ASSIGNMENT BY INSTRUCTOR ID
    public function getAssignmentByInstructorId($instructor_id){
        $assignments = Assignment::where('instructor_id',$instructor_id)->get();

        // Check if assignments are found
        if ($assignments->isEmpty()) {
            return response()->json([
                'message' => 'No assignments found for this course.'
            ], 404);
        }

        $assignmentData = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'course_id' => $assignment->course_id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'task' => "http://localhost:8000/assignments/{$assignment->course_id}/{$assignment->task}",
                'due_date' => $assignment->due_date
            ];
        });

        // Return the assignments
        return response()->json([
            'message' => "Successfully get assignment data for instructor id = $instructor_id",
            'assignments' => $assignmentData
        ]);
    }

    public function show($id)
    {
        // Retrieve the assignment by its ID
        $assignment = Assignment::find($id);

        // Check if the assignment exists
        if (!$assignment) {
            return response()->json([
                'message' => 'Assignment not found.'
            ], 404);
        }

        // Prepare the assignment data
        $assignmentData = [
            'id' => $assignment->id,
            'course_id' => $assignment->course_id,
            'title' => $assignment->title,
            'description' => $assignment->description,
            'task' => "http://localhost:8000/assignments/{$assignment->course_id}/{$assignment->task}",
            'due_date' => $assignment->due_date
        ];

        // Return the assignment data
        return response()->json([
            'message' => 'Assignment retrieved successfully.',
            'assignment' => $assignmentData
        ]);
    }

    // ADD CONTENT ASSIGNMENT DATA
    public function store(Request $request, $course_id)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'task' => 'required|file|mimes:pdf,mp4|max:10240',
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // upload file
        $task = $request->file('task');
        $originalTaskName = $task->getClientOriginalName();
        $taskName = "$course_id-$originalTaskName";
        $destinationPath = public_path('assignments/' . $course_id);
        $task->move($destinationPath, $taskName);

        // create new assignment
        $assignment = Assignment::create([
            'course_id' => $course_id,
            'task' => $taskName,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date
        ]);

        return response()->json([
            'message' => "Successfully created new assignment for course with id = $course_id",
            'assignment' => $assignment
        ], 201);
    }
}