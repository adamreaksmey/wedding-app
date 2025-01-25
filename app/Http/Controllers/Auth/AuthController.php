<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = UserRequest::validated($request->all());

        $payload = array_merge(
            $validated,
            [
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'password' => Hash::make($validated['password']),
            ]
        );

        $user = User::create($payload);

        $token = JWTAuth::fromUser($user);

        return $this->apiResponse([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        UserRequest::login($credentials);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->apiResponse(['error' => 'Invalid credentials'], ["code" => 400]);
            }
        } catch (JWTException $e) {
            return $this->apiResponse(['error' => 'Could not create token']);
        }

        return $this->apiResponse(['token' => $token]);
    }

    public function logout()
    {
        try {
            // Get the current token
            $token = JWTAuth::getToken();

            // Check if token exists
            if (!$token) {
                return $this->apiResponse(['message' => 'No token found']);
            }

            // Invalidate the token
            JWTAuth::invalidate($token);

            // Successfully invalidated
            return $this->apiResponse(['message' => 'Successfully logged out']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            // Token has been blacklisted, return Unauthorized message
            return $this->apiResponse(['message' => 'Unauthorized: Token is no longer valid']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Handle other JWT-related exceptions
            return $this->apiResponse(['message' => 'Failed to log out, please try again']);
        }
    }

    public function get_authorized_user()
    {
        $user = Auth::user();

        return $this->apiResponse([
            'user' => $user
        ]);
    }
}
