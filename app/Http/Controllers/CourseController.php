<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // GET POPULAR COURSE DATA WITH LIMIT=10
    public function popular() {
        $courses = Course::limit(10)->get();
        $courses->map(function($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'description' => $course->description,
                'syllabus' => $course->syllabus,
                'image' => $course->image,
                'price' => $course->price,
                'instructor' => [
                    'name' => $course->instructor->name,
                    'email' => $course->instructor->email,
                ],
                'created_at' => $course->created_at
            ];
        });

        return response()->json([
            'courses' => $courses
        ]);
    }

    // GET COURSE DETAIL DATA
    public function detail($id) {
        $course = Course::find($id);

        return response()->json([
            'message' => "Successfully get course details data with id = $id",
            'courses' => $course,
        ], 200);
    }

    // ADD COURSE DATA
    public function store(Request $request) {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'instructor_id' => 'required|exists:users,id',
            'description' => 'required',
            'syllabus' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // generate slug
        $name = $request->input('name');
        $slug = Str::slug($name);

        $slug = $this->generateUniqueSlug($slug);

        // upload image
        $image = $request->file('image');
        $imagePath = $image->move(public_path() . '//courses/', $image->hashName());

        // create course
        $course = Course::create([
            'name' => $request->name,
            'slug' => $slug,
            'instructor_id' => $request->instructor_id,
            'description' => $request->description,
            'syllabus' => $request->syllabus,
            'image' => $imagePath,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Successfully created new course',
            'course' => $course
        ], 201);
    }

    // UPDATE COURSE DATA BY ID
    public function update(Request $request, $id) {
        // find course by id
        $course = Course::find($id);

        // check if course exists
        if(!$course) {
            return response()->json([
                'message' => 'Course not found'
            ]);
        }   

        // define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required',
            'description' => 'sometimes|required',
            'syllabus' => 'sometimes|required',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'sometimes|required',
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // generate slug
        $slug = $course->slug;
        if($request->has('name')) {
            $name = $request->input('name');
            $slug = Str::slug($name);
            $slug = $this->generateUniqueSlug($slug);
        }

        // data for update
        $data = [
            'name' => $request->input('name', $course->name),
            'slug' => $slug,
            'description' => $request->input('description', $course->description),
            'syllabus' => $request->input('syllabus', $course->syllabus),
            'price' => $request->input('price', $course->price),
        ];

        // handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('courses', 'public');
            $data['image'] = $imagePath;
        }

        // update the course
        $course->update($data);

        return response()->json([
            'message' => "Successfully update course with id = $id",
            'course' => $course
        ]);
    }

    // DELETE COURSE DATA BY ID
    public function delete($id) {
        $course = Course::find($id);

        Storage::delete('public/courses' . basename($course->image));

        $course->delete();

        return response()->json([
            'message' => "Successfully delete course with id = $id",
        ]);
    }

    private function generateUniqueSlug($slug) {
        $originalSlug = $slug;
        $count = 1;

        // loop until a unique slug is found
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

}

