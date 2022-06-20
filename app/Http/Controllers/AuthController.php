<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Src\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user or !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('email or password incorrect', 401);
        }
        $token = $user->createToken($request->email)->plainTextToken;
        return ApiResponse::data([
            'id'=> $user->id,
            'name'=> $user->name,
            'email'=> $user->email,
            'is_admin'=> $user->is_admin,
            'token'=> $token,
        ]);
    }

    public function getme(Request $request)
    {
        return ApiResponse::data($request->user());
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'is_admin'=> false
        ]);

        $token = $user->createToken($request->email)->plainTextToken;
        return ApiResponse::data([
            'id'=> $user->id,
            'name'=> $user->name,
            'email'=> $user->email,
            'is_admin'=> $user->is_admin,
            'token'=> $token,
        ]);
    }
}
