<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    // ADD NEW PROGRESS
    public function store(Request $request, $course_id, $content_id, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'isFinish' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $progress = Progress::updateOrCreate(
            ['course_id' => $course_id, 'content_id' => $content_id, 'user_id' => $user_id],
            ['isFinish' => $request->isFinish]
        );

        $action = $progress->wasRecentlyCreated ? 'created' : 'updated';

        return response()->json([
            'message' => "Successfully $action progress for course with id = $course_id",
            'progress' => $progress
        ], 201);
    }

    // GET PROGRESS BY CONTENT ID
    public function checkProgress($content_id, $user_id)
    {
        $progress = Progress::where('content_id', $content_id)
            ->where('user_id', $user_id)
            ->first();

            if ($progress) {
                return response()->json([
                    'message' => 'Successfully get user progress for content with id = ' . $content_id,
                    'isFinish' => $progress->isFinish
                ]);
            } else {
                return response()->json([
                    'message' => 'No progress found for content with id = ' . $content_id,
                    'isFinish' => 0
                ], 404);
            }
    }

    // COUNT PROGRESS
    public function countProgress($course_id, $user_id)
    {
        // Count total contents for the course
        $totalContents = Content::where('course_id', $course_id)->count();

        // Count completed contents for the user in the specified course
        $completedContents = Progress::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->where('isFinish', true)
            ->count();

        // Calculate progress percentage
        $progressPercentage = $totalContents > 0 ? ($completedContents / $totalContents) * 100 : 0;

        return response()->json([
            'message' => 'Successfully get user progress percentage',
            'progress' => ['course_id' => $course_id,
            'user_id' => $user_id,
            'total_contents' => $totalContents,
            'completed_contents' => $completedContents,
            'progress_percentage' => $progressPercentage]
        ]);
    }

    public function show($course_id, $content_id, $user_id)
    {
        $progress = Progress::where([
            'course_id' => $course_id,
            'content_id' => $content_id,
            'user_id' => $user_id
        ])->first();

        if (!$progress) {
            return response()->json([
                'message' => 'Progress not found',
                'progress' => ['isFinish' => false]
            ], 404);
        }

        return response()->json([
            'message' => 'Progress found',
            'progress' => $progress
        ], 200);
    }
}
