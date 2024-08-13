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

        $answersData = $answers->map(function($answer) {
            return [
                'id' => $answer->id,
                'assignment_id' => $answer->assignment_id,
                'user_id' => $answer->user_id,
                'task' => "http://localhost:8000/answers/{$answer->assignment_id}/{$answer->task}",
                'status'    => $answer->status,
                'grade' => $answer->grade
            ];
        });

        return response()->json([
            'answers' => $answersData
        ], 200);
    }

    // ADD ANSWER BY ASSIGNMENT ID
    public function store(Request $request, $assignment_id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
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
            'user_id' => $request->user_id,
            'task' => $taskName,
            'status' => 'submitted',
        ]);

        return response()->json([
            'message' => "Successfully created new answer for assignment with id = $assignment_id",
            'answer' => $answer
        ], 201);
    }

    // UPDATE ANSWER BY ANSWER ID
    public function update(Request $request, $assignment_id, $id)
    {
        $answer = Answer::find($id);

        $validator = Validator::make($request->all(), [
            'task' => 'nullable|file|mimes:pdf,mp4|max:10240',
            'status' => 'nullable',
            'grade' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'status' => $request->input('status', $answer->status),
            'grade' => $request->input('grade', $answer->grade)
        ];

        if($request->hasFile('task')) {
            $task = $request->file('task');
            $originalTaskName = $task->getClientOriginalName();
            $taskName = "$assignment_id-$originalTaskName";
            $destinationPath = public_path('answers/' . $assignment_id);
            $task->move($destinationPath, $taskName);
            $data['task'] = $taskName;
        }

        $answer->update($data);

        return response()->json([
            'message' => "Successfully update answer with id = $id",
            'answer' => $answer
        ]);
    }
}
