<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['purchased']);
    }

    // GET POPULAR COURSEs DATA WITH LIMIT=8
    public function popular()
    {
        $courses = Course::with(['instructor', 'category'])
            ->limit(8)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'slug' => $course->slug,
                    'instructor_id' => $course->instructor_id,
                    'instructor_name' => $course->instructor->name,
                    'category_id' => $course->category_id,
                    'category_name' => $course->category->name,
                    'description' => $course->description,
                    'brief' => $course->brief,
                    'image' => 'http://localhost:8000/images/'.$course->image,
                    'price' => $course->price,
                    'level' => $course->level,
                    'average_rating' => number_format($course->averageRating(), 1),
                    'created_at' => $course->created_at,
                    'updated_at' => $course->updated_at,
                ];
            });

        return response()->json([
            'message' => "Successfully get popular courses data",
            'courses' => $courses
        ]);
    }

    // GET COURSEs BY CATEGORY ID
    public function getCoursesByCategoryId($category_id)
    {
        $courses = Course::where('category_id', $category_id)
            ->with('instructor', 'category')
            ->get();

        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category ? $course->category->name : 'N/A',
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        });

        return response()->json([
            'message' => "Successfully get courses data by category with id = $category_id",
            'courses' => $coursesData,
        ]);
    }

    // GET COURSES BY DIFFICULTY LEVEL
    public function getCoursesByLevel($level)
    {
        $courses = Course::where('level', $level)
            ->with('instructor', 'category')
            ->get();

        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category->name,
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        });

        return response()->json([
            'message' => "Successfully get courses data by difficulty level = $level",
            'courses' => $coursesData,
        ]);
    }

    // GET COURSES BY RATINGS
    public function getCoursesByRatingRange(Request $request)
    {
        // get min and max rating from query parameters
        $minRating = $request->query('min', 0);
        $maxRating = $request->query('max', 5);

        // get courses with average rating within the specified range
        $courses = Course::with('ratings')
            ->with('instructor', 'category')
            ->get()
            ->filter(function ($course) use ($minRating, $maxRating) {
                $averageRating = $course->averageRating();
                return $averageRating >= $minRating && $averageRating < $maxRating;
            });

        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category->name,
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        })->values();

        return response()->json([
            'message' => "Successfully get courses with average rating between $minRating and $maxRating",
            'courses' => $coursesData,
        ]);
    }

    // GET PURCHASED COURSES
    public function purchased($user_id)
    {

        // check if user is authenticated
        $authenticatedUser = auth()->user();

        if (!$authenticatedUser || $authenticatedUser->id != $user_id) {
            return response()->json([
                'message' => 'Unauthorized access to purchased courses data',
            ], 403); // 403 Forbidden
        }
        // paginate
        $perPage = 8;
        $currentPage = request()->input('page', 1);


        $enrollments = Enrollment::where('user_id', $user_id)
            ->with('course.instructor', 'course.category')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        $courses = $enrollments->map(function ($enrollment) {
            $course = $enrollment->course;
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category->name,
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        });

        return response()->json([
            'message' => "Successfully get purchased courses data for user with id = $user_id",
            'courses' => $courses,
            'current_page' => $enrollments->currentPage(),
            'last_page' => $enrollments->lastPage(),
            'total' => $enrollments->total(),
        ]);
    }

    public function getInstructorCourse($instructorId)
    {
        $courses = Course::where('instructor_id', $instructorId)->get();

        if (!$courses) {
            return response()->json([
                'message' => "courses with id = $instructorId not found",
            ], 404);
        }

        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category->name,
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        })->values();

        return response()->json([
            'message' => "Successfully get courses with instructor id = $instructorId",
            'courses' => $coursesData,
        ]);
    }

    // GET COURSE DETAIL DATA
    public function detail($id)
    {
        $course = Course::with(['contents', 'instructor', 'category'])->find($id);

        if (!$course) {
            return response()->json([
                'message' => "Course with id = $id not found",
            ], 404);
        }

        return response()->json([
            'message' => "Successfully get course details data with id = $id",
            'courses' => [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category->name,
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => 'http://localhost:8000/images/'.$course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
                'contents' => $course->contents
            ],
        ], 200);
    }

    // ADD COURSE DATA
    public function store(Request $request)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'instructor_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'brief' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'level' => 'required',
            'price' => 'required',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // generate slug
        $name = $request->input('name');
        $slug = Str::slug($name);

        $slug = $this->generateUniqueSlug($slug);

        // upload image
        $image = $request->file('image');
        $image->move(public_path() . '//images/', $image->getClientOriginalName());
        $imageName = date('Y-m-d').$image->getClientOriginalName();

        // create new course
        $course = Course::create([
            'name' => $request->name,
            'slug' => $slug,
            'instructor_id' => $request->instructor_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'brief' => $request->brief,
            'image' => $imageName,
            'level' => $request->level,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Successfully created new course',
            'course' => $course
        ], 201);
    }

    // UPDATE COURSE DATA BY ID
    public function update(Request $request, $id)
    {
        // find course by id
        $course = Course::find($id);

        // check if course exists
        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'brief' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'nullable|numeric',
            'level' => 'nullable|string'
        ]);

        // // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // generate slug
        $slug = str($course->slug);

        if ($request->exists('name')) {
            $name = $request->input('name');
            $slug = Str::slug($name);
            $slug = $this->generateUniqueSlug($slug);
            dd('ini dalam if'.$slug);
        }
        
        
        // $slug = Str::slug($request->input('name', $course->name));
        // $slug = $this->generateUniqueSlug($slug);
        
        // data for update
        $data = [
            'name' => $request->input('name', $course->name),
            'slug' => $slug,
            'category_id' => $request->input('category_id', $course->category_id),
            'description' => $request->input('description', $course->description),
            'brief' => $request->input('brief', $course->brief),
            'price' => $request->input('price', $course->price),
            'level' => $request->input('level', $course->level)
        ];

        // handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->move(public_path() . '//images/', $image->getClientOriginalName());
            $imageName = date('Y-m-d').$image->getClientOriginalName();
            $data['image'] = $imageName;
        }

        // update the course
        $course->update($data);

        return response()->json([
            'message' => "Successfully update course with id = $id",
            'course' => $course
        ]);
    }

    // DELETE COURSE DATA BY ID
    public function delete($id)
    {
        $course = Course::find($id);

        Storage::delete('public/courses' . basename($course->image));

        $course->delete();

        return response()->json([
            'message' => "Successfully delete course with id = $id",
        ]);
    }

    // GENERATE SLUG
    private function generateUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        // loop until a unique slug is found
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // GET COURSE WITH FILTERS

    public function filterCourses(Request $request)
    {
        $query = Course::with(['instructor', 'category']);

        // Apply filters if they exist
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('difficulty')) {
            $difficulties = explode(',', $request->input('difficulty'));
            $query->whereIn('courses.level', $difficulties);
        }

        if ($request->has('min_rating')) {
            $minRating = $request->input('min_rating');
            $query->whereHas('ratings', function ($q) use ($minRating) {
                $q->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }

        // Hardcoded pagination to 10 items per page
        $perPage = 3;
        $currentPage = $request->input('page', 1); // Default to page 1 if not provided

        $courses = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Transform data for response
        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'slug' => $course->slug,
                'instructor_id' => $course->instructor_id,
                'instructor_name' => $course->instructor->name,
                'category_id' => $course->category_id,
                'category_name' => $course->category ? $course->category->name : 'N/A',
                'description' => $course->description,
                'brief' => $course->brief,
                'image' => $course->image,
                'price' => $course->price,
                'level' => $course->level,
                'average_rating' => number_format($course->averageRating(), 1),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        });

        return response()->json([
            'message' => "Successfully fetched courses",
            'courses' => $coursesData,
            'current_page' => $courses->currentPage(),
            'last_page' => $courses->lastPage(),
            'total' => $courses->total(),
        ]);
    }

}

