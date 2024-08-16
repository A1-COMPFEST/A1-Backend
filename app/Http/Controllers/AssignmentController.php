<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    // GET ASSIGNMENT BY COURSE_ID
    public function getAssignmentByCourseId($course_id)
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
                'task' => env('BASE_URL') . 'assignmenmts/' . "{$assignment->course_id}/{$assignment->task}",
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
                'task' => env('BASE_URL') . 'assignmenmts/' . "{$assignment->course_id}/{$assignment->task}",
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
            'task' => env('BASE_URL') . 'assignmenmts/' . "{$assignment->course_id}/{$assignment->task}",
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

    // UPDATE ASSIGNMENT DATA BY ID
    public function update(Request $request, $course_id, $id)
    {
        $assignment = Assignment::find($id);
        
        // define validation rules
        $validator = Validator::make($request->all(), [
            'task' => 'nullable|file|mimes:pdf,mp4|max:10240',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // $data = [
        //     'title' => $request->input('title', $assignment->title),
        //     'description' => $request->input('description', $assignment->description),
        //     'due_date' => $request->input('due_date', $assignment->due_date)
        // ];

        $data = $request->all();

        // upload file
        if ($request->hasFile('task')) {
            $task = $request->file('task');
            $originalTaskName = $task->getClientOriginalName();
            $taskName = "$course_id-$originalTaskName";
            $destinationPath = public_path('assignments/' . $course_id);
            $task->move($destinationPath, $taskName);
            $data['task'] = $taskName;
        }

        // create new assignment
        $assignment->update($data);

        return response()->json([
            'message' => "Successfully update new assignment for course with id = $id",
            'assignment' => $assignment
        ], 201);
    }

    // DELETE ASSIGNMENT DATA BY ID
    public function delete($id)
    {
        $assignment = Assignment::find($id);

        $assignment->delete();

        return response()->json([
            'message' => "Successfully delete assignment with id = $id",
        ]);
    }
}