<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    // ADD COURSE CONTENT DATA
    public function store(Request $request, $course_id) {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|file|mimes:pdf,mp4|max:10240',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload file
        $file = $request->file('file');
        $filePath = $file->move(public_path() . '//contents/', $file->hashName());

        //create content
        $content = Content::create([
            'course_id' => $course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $filePath
        ]);

        return response()->json([
            'message' => "Successfully created new content for course with id = $course_id",
            'content' => $content
        ], 201);
    }

    // UPDATE COURSE CONTENT DATA BY ID
    public function update(Request $request, $course_id, $id) {
        $content = Content::find($id);

        // define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'file' => 'sometimes|required|file|mimes:pdf,mp4|max:10240',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // data for update
        $data = [
            'title' => $request->input('title', $content->title),
            'description' => $request->input('description', $content->description),
        ];
        
        // handle file upload if provided
        if($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->move(public_path() . '//contents/', $file->hashName());
        }

        $content->update($data);

        return response()->json([
            'message' => "Successfully update content with id = $id",
            'course' => $content
        ]);
    }

    // DELETE COURSE CONTENT DATA BY ID
    public function delete($course_id, $id) {
        $content = Content::find($id);

        Storage::delete('public/contents' . basename($content->image));

        $content->delete();

        return response()->json([
            'message' => "Successfully delete content with id = $id",
        ]);
    }
}
