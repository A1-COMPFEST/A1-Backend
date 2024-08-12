<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // TOP UP BALANCE
    public function topup(Request $request, $user_id)
    {
        // Find user by id
        $user = User::find($user_id);

        // If user is not found
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric|min:0'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user balance
        $user->balance += $request->balance;
        $user->save();

        return response()->json([
            'message' => "Successfully topped up balance for user with id = $user_id",
            'user' => $user
        ]);
    }
    public function getBalance($user_id)
    {
        // Find user by id
        $user = User::find($user_id);

        // If user is not found
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched user balance',
            'balance' => $user->balance
        ]);
    }


}
