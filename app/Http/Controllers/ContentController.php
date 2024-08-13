<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    // GET ALL CONTENT DATA IN COURSE BY COURSE_ID
    public function allContents($course_id) {
        $contents = Content::where('course_id', $course_id)->get();

        $contentsData = $contents->map(function($content) {
            return [
                'id' => $content->id,
                'course_id' => $content->course_id,
                'title' => $content->title,
                'description' => $content->description,
                'file' => "http://localhost:8000/contents/{$content->course_id}/{$content->file}"
            ];
        });

        return response()->json([
            'message' => "Successfully get courses content data",
            'contents' => $contentsData
        ]);
    }

    // GET CONTENT DATA BY ID
    public function detail($course_id, $id) {
        $content = Content::find($id);

        return response()->json([
            'message' => "Successfully get content data with id = $id",
            'content' => [
                'id' => $content->id,
                'course_id' => $content->course_id,
                'title' => $content->title,
                'description' => $content->description,
                'file' => "http://localhost:8000/contents/{$content->course_id}/{$content->file}"
            ]
        ]);
    }

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
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // upload file
        $file = $request->file('file');
        $originalFileName = $file->getClientOriginalName();
        $fileName = "$course_id-$originalFileName";
        $destinationPath = public_path('contents/' . $course_id);
        $file->move($destinationPath, $fileName);

        //create new content
        $content = Content::create([
            'course_id' => $course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $fileName
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
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,mp4|max:10240',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // data for update
        $data = [
            'title' => $request->input('title', $content->title),
            'description' => $request->input('description', $content->description),
        ];

        // handle file upload if provided
        if($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $fileName = "$course_id-$originalFileName";
            $destinationPath = public_path('contents/' . $course_id);
            $file->move($destinationPath, $fileName);
            $data['file'] = $fileName;
        }

        $content->update($data);

        return response()->json([
            'message' => "Successfully update content with id = $id",
            'course' => $content
        ]);
    }

    // DELETE COURSE CONTENT DATA BY ID
    public function delete($id) {
        $content = Content::find($id);

        Storage::delete('public/contents' . basename($content->image));

        $content->delete();

        return response()->json([
            'message' => "Successfully delete content with id = $id",
        ]);
    }
}