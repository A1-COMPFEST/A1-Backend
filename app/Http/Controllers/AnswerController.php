<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    // GET ALL ANSWER BY ASSIGNMENT ID
    public function getAllAnswers($assignment_id)
    {
        $answers = Answer::where('assignment_id', $assignment_id)->get();

        return response()->json([
            'answers' => $answers
        ], 200);
    }

    // ADD ANSWER BY ASSIGNMENT ID
    public function store(Request $request, $assignment_id, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required|file|mimes:pdf,mp4|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // upload file
        $task = $request->file('task');
        $originalTaskName = $task->getClientOriginalName();
        $taskName = "$assignment_id-$originalTaskName";
        $destinationPath = public_path('answers/' . $assignment_id);
        $task->move($destinationPath, $taskName);

        //create new content
        $answer = Answer::create([
            'assignment_id' => $assignment_id,
            'user_id' => $user_id,
            'task' => $taskName,
            'status' => 'submitted',
        ]);

        return response()->json([
            'message' => "Successfully created new answer for assignment with id = $assignment_id",
            'answer' => $answer
        ], 201);
    }
}