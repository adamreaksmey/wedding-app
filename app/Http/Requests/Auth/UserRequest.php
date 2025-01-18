<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Validator;

class UserRequest
{
    public static function validated($data)
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|min:20'
        ]);

        if ($validator->fails()) {
            abort(response()->json(['errors' => $validator->errors()], 422));
        }

        return $validator->validated();
    }

    public static function login($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            abort(response()->json(['errors' => $validator->errors()], 422));
        }

        return $validator->validated();
    }
}
