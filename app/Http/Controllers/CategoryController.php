<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // ADD NEW CATEGORY
    public function store(Request $request) {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // create new category
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Successfully created new course',
            'category' => $category
        ], 201);
    }
}
