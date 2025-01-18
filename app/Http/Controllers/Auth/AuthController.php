<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = UserRequest::validated($request->all());

        $user = User::create(array_merge(
            $validated,
            [
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'password' => Hash::make($validated['password']),
            ]
        ));

        return $this->apiResponse([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }
}
