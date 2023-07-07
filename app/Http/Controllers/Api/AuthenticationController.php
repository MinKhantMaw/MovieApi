<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Laravel\Passport\Token;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return ApiResponse::success('User created successfully', ['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail('Validation Error', $validator->errors()->all(), 422);
        }

        $user = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($user)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return ApiResponse::success('Login Successfully', ['user' => $user, 'token' => $token], 200);
        } else {
            return ApiResponse::fail('User Not fount in our record', ['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {

        $request->user()->token()->revoke();
        return ApiResponse::success('Successfully logged out', [], 200);
    }
}
