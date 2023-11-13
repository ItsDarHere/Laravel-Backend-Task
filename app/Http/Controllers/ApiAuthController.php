<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;


class ApiAuthController extends Controller
{
    function index(Request $request)
    // Login Process
    {
        $user = User::where(['email' => $request->email])->first();
        if ($user && Hash::check($request->password, $user->password)) {
                // create a token here 
                $token = Str::random(32);
                $user->login_token = $token;
                $user->save();
                $response = [
                    'status' => 200,
                    'data' => $user,
                    'token' => $token,
                ];
                return response($response, 201);
        } else {
            return response([
                'status' => 401,
                'message' => ['These credentials do not match our records.']
            ], 401);
        }
    }

    public function CreateUser(Request $request)
    // Create User 
    {
        
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => 200,
                'data' => $user,
                'message' => 'User Created Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
