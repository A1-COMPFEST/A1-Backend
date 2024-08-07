<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // TOP UP BALANCE
    public function topup(Request $request, $user_id) {
        // find user by id
        $user = User::find($user_id);

        // if user is not found
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric|min:0'
        ]);

        // check if validation fails
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        // update user balance
        $user->update([
            'balance' => $request->balance
        ]);

        return response()->json([
            'message' => "Successfully top up balance for user with id = $user_id",
            'user' => $user
        ]);
    }
}
